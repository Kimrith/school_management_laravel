<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LevelManagementTest extends TestCase
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
        ]);
    }

    public function test_admin_can_view_levels_index_with_default_seeding(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.levels.index'));

        $response->assertStatus(200);
        $response->assertSee('Academic Levels & Grades');
        $response->assertSee('Grade 10');
        $response->assertSee('Grade 11');
        $response->assertSee('Grade 12');
    }

    public function test_admin_can_create_level(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.levels.store'), [
            'name' => 'Kindergarten',
            'status' => 'Active',
        ]);

        $response->assertRedirect(route('admin.levels.index'));

        $this->assertDatabaseHas('levels', [
            'name' => 'Kindergarten',
            'status' => 'Active',
        ]);
    }

    public function test_admin_can_view_edit_level_page(): void
    {
        $level = Level::create([
            'name' => 'Grade 6',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.levels.edit', $level));

        $response->assertStatus(200);
        $response->assertSee('Edit Academic Level');
        $response->assertSee('Grade 6');
    }

    public function test_admin_can_update_level(): void
    {
        $level = Level::create([
            'name' => 'Grade 6',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.levels.update', $level), [
            'name' => 'Grade 6 (Primary)',
            'status' => 'Active',
        ]);

        $response->assertRedirect(route('admin.levels.index'));

        $level->refresh();
        $this->assertEquals('Grade 6 (Primary)', $level->name);
    }

    public function test_admin_can_toggle_level_status(): void
    {
        $level = Level::create([
            'name' => 'Grade 13',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.levels.toggle-status', $level));
        $response->assertRedirect();

        $level->refresh();
        $this->assertEquals('Suspended', $level->status);

        $response2 = $this->actingAs($this->admin)->patch(route('admin.levels.toggle-status', $level));
        $response2->assertRedirect();

        $level->refresh();
        $this->assertEquals('Active', $level->status);
    }

    public function test_admin_can_view_suspended_levels(): void
    {
        $level = Level::create([
            'name' => 'Old Cohort Level',
            'status' => 'Suspended',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.levels.suspended'));

        $response->assertStatus(200);
        $response->assertSee('Suspended Academic Levels');
        $response->assertSee('Old Cohort Level');
        $response->assertSee('Restore Active');
    }

    public function test_admin_can_delete_level(): void
    {
        $level = Level::create([
            'name' => 'Temporary Tier',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.levels.destroy', $level));

        $response->assertRedirect(route('admin.levels.index'));
        $this->assertDatabaseMissing('levels', ['id' => $level->id]);
    }
}
