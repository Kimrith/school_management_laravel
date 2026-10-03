<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassroomManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Admin,
        ]);
    }

    public function test_admin_can_view_classrooms_index(): void
    {
        $classroom = Classroom::create([
            'name' => 'Grade 10-A',
            'grade_level' => 'Grade 10',
            'academic_year' => '2025-2026',
            'room' => 'Room 301',
            'capacity' => 40,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.classes.index'));

        $response->assertStatus(200);
        $response->assertSee('Classrooms & Grades');
        $response->assertSee('Grade 10-A');
        $response->assertSee('Room 301');
    }

    public function test_admin_can_create_classroom(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.classes.store'), [
            'name' => 'Grade 11-A',
            'grade_level' => 'Grade 11',
            'academic_year' => '2025-2026',
            'room' => 'Room 205',
            'capacity' => 38,
            'description' => 'Science Track Section',
        ]);

        $response->assertRedirect(route('admin.classes.index'));

        $this->assertDatabaseHas('classrooms', [
            'name' => 'Grade 11-A',
            'grade_level' => 'Grade 11',
            'room' => 'Room 205',
            'capacity' => 38,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_view_edit_classroom_page(): void
    {
        $classroom = Classroom::create([
            'name' => 'Grade 9-B',
            'grade_level' => 'Grade 9',
            'academic_year' => '2025-2026',
            'room' => 'Room 102',
            'capacity' => 35,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.classes.edit', $classroom));

        $response->assertStatus(200);
        $response->assertSee('Edit Classroom Details');
        $response->assertSee('Grade 9-B');
        $response->assertSee('Room 102');
    }

    public function test_admin_can_update_classroom(): void
    {
        $classroom = Classroom::create([
            'name' => 'Grade 9-B',
            'grade_level' => 'Grade 9',
            'academic_year' => '2025-2026',
            'room' => 'Room 102',
            'capacity' => 35,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.classes.update', $classroom), [
            'name' => 'Grade 9-B (Advanced)',
            'grade_level' => 'Grade 9',
            'academic_year' => '2025-2026',
            'room' => 'Room 104',
            'capacity' => 40,
            'status' => 'active',
            'description' => 'Upgraded laboratory section',
        ]);

        $response->assertRedirect(route('admin.classes.index'));

        $classroom->refresh();
        $this->assertEquals('Grade 9-B (Advanced)', $classroom->name);
        $this->assertEquals('Room 104', $classroom->room);
        $this->assertEquals(40, $classroom->capacity);
    }

    public function test_admin_can_toggle_classroom_status(): void
    {
        $classroom = Classroom::create([
            'name' => 'Grade 12-X',
            'grade_level' => 'Grade 12',
            'academic_year' => '2024-2025',
            'status' => 'active',
        ]);

        // Toggle to suspended
        $response = $this->actingAs($this->admin)->patch(route('admin.classes.toggle-status', $classroom));
        $response->assertRedirect();

        $classroom->refresh();
        $this->assertEquals('suspended', $classroom->status);

        // Toggle back to active
        $response2 = $this->actingAs($this->admin)->patch(route('admin.classes.toggle-status', $classroom));
        $response2->assertRedirect();

        $classroom->refresh();
        $this->assertEquals('active', $classroom->status);
    }

    public function test_admin_can_view_suspended_classrooms(): void
    {
        $classroom = Classroom::create([
            'name' => 'Grade 12-Old',
            'grade_level' => 'Grade 12',
            'academic_year' => '2023-2024',
            'status' => 'suspended',
            'room' => 'Room 001',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.classes.suspended'));

        $response->assertStatus(200);
        $response->assertSee('Archived & Suspended Classrooms');
        $response->assertSee('Grade 12-Old');
        $response->assertSee('Restore Active');
    }

    public function test_admin_can_delete_classroom(): void
    {
        $classroom = Classroom::create([
            'name' => 'Temporary Section',
            'grade_level' => 'Grade 7',
            'academic_year' => '2025-2026',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.classes.destroy', $classroom));

        $response->assertRedirect(route('admin.classes.index'));
        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
    }
}
