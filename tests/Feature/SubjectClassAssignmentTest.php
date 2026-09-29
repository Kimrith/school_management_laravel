<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectClassAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_subject_can_be_created_and_assigned_to_multiple_classes(): void
    {
        $classA = Classroom::create(['name' => 'Grade 10-A', 'grade_level' => 'Grade 10', 'academic_year' => '2025-2026']);
        $classB = Classroom::create(['name' => 'Grade 11-B', 'grade_level' => 'Grade 11', 'academic_year' => '2025-2026']);

        $response = $this->actingAs($this->admin)->post(route('admin.subjects.store'), [
            'name' => 'Advanced Robotics',
            'code' => 'ROB401',
            'description' => 'Hands-on robotics and automated system engineering.',
            'classrooms' => [$classA->id, $classB->id],
        ]);

        $response->assertRedirect(route('admin.subjects.index'));

        $subject = Subject::where('code', 'ROB401')->first();
        $this->assertNotNull($subject);
        $this->assertEquals('Advanced Robotics', $subject->name);

        // Verify that both classrooms are associated with this subject
        $assignedClasses = $subject->classrooms()->get();
        $this->assertCount(2, $assignedClasses);
        $this->assertTrue($assignedClasses->pluck('id')->contains($classA->id));
        $this->assertTrue($assignedClasses->pluck('id')->contains($classB->id));
    }

    public function test_subject_classes_can_be_updated_and_synced(): void
    {
        $classA = Classroom::create(['name' => 'Grade 10-A', 'grade_level' => 'Grade 10', 'academic_year' => '2025-2026']);
        $classB = Classroom::create(['name' => 'Grade 11-B', 'grade_level' => 'Grade 11', 'academic_year' => '2025-2026']);
        $classC = Classroom::create(['name' => 'Grade 12-C', 'grade_level' => 'Grade 12', 'academic_year' => '2025-2026']);

        $subject = Subject::create([
            'name' => 'Physics II',
            'code' => 'PHY202',
            'description' => 'Mechanics and Thermodynamics',
        ]);

        TeacherSubject::create([
            'teacher_id' => null,
            'subject_id' => $subject->id,
            'classroom_id' => $classA->id,
        ]);
        TeacherSubject::create([
            'teacher_id' => null,
            'subject_id' => $subject->id,
            'classroom_id' => $classB->id,
        ]);

        $this->assertCount(2, $subject->classrooms()->get());

        // Update to assign classB and classC (removing classA)
        $response = $this->actingAs($this->admin)->put(route('admin.subjects.update', $subject), [
            'name' => 'Physics II (Updated)',
            'code' => 'PHY202',
            'description' => 'Mechanics and Advanced Thermodynamics',
            'classrooms' => [$classB->id, $classC->id],
        ]);

        $response->assertRedirect(route('admin.subjects.index'));

        $subject->refresh();
        $this->assertEquals('Physics II (Updated)', $subject->name);

        $syncedClasses = $subject->classrooms()->get();
        $this->assertCount(2, $syncedClasses);
        $this->assertFalse($syncedClasses->pluck('id')->contains($classA->id));
        $this->assertTrue($syncedClasses->pluck('id')->contains($classB->id));
        $this->assertTrue($syncedClasses->pluck('id')->contains($classC->id));
    }

    public function test_admin_subjects_view_renders_with_multi_class_badges(): void
    {
        $classA = Classroom::create(['name' => 'Grade 10-A', 'grade_level' => 'Grade 10', 'academic_year' => '2025-2026']);
        $classB = Classroom::create(['name' => 'Grade 11-B', 'grade_level' => 'Grade 11', 'academic_year' => '2025-2026']);

        $subject = Subject::create([
            'name' => 'Data Structures',
            'code' => 'CS201',
            'description' => 'Algorithms and data structures',
        ]);

        TeacherSubject::create([
            'teacher_id' => null,
            'subject_id' => $subject->id,
            'classroom_id' => $classA->id,
        ]);
        TeacherSubject::create([
            'teacher_id' => null,
            'subject_id' => $subject->id,
            'classroom_id' => $classB->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.subjects.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Structures');
        $response->assertSee('CS201');
        $response->assertSee('2 Classes');
        $response->assertSee('Grade 10-A');
        $response->assertSee('Grade 11-B');
    }
}
