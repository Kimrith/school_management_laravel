<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\FeeInvoice;
use App\Models\Mark;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolManagementModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_classroom_and_relationships(): void
    {
        $classroom = Classroom::create([
            'name' => 'Grade 10-A',
            'grade_level' => 'Grade 10',
            'academic_year' => '2025-2026',
        ]);

        $this->assertDatabaseHas('classrooms', [
            'id' => $classroom->id,
            'name' => 'Grade 10-A',
        ]);

        $studentUser = User::create([
            'name' => 'Sokha Chan',
            'email' => 'sokha@example.com',
            'password' => bcrypt('password123'),
        ]);

        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'classroom_id' => $classroom->id,
            'student_code' => 'STU-1001',
            'date_of_birth' => '2008-04-12',
            'gender' => 'male',
            'parent_name' => 'Chan Dara',
            'parent_phone' => '012345678',
            'address' => 'Phnom Penh, Cambodia',
        ]);

        $this->assertEquals($classroom->id, $studentProfile->classroom->id);
        $this->assertTrue($classroom->studentProfiles->contains($studentProfile));
        $this->assertEquals($studentUser->id, $studentProfile->user->id);
        $this->assertEquals($studentProfile->id, $studentUser->studentProfile->id);

        $teacherUser = User::create([
            'name' => 'Prof. Virak',
            'email' => 'virak@example.com',
            'password' => bcrypt('password123'),
        ]);

        $teacherProfile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'phone' => '098765432',
            'qualification' => 'Master in Computer Science',
            'specialization' => 'Mathematics & Programming',
            'address' => 'Tuol Kork, Phnom Penh',
        ]);

        $this->assertEquals($teacherUser->id, $teacherProfile->user->id);
        $this->assertEquals($teacherProfile->id, $teacherUser->teacherProfile->id);

        $subject = Subject::create([
            'name' => 'Web Development',
            'code' => 'WEB401',
            'description' => 'Laravel & Fullstack Web Development',
        ]);

        $teacherSubject = TeacherSubject::create([
            'teacher_id' => $teacherUser->id,
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
        ]);

        $this->assertEquals($teacherUser->id, $teacherSubject->teacher->id);
        $this->assertEquals($subject->id, $teacherSubject->subject->id);
        $this->assertEquals($classroom->id, $teacherSubject->classroom->id);

        $this->assertTrue($teacherUser->taughtSubjects->contains($subject));
        $this->assertTrue($teacherUser->taughtClassrooms->contains($classroom));
        $this->assertTrue($subject->teachers->contains($teacherUser));
        $this->assertTrue($classroom->teachers->contains($teacherUser));
        $this->assertTrue($classroom->subjects->contains($subject));

        $attendance = Attendance::create([
            'student_id' => $studentProfile->id,
            'classroom_id' => $classroom->id,
            'date' => '2026-09-27',
            'status' => 'present',
            'remarks' => 'On time',
        ]);

        $this->assertEquals($studentProfile->id, $attendance->student->id);
        $this->assertEquals($classroom->id, $attendance->classroom->id);
        $this->assertTrue($studentProfile->attendances->contains($attendance));
        $this->assertTrue($classroom->attendances->contains($attendance));

        $exam = Exam::create([
            'title' => 'Midterm Examination',
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
            'exam_date' => '2026-10-15',
            'total_marks' => 100.00,
        ]);

        $this->assertEquals($subject->id, $exam->subject->id);
        $this->assertEquals($classroom->id, $exam->classroom->id);
        $this->assertTrue($subject->exams->contains($exam));
        $this->assertTrue($classroom->exams->contains($exam));

        $mark = Mark::create([
            'exam_id' => $exam->id,
            'student_id' => $studentProfile->id,
            'marks_obtained' => 96.50,
            'grade_letter' => 'A',
        ]);

        $this->assertEquals($exam->id, $mark->exam->id);
        $this->assertEquals($studentProfile->id, $mark->student->id);
        $this->assertTrue($exam->marks->contains($mark));
        $this->assertTrue($studentProfile->marks->contains($mark));

        $feeInvoice = FeeInvoice::create([
            'student_id' => $studentProfile->id,
            'title' => 'Semester 1 Tuition Fee',
            'amount' => 500.00,
            'due_date' => '2026-11-01',
            'status' => 'paid',
            'paid_date' => '2026-10-01',
        ]);

        $this->assertEquals($studentProfile->id, $feeInvoice->student->id);
        $this->assertTrue($studentProfile->feeInvoices->contains($feeInvoice));
    }
}
