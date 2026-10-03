<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_student_scores_and_reports_pushed_by_teachers(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Admin,
        ]);

        $classroom = Classroom::create([
            'name' => 'Classroom 10-A',
            'grade_level' => 'Grade 10',
            'academic_year' => '2025-2026',
        ]);

        $subject = Subject::create([
            'name' => 'Physics',
            'code' => 'PHY101',
        ]);

        $exam = Exam::create([
            'title' => 'Final Exam Term 1',
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        $studentUser = User::create([
            'name' => 'Sokha Chan',
            'email' => 'sokha@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Student,
        ]);

        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'student_code' => 'STU-001',
            'classroom_id' => $classroom->id,
            'date_of_birth' => '2008-01-15',
            'gender' => 'male',
        ]);

        // Teacher pushes mark to DB
        Mark::create([
            'student_id' => $studentProfile->id,
            'exam_id' => $exam->id,
            'marks_obtained' => 92.50,
            'grade_letter' => 'A',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.index'));

        $response->assertOk();
        $response->assertSee('Student Scores & Examination Reports', false);
        $response->assertSee('Sokha Chan');
        $response->assertSee('STU-001');
        $response->assertSee('Physics');
        $response->assertSee('92.5');
        $response->assertSee('Final Exam Term 1');
    }

    public function test_admin_reports_can_filter_by_classroom_and_title(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Admin,
        ]);

        $classroomA = Classroom::create([
            'name' => 'Grade 11-A',
            'grade_level' => 'Grade 11',
            'academic_year' => '2025-2026',
        ]);

        $classroomB = Classroom::create([
            'name' => 'Grade 11-B',
            'grade_level' => 'Grade 11',
            'academic_year' => '2025-2026',
        ]);

        $subject = Subject::create([
            'name' => 'Chemistry',
            'code' => 'CHEM101',
        ]);

        $examA = Exam::create([
            'title' => 'Midterm',
            'subject_id' => $subject->id,
            'classroom_id' => $classroomA->id,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        $examB = Exam::create([
            'title' => 'Final',
            'subject_id' => $subject->id,
            'classroom_id' => $classroomB->id,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        $studentA = User::create([
            'name' => 'Student Alpha',
            'email' => 'alpha@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Student,
        ]);

        $profileA = StudentProfile::create([
            'user_id' => $studentA->id,
            'student_code' => 'STU-100',
            'classroom_id' => $classroomA->id,
            'date_of_birth' => '2007-05-10',
            'gender' => 'male',
        ]);

        $studentB = User::create([
            'name' => 'Student Beta',
            'email' => 'beta@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Student,
        ]);

        $profileB = StudentProfile::create([
            'user_id' => $studentB->id,
            'student_code' => 'STU-200',
            'classroom_id' => $classroomB->id,
            'date_of_birth' => '2007-06-12',
            'gender' => 'female',
        ]);

        Mark::create([
            'student_id' => $profileA->id,
            'exam_id' => $examA->id,
            'marks_obtained' => 88.0,
            'grade_letter' => 'A',
        ]);

        Mark::create([
            'student_id' => $profileB->id,
            'exam_id' => $examB->id,
            'marks_obtained' => 45.0,
            'grade_letter' => 'F',
        ]);

        // Filter by classroom A
        $response = $this->actingAs($admin)->get(route('admin.reports.index', ['classroom_id' => $classroomA->id]));
        $response->assertOk();
        $response->assertSee('Student Alpha');
        $response->assertDontSee('Student Beta');

        // Filter by status=failed (<50)
        $responseFailed = $this->actingAs($admin)->get(route('admin.reports.index', ['status' => 'failed']));
        $responseFailed->assertOk();
        $responseFailed->assertSee('Student Beta');
        $responseFailed->assertDontSee('Student Alpha');
    }

    public function test_non_admin_cannot_access_admin_reports(): void
    {
        $teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Teacher,
        ]);

        $response = $this->actingAs($teacher)->get(route('admin.reports.index'));
        $response->assertForbidden();
    }

    public function test_report_card_view_is_dynamic_based_on_student_id_or_user_id(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Admin,
        ]);

        $classroom = Classroom::create([
            'name' => 'Grade 12-Science',
            'grade_level' => 'Grade 12',
            'academic_year' => '2025-2026',
        ]);

        $studentUser = User::create([
            'name' => 'Vannak Keo',
            'email' => 'vannak@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Student,
        ]);

        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'student_code' => 'STU-9999',
            'classroom_id' => $classroom->id,
            'date_of_birth' => '2007-03-20',
            'gender' => 'male',
        ]);

        $subject = Subject::create([
            'name' => 'Advanced Robotics',
            'code' => 'ROB401',
        ]);

        $exam = Exam::create([
            'title' => 'Midterm Robotics Exam',
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
        ]);

        Mark::create([
            'student_id' => $studentProfile->id,
            'exam_id' => $exam->id,
            'marks_obtained' => 97.50,
            'grade_letter' => 'A',
        ]);

        // 1. Query by student_id
        $responseStudentId = $this->actingAs($admin)->get(route('pdf.report-card', ['student_id' => $studentProfile->id]));
        $responseStudentId->assertOk();
        $responseStudentId->assertSee('Vannak Keo');
        $responseStudentId->assertSee('STU-9999');
        $responseStudentId->assertSee('Grade 12-Science');
        $responseStudentId->assertSee('ROB401');
        $responseStudentId->assertSee('Advanced Robotics');
        $responseStudentId->assertSee('97.50');

        // 2. Query by user_id
        $responseUserId = $this->actingAs($admin)->get(route('pdf.report-card', ['user_id' => $studentUser->id]));
        $responseUserId->assertOk();
        $responseUserId->assertSee('Vannak Keo');
        $responseUserId->assertSee('STU-9999');

        // 3. Authenticated student accessing /pdf/report-card directly
        $responseStudentSelf = $this->actingAs($studentUser)->get(route('pdf.report-card'));
        $responseStudentSelf->assertOk();
        $responseStudentSelf->assertSee('Vannak Keo');
        $responseStudentSelf->assertSee('STU-9999');
    }
}
