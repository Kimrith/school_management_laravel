<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherGradeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_grades_page_loads_data_from_db(): void
    {
        $teacherUser = User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Teacher,
        ]);

        $teacherProfile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'teacher_code' => 'TCH-001',
        ]);

        $classroom = Classroom::create([
            'name' => 'Grade 10-A',
            'grade_level' => 'Grade 10',
            'academic_year' => '2025-2026',
        ]);

        $subject = Subject::create([
            'name' => 'Mathematics',
            'code' => 'MATH101',
        ]);

        TeacherSubject::create([
            'teacher_id' => $teacherUser->id,
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
        ]);

        $exam = Exam::create([
            'title' => 'Midterm Mathematics',
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        $studentUser = User::create([
            'name' => 'Alice Student',
            'email' => 'alice@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Student,
        ]);

        $student = StudentProfile::create([
            'user_id' => $studentUser->id,
            'classroom_id' => $classroom->id,
            'student_code' => 'STU-001',
            'gender' => 'female',
        ]);

        Mark::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'marks_obtained' => 88.5,
            'grade_letter' => 'B+',
        ]);

        $response = $this->actingAs($teacherUser)->get(route('teacher.grades.index', [
            'classroom_id' => $classroom->id,
            'exam_id' => $exam->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Alice Student');
        $response->assertSee('STU-001');
        $response->assertSee('88.5');
    }

    public function test_teacher_can_save_and_publish_marks_to_database(): void
    {
        $teacherUser = User::create([
            'name' => 'Teacher One',
            'email' => 'teacher1@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Teacher,
        ]);

        TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'teacher_code' => 'TCH-001',
        ]);

        $classroom = Classroom::create([
            'name' => 'Grade 10-A',
            'grade_level' => 'Grade 10',
            'academic_year' => '2025-2026',
        ]);

        $subject = Subject::create([
            'name' => 'Science',
            'code' => 'SCI101',
        ]);

        $exam = Exam::create([
            'title' => 'Final Exam Science',
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        $studentUser = User::create([
            'name' => 'Bob Student',
            'email' => 'bob@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Student,
        ]);

        $student = StudentProfile::create([
            'user_id' => $studentUser->id,
            'classroom_id' => $classroom->id,
            'student_code' => 'STU-002',
            'gender' => 'male',
        ]);

        $response = $this->actingAs($teacherUser)->post(route('teacher.grades.store'), [
            'exam_id' => $exam->id,
            'classroom_id' => $classroom->id,
            'marks' => [
                [
                    'student_id' => $student->id,
                    'marks_obtained' => 95.0,
                ],
            ],
        ]);

        $response->assertRedirect(route('teacher.grades.index', [
            'classroom_id' => $classroom->id,
            'exam_title' => $exam->title,
            'exam_id' => $exam->id,
        ]));

        $this->assertDatabaseHas('marks', [
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'marks_obtained' => 95.0,
            'grade_letter' => 'A',
        ]);
    }
}
