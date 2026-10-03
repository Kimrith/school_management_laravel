<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Classroom $classroom;

    protected Subject $math;

    protected Subject $science;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Admin,
        ]);

        $this->classroom = Classroom::create([
            'name' => 'Grade 10-A',
            'grade_level' => 'Grade 10',
            'academic_year' => '2025-2026',
        ]);

        $this->math = Subject::create([
            'name' => 'Mathematics',
            'code' => 'MATH101',
        ]);

        $this->science = Subject::create([
            'name' => 'Science',
            'code' => 'SCI101',
        ]);
    }

    public function test_admin_can_schedule_exam_and_assign_multiple_subjects(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.exams.store'), [
            'title' => 'Final Examination 2026',
            'classroom_id' => $this->classroom->id,
            'subject_ids' => [$this->math->id, $this->science->id],
            'exam_date' => '2026-10-15',
            'total_marks' => 100.00,
        ]);

        $response->assertRedirect(route('admin.exams.index'));

        $this->assertDatabaseHas('exams', [
            'title' => 'Final Examination 2026',
            'classroom_id' => $this->classroom->id,
            'subject_id' => $this->math->id,
        ]);

        $this->assertDatabaseHas('exams', [
            'title' => 'Final Examination 2026',
            'classroom_id' => $this->classroom->id,
            'subject_id' => $this->science->id,
        ]);
    }

    public function test_admin_can_assign_another_subject_to_existing_exam_title(): void
    {
        Exam::create([
            'title' => 'Midterm 2026',
            'classroom_id' => $this->classroom->id,
            'subject_id' => $this->math->id,
            'exam_date' => '2026-10-10',
            'total_marks' => 100.00,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.exams.store'), [
            'title' => 'Midterm 2026',
            'classroom_id' => $this->classroom->id,
            'subject_id' => $this->science->id,
            'exam_date' => '2026-10-12',
            'total_marks' => 100.00,
        ]);

        $response->assertRedirect(route('admin.exams.index'));

        $this->assertDatabaseCount('exams', 2);
        $this->assertDatabaseHas('exams', [
            'title' => 'Midterm 2026',
            'subject_id' => $this->science->id,
        ]);
    }

    public function test_admin_can_delete_an_exam_subject(): void
    {
        $exam = Exam::create([
            'title' => 'Midterm 2026',
            'classroom_id' => $this->classroom->id,
            'subject_id' => $this->math->id,
            'exam_date' => '2026-10-10',
            'total_marks' => 100.00,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.exams.destroy', $exam));

        $response->assertRedirect(route('admin.exams.index'));
        $this->assertDatabaseMissing('exams', [
            'id' => $exam->id,
        ]);
    }
}
