<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_can_be_rendered(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Academic Overview');
        $response->assertSee('Total Students');
        $response->assertSee('Total Teachers');
        $response->assertSee('Active Classes');
        $response->assertSee("Today's Attendance", false);
        $response->assertSee('Recent Student Admissions');
    }

    public function test_teacher_cannot_access_admin_dashboard(): void
    {
        $teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Teacher,
        ]);

        $response = $this->actingAs($teacher)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Student,
        ]);

        $response = $this->actingAs($student)->get('/admin/dashboard');

        $response->assertStatus(403);
    }
}
