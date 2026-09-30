<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');

        $query = Classroom::with(['level', 'teachers', 'subjects'])
            ->withCount('studentProfiles');

        if ($statusFilter === 'active') {
            $query->where('status', '!=', 'suspended');
        } elseif ($statusFilter === 'suspended') {
            $query->where('status', 'suspended');
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhere('room', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grade') && $request->grade !== 'all') {
            $query->where('grade_level', $request->grade);
        }

        $classrooms = $query->orderBy('name')->paginate(7)->withQueryString();

        $allClassrooms = Classroom::withCount('studentProfiles')->get();
        $totalCapacity = $allClassrooms->sum('capacity') ?: 1;
        $totalEnrolled = $allClassrooms->sum('student_profiles_count');

        $counts = [
            'all' => Classroom::count(),
            'active' => Classroom::where('status', '!=', 'suspended')->count(),
            'suspended' => Classroom::where('status', 'suspended')->count(),
            'total_students' => $totalEnrolled,
            'utilization' => min(100, round(($totalEnrolled / max(1, $totalCapacity)) * 100)),
        ];

        $teachers = User::where('role', Role::Teacher)->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $levels = Level::whereRaw('LOWER(status) = ?', ['active'])->orderBy('name')->get();
        $gradeLevels = $levels->isNotEmpty()
            ? $levels->pluck('name')->values()
            : Classroom::select('grade_level')->distinct()->pluck('grade_level')->filter()->values();

        return view('admin.classes.index', compact('classrooms', 'counts', 'statusFilter', 'teachers', 'subjects', 'gradeLevels', 'levels'));
    }

    public function create()
    {
        return redirect()->route('admin.classes.index')->with('open_add_modal', true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'level_id' => 'nullable|exists:levels,id',
            'academic_year' => 'required|string|max:50',
            'room' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1|max:200',
            'description' => 'nullable|string|max:500',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $matchedLevel = Level::where('name', $validated['grade_level'])->first();
        $levelId = $request->input('level_id') ?: $matchedLevel?->id;

        $classroom = Classroom::create([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'],
            'level_id' => $levelId,
            'academic_year' => $validated['academic_year'],
            'room' => $validated['room'] ?? null,
            'capacity' => $validated['capacity'] ?? 40,
            'status' => 'active',
            'description' => $validated['description'] ?? null,
        ]);

        if (! empty($validated['teacher_id'])) {
            TeacherSubject::firstOrCreate([
                'teacher_id' => $validated['teacher_id'],
                'classroom_id' => $classroom->id,
                'subject_id' => null,
            ]);
        }

        return redirect()->route('admin.classes.index')->with('success', "Classroom '{$classroom->name}' created successfully!");
    }

    public function edit(Classroom $classroom)
    {
        $classroom->load(['teachers', 'subjects', 'level'])->loadCount('studentProfiles');
        $teachers = User::where('role', Role::Teacher)->orderBy('name')->get();
        $academicYears = ['2025-2026', '2026-2027', '2024-2025'];
        $levels = Level::whereRaw('LOWER(status) = ?', ['active'])->orderBy('name')->get();
        $gradeLevels = $levels->isNotEmpty()
            ? $levels->pluck('name')->values()
            : ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];

        return view('admin.classes.edit', compact('classroom', 'teachers', 'academicYears', 'gradeLevels', 'levels'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'level_id' => 'nullable|exists:levels,id',
            'academic_year' => 'required|string|max:50',
            'room' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1|max:200',
            'status' => 'nullable|string|in:active,suspended',
            'description' => 'nullable|string|max:500',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $matchedLevel = Level::where('name', $validated['grade_level'])->first();
        $levelId = $request->input('level_id') ?: $matchedLevel?->id;

        $classroom->update([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'],
            'level_id' => $levelId,
            'academic_year' => $validated['academic_year'],
            'room' => $validated['room'] ?? null,
            'capacity' => $validated['capacity'] ?? 40,
            'status' => $validated['status'] ?? $classroom->status ?? 'active',
            'description' => $validated['description'] ?? null,
        ]);

        if (array_key_exists('teacher_id', $validated)) {
            if ($validated['teacher_id']) {
                TeacherSubject::firstOrCreate([
                    'teacher_id' => $validated['teacher_id'],
                    'classroom_id' => $classroom->id,
                    'subject_id' => null,
                ]);
            }
        }

        return redirect()->route('admin.classes.index')->with('success', "Classroom '{$classroom->name}' updated successfully!");
    }

    public function toggleStatus(Classroom $classroom)
    {
        $newStatus = ($classroom->status === 'suspended') ? 'active' : 'suspended';
        $classroom->update(['status' => $newStatus]);

        $message = $newStatus === 'suspended'
            ? "Classroom '{$classroom->name}' has been archived."
            : "Classroom '{$classroom->name}' has been reinstated as active.";

        return redirect()->back()->with('success', $message);
    }

    public function suspended(Request $request)
    {
        $query = Classroom::with(['teachers', 'subjects', 'level'])
            ->withCount('studentProfiles')
            ->where('status', 'suspended');

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('room', 'like', "%{$search}%");
            });
        }

        $classrooms = $query->orderBy('name')->paginate(7)->withQueryString();
        $activeCount = Classroom::where('status', '!=', 'suspended')->count();
        $suspendedCount = Classroom::where('status', 'suspended')->count();

        return view('admin.classes.suspend', compact('classrooms', 'activeCount', 'suspendedCount'));
    }

    public function destroy(Classroom $classroom)
    {
        StudentProfile::where('classroom_id', $classroom->id)->update(['classroom_id' => null]);
        TeacherSubject::where('classroom_id', $classroom->id)->delete();

        $name = $classroom->name;
        $classroom->delete();

        return redirect()->route('admin.classes.index')->with('success', "Classroom '{$name}' deleted successfully!");
    }
}
