<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacherUser;

    protected TeacherProfile $teacherProfile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacherUser = User::create([
            'name' => 'Prof. Sok Dara',
            'email' => 'sok.dara@school.edu',
            'password' => Hash::make('password123'),
            'role' => Role::Teacher,
        ]);

        $this->teacherProfile = TeacherProfile::create([
            'user_id' => $this->teacherUser->id,
            'phone' => '012 345 678',
            'qualification' => 'Master of Computer Science',
            'specialization' => 'Web Application Engineering',
            'address' => 'Phnom Penh, Cambodia',
        ]);
    }

    public function test_teacher_profile_view_renders_successfully(): void
    {
        $response = $this->actingAs($this->teacherUser)->get(route('teacher.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Prof. Sok Dara');
        $response->assertSee('Master of Computer Science');
        $response->assertSee('Faculty Bio &amp; Credentials', false);
        $response->assertSee('Digital Faculty ID Card');
    }

    public function test_teacher_can_update_profile_information(): void
    {
        $response = $this->actingAs($this->teacherUser)->put(route('teacher.profile.update'), [
            'name' => 'Prof. Sok Dara Updated',
            'phone' => '098 765 432',
            'qualification' => 'Ph.D. in Computer Science',
            'specialization' => 'Artificial Intelligence & Data Systems',
            'address' => 'Siem Reap, Cambodia',
        ]);

        $response->assertRedirect(route('teacher.profile.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->teacherUser->id,
            'name' => 'Prof. Sok Dara Updated',
        ]);

        $this->assertDatabaseHas('teacher_profiles', [
            'user_id' => $this->teacherUser->id,
            'phone' => '098 765 432',
            'qualification' => 'Ph.D. in Computer Science',
            'specialization' => 'Artificial Intelligence & Data Systems',
            'address' => 'Siem Reap, Cambodia',
        ]);
    }

    public function test_teacher_can_update_account_password(): void
    {
        $response = $this->actingAs($this->teacherUser)->put(route('teacher.profile.password'), [
            'current_password' => 'password123',
            'password' => 'newsecretpassword123',
            'password_confirmation' => 'newsecretpassword123',
        ]);

        $response->assertRedirect(route('teacher.profile.index'));
        $response->assertSessionHas('success');

        $this->teacherUser->refresh();
        $this->assertTrue(Hash::check('newsecretpassword123', $this->teacherUser->password));
    }

    public function test_teacher_cannot_update_password_with_incorrect_current_password(): void
    {
        $response = $this->actingAs($this->teacherUser)->put(route('teacher.profile.password'), [
            'current_password' => 'wrongpassword',
            'password' => 'newsecretpassword123',
            'password_confirmation' => 'newsecretpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->teacherUser->refresh();
        $this->assertTrue(Hash::check('password123', $this->teacherUser->password));
    }

    public function test_teacher_cannot_update_password_with_mismatched_confirmation(): void
    {
        $response = $this->actingAs($this->teacherUser)->put(route('teacher.profile.password'), [
            'current_password' => 'password123',
            'password' => 'newsecretpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
        $this->teacherUser->refresh();
        $this->assertTrue(Hash::check('password123', $this->teacherUser->password));
    }
}
