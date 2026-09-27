<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_can_be_rendered(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Academic Overview');
        $response->assertSee('Total Students');
        $response->assertSee('Total Teachers');
        $response->assertSee('Active Classes');
        $response->assertSee("Today's Attendance", false);
        $response->assertSee('Recent Student Admissions');
    }
}
