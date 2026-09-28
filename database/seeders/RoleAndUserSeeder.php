<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Subject;
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

        // 5. Assign Teacher Subjects & Classrooms
        if (isset($teacherUser1) && isset($teacherUser2)) {
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
        }
    }
}
