<?php

namespace Tests\Feature;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalViewsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@school.edu',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_admin_students_view_renders(): void
    {
        $studentUser = User::create([
            'name' => 'John Doe',
            'email' => 'student@school.edu',
            'password' => bcrypt('password123'),
        ]);
        StudentProfile::create([
            'user_id' => $studentUser->id,
            'student_code' => 'STU-1001',
            'gender' => 'male',
        ]);

        $response = $this->actingAs($this->user)->get('/admin/students');
        $response->assertStatus(200);
        $response->assertSee('Students Directory');
        $response->assertSee('Add Student');
        $response->assertSee('STU-1001');
    }

    public function test_admin_teachers_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/teachers');
        $response->assertStatus(200);
        $response->assertSee('Faculty Members');
        $response->assertSee('Add Teacher');
    }

    public function test_admin_classes_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/classes');
        $response->assertStatus(200);
        $response->assertSee('Classrooms');
        $response->assertSee('Add Classroom');
    }

    public function test_admin_attendances_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/attendances');
        $response->assertStatus(200);
        $response->assertSee('Attendance Monitoring');
        $response->assertSee('Present Today');
    }

    public function test_admin_fees_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/fees');
        $response->assertStatus(200);
        $response->assertSee('Fee Billing');
        $response->assertSee('Create Invoice');
    }

    public function test_admin_subjects_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/subjects');
        $response->assertStatus(200);
        $response->assertSee('Academic Subjects');
        $response->assertSee('Add Subject');
    }

    public function test_admin_exams_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/exams');
        $response->assertStatus(200);
        $response->assertSee('Examinations');
        $response->assertSee('Schedule Exam');
    }

    public function test_teacher_dashboard_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/teacher/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Faculty Dashboard');
        $response->assertSee('Assigned Classes');
    }

    public function test_teacher_attendance_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/teacher/attendance');
        $response->assertStatus(200);
        $response->assertSee('Class Attendance Sheet');
        $response->assertSee('Mark All Present');
    }

    public function test_teacher_grades_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/teacher/grades');
        $response->assertStatus(200);
        $response->assertSee('Input Exam Marks');
        $response->assertSee('Publish Marks');
    }

    public function test_student_dashboard_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/student/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Download Report Card');
        $response->assertSee('Recent Exam Results');
    }

    public function test_pdf_report_card_view_renders(): void
    {
        $response = $this->actingAs($this->user)->get('/pdf/report-card');
        $response->assertStatus(200);
        $response->assertSee('SETEC INSTITUTE');
        $response->assertSee('Academic Transcript');
    }
}
