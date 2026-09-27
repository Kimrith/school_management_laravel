<?php

namespace Database\Seeders;

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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@school.edu'],
            [
                'name' => 'School Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Classrooms
        $class10A = Classroom::firstOrCreate(
            ['name' => 'Grade 10-A'],
            ['grade_level' => 'Grade 10', 'academic_year' => '2025-2026']
        );

        $class11B = Classroom::firstOrCreate(
            ['name' => 'Grade 11-B'],
            ['grade_level' => 'Grade 11', 'academic_year' => '2025-2026']
        );

        $class12A = Classroom::firstOrCreate(
            ['name' => 'Grade 12-A'],
            ['grade_level' => 'Grade 12', 'academic_year' => '2025-2026']
        );

        $class9C = Classroom::firstOrCreate(
            ['name' => 'Grade 9-C'],
            ['grade_level' => 'Grade 9', 'academic_year' => '2025-2026']
        );

        // 3. Create Core Subjects
        $webDev = Subject::firstOrCreate(
            ['code' => 'WEB401'],
            ['name' => 'Web Application Development', 'description' => 'Fullstack web development with Laravel, Blade, and modern styling.']
        );

        $dbs = Subject::firstOrCreate(
            ['code' => 'DBS301'],
            ['name' => 'Relational Database Management', 'description' => 'Relational database architecture, queries, indexing, and normal forms.']
        );

        $math = Subject::firstOrCreate(
            ['code' => 'MATH101'],
            ['name' => 'Discrete Mathematics & Logic', 'description' => 'Propositional logic, set theory, induction, and graph algorithms.']
        );

        $eng = Subject::firstOrCreate(
            ['code' => 'ENG201'],
            ['name' => 'Technical English Communications', 'description' => 'Professional presentations and engineering report documentation.']
        );

        // 4. Create Teachers
        $teacherUser1 = User::firstOrCreate(
            ['email' => 'virak.meas@school.edu'],
            ['name' => 'Prof. Virak Meas', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        TeacherProfile::firstOrCreate(
            ['user_id' => $teacherUser1->id],
            [
                'phone' => '012 998 123',
                'qualification' => 'Master of Computer Science',
                'specialization' => 'Web Engineering & Systems',
                'address' => 'Tuol Kork, Phnom Penh',
            ]
        );

        $teacherUser2 = User::firstOrCreate(
            ['email' => 'sopheap.ouk@school.edu'],
            ['name' => 'Dr. Sopheap Ouk', 'password' => Hash::make('password123'), 'email_verified_at' => now()]
        );
        TeacherProfile::firstOrCreate(
            ['user_id' => $teacherUser2->id],
            [
                'phone' => '011 445 789',
                'qualification' => 'Ph.D. in Pure Mathematics',
                'specialization' => 'Discrete Mathematics',
                'address' => 'Daun Penh, Phnom Penh',
            ]
        );

        // 5. Assign Teacher Subjects & Classrooms
        TeacherSubject::firstOrCreate([
            'teacher_id' => $teacherUser1->id,
            'subject_id' => $webDev->id,
            'classroom_id' => $class10A->id,
        ]);

        TeacherSubject::firstOrCreate([
            'teacher_id' => $teacherUser2->id,
            'subject_id' => $math->id,
            'classroom_id' => $class10A->id,
        ]);

        // 6. Create Students
        $studentsData = [
            ['name' => 'Sokha Chan', 'email' => 'sokha@example.com', 'code' => 'STU-1001', 'class' => $class10A->id, 'gender' => 'male', 'dob' => '2008-04-12', 'parent' => 'Chan Dara', 'phone' => '012 345 678'],
            ['name' => 'Bopha Vong', 'email' => 'bopha@example.com', 'code' => 'STU-1002', 'class' => $class10A->id, 'gender' => 'female', 'dob' => '2008-07-21', 'parent' => 'Vong Meas', 'phone' => '015 889 221'],
            ['name' => 'Dara Rath', 'email' => 'dara@example.com', 'code' => 'STU-1003', 'class' => $class11B->id, 'gender' => 'male', 'dob' => '2007-02-18', 'parent' => 'Rath Sitha', 'phone' => '098 712 334'],
            ['name' => 'Chanthou Seng', 'email' => 'chanthou@example.com', 'code' => 'STU-1004', 'class' => $class12A->id, 'gender' => 'female', 'dob' => '2006-11-05', 'parent' => 'Seng Kosal', 'phone' => '077 445 109'],
            ['name' => 'Panha Lim', 'email' => 'panha@example.com', 'code' => 'STU-1005', 'class' => $class9C->id, 'gender' => 'male', 'dob' => '2009-09-30', 'parent' => 'Lim Vichea', 'phone' => '089 990 123'],
        ];

        foreach ($studentsData as $st) {
            $user = User::firstOrCreate(
                ['email' => $st['email']],
                ['name' => $st['name'], 'password' => Hash::make('password123'), 'email_verified_at' => now()]
            );

            $profile = StudentProfile::firstOrCreate(
                ['student_code' => $st['code']],
                [
                    'user_id' => $user->id,
                    'classroom_id' => $st['class'],
                    'date_of_birth' => $st['dob'],
                    'gender' => $st['gender'],
                    'parent_name' => $st['parent'],
                    'parent_phone' => $st['phone'],
                    'address' => 'Phnom Penh, Cambodia',
                ]
            );

            // Attendance
            Attendance::firstOrCreate([
                'student_id' => $profile->id,
                'classroom_id' => $st['class'],
                'date' => now()->toDateString(),
            ], [
                'status' => 'present',
                'remarks' => 'On time',
            ]);

            // Fee Invoice
            FeeInvoice::firstOrCreate([
                'student_id' => $profile->id,
                'title' => 'Term 1 Tuition Fee',
            ], [
                'amount' => 350.00,
                'due_date' => now()->addDays(14)->toDateString(),
                'status' => 'paid',
                'paid_date' => now()->subDays(2)->toDateString(),
            ]);
        }

        // 7. Exam & Marks
        $midterm = Exam::firstOrCreate([
            'title' => 'Web Development Midterm Examination',
            'subject_id' => $webDev->id,
            'classroom_id' => $class10A->id,
        ], [
            'exam_date' => now()->addDays(10)->toDateString(),
            'total_marks' => 100.00,
        ]);

        $firstStudent = StudentProfile::where('student_code', 'STU-1001')->first();
        if ($firstStudent) {
            Mark::firstOrCreate([
                'exam_id' => $midterm->id,
                'student_id' => $firstStudent->id,
            ], [
                'marks_obtained' => 96.50,
                'grade_letter' => 'A',
            ]);
        }
    }
}
