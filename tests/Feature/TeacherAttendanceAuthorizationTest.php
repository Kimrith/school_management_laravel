<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherAttendanceAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacherWithoutClasses;

    protected User $teacherWithClasses;

    protected Classroom $assignedClassroom;

    protected Classroom $unassignedClassroom;

    protected StudentProfile $studentInAssignedClass;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Teacher without any assigned classes
        $this->teacherWithoutClasses = User::create([
            'name' => 'Teacher Unassigned',
            'email' => 'teacher@school.edu',
            'password' => Hash::make('password123'),
            'role' => Role::Teacher,
        ]);

        TeacherProfile::create([
            'user_id' => $this->teacherWithoutClasses->id,
            'phone' => '012 111 222',
        ]);

        // 2. Teacher with assigned classes
        $this->teacherWithClasses = User::create([
            'name' => 'Prof. Virak Meas',
            'email' => 'virak.meas@school.edu',
            'password' => Hash::make('password123'),
            'role' => Role::Teacher,
        ]);

        TeacherProfile::create([
            'user_id' => $this->teacherWithClasses->id,
            'phone' => '012 333 444',
        ]);

        // 3. Classrooms
        $this->assignedClassroom = Classroom::create([
            'name' => 'Grade 10-A',
            'grade_level' => 'Grade 10',
            'academic_year' => '2025-2026',
        ]);

        $this->unassignedClassroom = Classroom::create([
            'name' => 'Brendan Cobb & SV',
            'grade_level' => 'Grade 12',
            'academic_year' => '2025-2026',
        ]);

        // Assign Grade 10-A to $teacherWithClasses
        TeacherSubject::create([
            'teacher_id' => $this->teacherWithClasses->id,
            'classroom_id' => $this->assignedClassroom->id,
            'subject_id' => null,
        ]);

        // Add a student to assigned classroom
        $studentUser = User::create([
            'name' => 'John Student',
            'email' => 'student@school.edu',
            'password' => Hash::make('password123'),
            'role' => Role::Student,
        ]);

        $this->studentInAssignedClass = StudentProfile::create([
            'user_id' => $studentUser->id,
            'student_code' => 'STU-1001',
            'classroom_id' => $this->assignedClassroom->id,
            'gender' => 'male',
        ]);
    }

    public function test_teacher_without_classes_sees_empty_dropdown_and_restriction_message(): void
    {
        $response = $this->actingAs($this->teacherWithoutClasses)->get(route('teacher.attendance.index'));

        $response->assertStatus(200);
        $response->assertSee('No Assigned Classrooms');
        $response->assertSee('No assigned classrooms');
        $response->assertDontSee('value="'.$this->assignedClassroom->id.'"', false);
        $response->assertDontSee('value="'.$this->unassignedClassroom->id.'"', false);
        $response->assertDontSee('Brendan Cobb &amp; SV', false);
    }

    public function test_teacher_with_classes_only_sees_assigned_classrooms(): void
    {
        $response = $this->actingAs($this->teacherWithClasses)->get(route('teacher.attendance.index'));

        $response->assertStatus(200);
        $response->assertSee('Grade 10-A');
        $response->assertDontSee('Brendan Cobb &amp; SV', false);
        $response->assertDontSee('value="'.$this->unassignedClassroom->id.'"', false);
    }

    public function test_unauthorized_teacher_cannot_view_unassigned_classroom_attendance(): void
    {
        $response = $this->actingAs($this->teacherWithoutClasses)
            ->get(route('teacher.attendance.index', ['classroom_id' => $this->assignedClassroom->id]));

        $response->assertStatus(403);
    }

    public function test_unauthorized_teacher_cannot_submit_attendance_for_unassigned_classroom(): void
    {
        $response = $this->actingAs($this->teacherWithoutClasses)->post(route('teacher.attendance.store'), [
            'classroom_id' => $this->assignedClassroom->id,
            'date' => now()->format('Y-m-d'),
            'attendances' => [
                [
                    'student_id' => $this->studentInAssignedClass->id,
                    'status' => 'present',
                    'remarks' => 'Hacked attendance attempt',
                ],
            ],
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('attendances', [
            'student_id' => $this->studentInAssignedClass->id,
            'remarks' => 'Hacked attendance attempt',
        ]);
    }

    public function test_authorized_teacher_can_view_and_submit_attendance(): void
    {
        $date = now()->format('Y-m-d');

        // View attendance sheet
        $response = $this->actingAs($this->teacherWithClasses)
            ->get(route('teacher.attendance.index', ['classroom_id' => $this->assignedClassroom->id, 'date' => $date]));

        $response->assertStatus(200);
        $response->assertSee('John Student');

        // Submit attendance
        $postResponse = $this->actingAs($this->teacherWithClasses)->post(route('teacher.attendance.store'), [
            'classroom_id' => $this->assignedClassroom->id,
            'date' => $date,
            'attendances' => [
                [
                    'student_id' => $this->studentInAssignedClass->id,
                    'status' => 'present',
                    'remarks' => 'Present on time',
                ],
            ],
        ]);

        $postResponse->assertRedirect();
        $postResponse->assertSessionHas('success');

        $attendance = Attendance::where('classroom_id', $this->assignedClassroom->id)
            ->where('student_id', $this->studentInAssignedClass->id)
            ->first();

        $this->assertNotNull($attendance);
        $this->assertEquals('present', $attendance->status);
        $this->assertEquals('Present on time', $attendance->remarks);
        $this->assertEquals($date, Carbon::parse($attendance->date)->format('Y-m-d'));
    }

    public function test_classroom_for_teacher_scope_and_teaches_classroom_helper(): void
    {
        $classesForTeacherWithClasses = Classroom::forTeacher($this->teacherWithClasses)->get();
        $this->assertCount(1, $classesForTeacherWithClasses);
        $this->assertTrue($classesForTeacherWithClasses->contains($this->assignedClassroom));
        $this->assertFalse($classesForTeacherWithClasses->contains($this->unassignedClassroom));

        $classesForTeacherWithoutClasses = Classroom::forTeacher($this->teacherWithoutClasses)->get();
        $this->assertCount(0, $classesForTeacherWithoutClasses);

        $this->assertTrue($this->teacherWithClasses->teachesClassroom($this->assignedClassroom));
        $this->assertFalse($this->teacherWithClasses->teachesClassroom($this->unassignedClassroom));
        $this->assertFalse($this->teacherWithoutClasses->teachesClassroom($this->assignedClassroom));
    }
}
