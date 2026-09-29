<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherClassAssignmentTest extends TestCase
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

    public function test_teacher_can_be_created_and_assigned_to_multiple_classes(): void
    {
        $classA = Classroom::create(['name' => 'Grade 10-A', 'grade_level' => 'Grade 10', 'academic_year' => '2025-2026']);
        $classB = Classroom::create(['name' => 'Grade 11-B', 'grade_level' => 'Grade 11', 'academic_year' => '2025-2026']);
        $subject = Subject::create(['name' => 'Computer Science', 'code' => 'CS101']);

        $response = $this->actingAs($this->admin)->post(route('admin.teachers.store'), [
            'name' => 'Dr. Jane Smith',
            'email' => 'jane.smith@school.edu',
            'phone' => '012 999 888',
            'qualification' => 'Ph.D. in CS',
            'specializations' => [$subject->name],
            'classrooms' => [$classA->id, $classB->id],
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        $teacherUser = User::where('email', 'jane.smith@school.edu')->first();
        $this->assertNotNull($teacherUser);

        $teacherProfile = TeacherProfile::where('user_id', $teacherUser->id)->first();
        $this->assertNotNull($teacherProfile);

        // Verify taught classrooms count and contents
        $assignedClasses = $teacherProfile->taughtClassrooms->unique('id');
        $this->assertCount(2, $assignedClasses);
        $this->assertTrue($assignedClasses->pluck('id')->contains($classA->id));
        $this->assertTrue($assignedClasses->pluck('id')->contains($classB->id));
    }

    public function test_teacher_classes_can_be_updated_and_synced(): void
    {
        $classA = Classroom::create(['name' => 'Grade 10-A', 'grade_level' => 'Grade 10', 'academic_year' => '2025-2026']);
        $classB = Classroom::create(['name' => 'Grade 11-B', 'grade_level' => 'Grade 11', 'academic_year' => '2025-2026']);
        $classC = Classroom::create(['name' => 'Grade 12-A', 'grade_level' => 'Grade 12', 'academic_year' => '2025-2026']);

        $teacherUser = User::create([
            'name' => 'Mr. John Doe',
            'email' => 'john.doe@school.edu',
            'password' => bcrypt('password123'),
        ]);

        $teacherProfile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'phone' => '011 222 333',
        ]);

        // Initially assign Class A
        TeacherSubject::create([
            'teacher_id' => $teacherUser->id,
            'classroom_id' => $classA->id,
            'subject_id' => null,
        ]);

        $this->assertCount(1, $teacherProfile->taughtClassrooms);

        // Update to assign Class B and Class C instead of Class A
        $response = $this->actingAs($this->admin)->put(route('admin.teachers.update', $teacherProfile->id), [
            'name' => 'Mr. John Doe Updated',
            'email' => 'john.doe@school.edu',
            'classrooms' => [$classB->id, $classC->id],
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        $teacherProfile->refresh();
        $updatedClasses = $teacherProfile->taughtClassrooms->unique('id');
        $this->assertCount(2, $updatedClasses);
        $this->assertFalse($updatedClasses->pluck('id')->contains($classA->id));
        $this->assertTrue($updatedClasses->pluck('id')->contains($classB->id));
        $this->assertTrue($updatedClasses->pluck('id')->contains($classC->id));
    }

    public function test_query_view_displays_assigned_class_badges(): void
    {
        $classA = Classroom::create(['name' => 'Grade 10-A', 'grade_level' => 'Grade 10', 'academic_year' => '2025-2026']);
        $classB = Classroom::create(['name' => 'Grade 11-B', 'grade_level' => 'Grade 11', 'academic_year' => '2025-2026']);

        $teacherUser = User::create([
            'name' => 'Prof. Multi Class',
            'email' => 'multi@school.edu',
            'password' => bcrypt('password123'),
        ]);

        $teacherProfile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'qualification' => 'Master of Education',
        ]);

        TeacherSubject::create(['teacher_id' => $teacherUser->id, 'classroom_id' => $classA->id, 'subject_id' => null]);
        TeacherSubject::create(['teacher_id' => $teacherUser->id, 'classroom_id' => $classB->id, 'subject_id' => null]);

        $response = $this->actingAs($this->admin)->get(route('admin.teachers.index'));

        $response->assertStatus(200);
        $response->assertSee('Grade 10-A');
        $response->assertSee('Grade 11-B');
    }
}
