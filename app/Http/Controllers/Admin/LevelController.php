<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        // Seed default academic levels if table is empty
        if (Level::count() === 0) {
            $defaultLevels = [
                ['name' => 'Grade 7', 'status' => 'Active'],
                ['name' => 'Grade 8', 'status' => 'Active'],
                ['name' => 'Grade 9', 'status' => 'Active'],
                ['name' => 'Grade 10', 'status' => 'Active'],
                ['name' => 'Grade 11', 'status' => 'Active'],
                ['name' => 'Grade 12', 'status' => 'Active'],
            ];
            foreach ($defaultLevels as $lvl) {
                Level::create($lvl);
            }
        }

        $statusFilter = strtolower($request->query('status', 'all'));

        $query = Level::withCount('classrooms');

        if ($statusFilter === 'active') {
            $query->whereRaw('LOWER(status) = ?', ['active']);
        } elseif ($statusFilter === 'suspended' || $statusFilter === 'inactive') {
            $query->whereRaw('LOWER(status) IN (?, ?)', ['suspended', 'inactive']);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where('name', 'like', "%{$search}%");
        }

        $levels = $query->orderBy('name')->paginate(7)->withQueryString();

        $counts = [
            'all' => Level::count(),
            'active' => Level::whereRaw('LOWER(status) = ?', ['active'])->count(),
            'suspended' => Level::whereRaw('LOWER(status) IN (?, ?)', ['suspended', 'inactive'])->count(),
            'total_classrooms' => Classroom::count(),
        ];

        return view('admin.level.index', compact('levels', 'counts', 'statusFilter'));
    }

    public function create()
    {
        return redirect()->route('admin.levels.index')->with('open_add_modal', true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:levels,name',
            'status' => 'required|string|in:Active,Suspended,active,suspended',
        ]);

        $level = Level::create([
            'name' => $validated['name'],
            'status' => ucfirst(strtolower($validated['status'])),
        ]);

        return redirect()->route('admin.levels.index')->with('success', "Academic Level '{$level->name}' created successfully!");
    }

    public function edit(Level $level)
    {
        $level->loadCount('classrooms');

        return view('admin.level.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:levels,name,'.$level->id,
            'status' => 'required|string|in:Active,Suspended,active,suspended',
        ]);

        $level->update([
            'name' => $validated['name'],
            'status' => ucfirst(strtolower($validated['status'])),
        ]);

        return redirect()->route('admin.levels.index')->with('success', "Academic Level '{$level->name}' updated successfully!");
    }

    public function toggleStatus(Level $level)
    {
        $current = strtolower($level->status);
        $newStatus = ($current === 'suspended' || $current === 'inactive') ? 'Active' : 'Suspended';

        $level->update(['status' => $newStatus]);

        $msg = $newStatus === 'Suspended'
            ? "Academic Level '{$level->name}' has been suspended."
            : "Academic Level '{$level->name}' has been reinstated as active.";

        return redirect()->back()->with('success', $msg);
    }

    public function suspended(Request $request)
    {
        $query = Level::withCount('classrooms')
            ->whereRaw('LOWER(status) IN (?, ?)', ['suspended', 'inactive']);

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where('name', 'like', "%{$search}%");
        }

        $levels = $query->orderBy('name')->paginate(7)->withQueryString();
        $activeCount = Level::whereRaw('LOWER(status) = ?', ['active'])->count();
        $suspendedCount = Level::whereRaw('LOWER(status) IN (?, ?)', ['suspended', 'inactive'])->count();

        return view('admin.level.suspended', compact('levels', 'activeCount', 'suspendedCount'));
    }

    public function destroy(Level $level)
    {
        $name = $level->name;
        $level->delete();

        return redirect()->route('admin.levels.index')->with('success', "Academic Level '{$name}' deleted successfully!");
    }
}
