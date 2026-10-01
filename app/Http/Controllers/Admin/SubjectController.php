<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $classrooms = Classroom::orderBy('name')->get();
        $levels = Level::whereRaw('LOWER(status) = ?', ['active'])->orderBy('name')->get();

        $query = Subject::with(['level', 'classrooms'])->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $subjects = $query->paginate(6)->withQueryString();

        $subjectsList = $subjects->through(function ($subject) {
            $classroomsList = $subject->classrooms->unique('id');
            $firstClassroom = $classroomsList->first();

            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'desc' => $subject->description,
                'level_id' => $subject->level_id ?? '',
                'level_name' => $subject->level?->name ?? 'All Levels',
                'classroom_id' => $firstClassroom?->id ?? '',
                'classroom_name' => $firstClassroom?->name ?? 'Not Assigned',
                'classroom_ids' => $classroomsList->pluck('id')->toArray(),
                'classes' => $classroomsList->pluck('name')->toArray(),
                'classrooms_count' => $classroomsList->count(),
            ];
        });

        return view('admin.subjects.index', compact('subjects', 'subjectsList', 'classrooms', 'levels'));
    }

    public function create()
    {
        $classrooms = Classroom::orderBy('name')->get();
        $levels = Level::whereRaw('LOWER(status) = ?', ['active'])->orderBy('name')->get();

        return view('admin.subjects.insert', compact('classrooms', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'level_id' => 'nullable|exists:levels,id',
            'classrooms' => 'nullable|array',
            'classrooms.*' => 'exists:classrooms,id',
            'classroom_ids' => 'nullable|array',
            'classroom_ids.*' => 'exists:classrooms,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'level_id' => $validated['level_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $selectedClassrooms = array_values(array_filter(array_map('intval', (array) (
            $request->input('classrooms') ?? $request->input('classroom_ids') ?? ($request->filled('classroom_id') ? [$request->input('classroom_id')] : [])
        ))));

        if (! empty($selectedClassrooms)) {
            $teacherUser = User::where('role', Role::Teacher)->first();
            $teacherId = $teacherUser?->id;
            foreach ($selectedClassrooms as $classroomId) {
                TeacherSubject::firstOrCreate([
                    'teacher_id' => $teacherId,
                    'subject_id' => $subject->id,
                    'classroom_id' => $classroomId,
                ]);
            }
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Academic subject registered successfully!');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,'.$subject->id,
            'level_id' => 'nullable|exists:levels,id',
            'classrooms' => 'nullable|array',
            'classrooms.*' => 'exists:classrooms,id',
            'classroom_ids' => 'nullable|array',
            'classroom_ids.*' => 'exists:classrooms,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'description' => 'nullable|string',
        ]);

        $subject->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'level_id' => $validated['level_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $selectedClassrooms = array_values(array_filter(array_map('intval', (array) (
            $request->input('classrooms') ?? $request->input('classroom_ids') ?? ($request->filled('classroom_id') ? [$request->input('classroom_id')] : [])
        ))));

        // Sync classrooms for this subject
        $currentClassroomIds = TeacherSubject::where('subject_id', $subject->id)
            ->pluck('classroom_id')
            ->unique()
            ->toArray();

        // Classrooms to remove
        $toRemove = array_diff($currentClassroomIds, $selectedClassrooms);
        if (! empty($toRemove)) {
            TeacherSubject::where('subject_id', $subject->id)
                ->whereIn('classroom_id', $toRemove)
                ->delete();
        }

        // Classrooms to add
        $toAdd = array_diff($selectedClassrooms, $currentClassroomIds);
        if (! empty($toAdd)) {
            $teacherUser = User::where('role', Role::Teacher)->first();
            $teacherId = $teacherUser?->id;
            foreach ($toAdd as $classroomId) {
                TeacherSubject::firstOrCreate([
                    'teacher_id' => $teacherId,
                    'subject_id' => $subject->id,
                    'classroom_id' => $classroomId,
                ]);
            }
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Academic subject updated successfully!');
    }

    public function destroy(Subject $subject)
    {
        TeacherSubject::where('subject_id', $subject->id)->delete();

        // Clean up deleted subject from teacher profile specializations
        $teachers = TeacherProfile::where('specialization', 'like', '%'.$subject->name.'%')->get();
        foreach ($teachers as $teacher) {
            $specs = array_filter(array_map('trim', explode(',', (string) $teacher->specialization)));
            $updatedSpecs = array_values(array_filter($specs, fn ($s) => $s !== $subject->name));
            $teacher->update([
                'specialization' => ! empty($updatedSpecs) ? implode(', ', $updatedSpecs) : null,
            ]);
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Academic subject deleted successfully!');
    }
}
