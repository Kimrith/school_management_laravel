<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Admin,
        ]);
    }

    public function test_admin_can_create_student_with_avatar(): void
    {
        $avatarFile = UploadedFile::fake()->image('student_avatar.jpg', 200, 200);

        $response = $this->actingAs($this->admin)->post(route('admin.students.store'), [
            'name' => 'Sophea Pich',
            'email' => 'sophea.pich@school.edu',
            'student_code' => 'STU-9901',
            'gender' => 'female',
            'parent_name' => 'Kosal Pich',
            'parent_phone' => '012 333 444',
            'avatar' => $avatarFile,
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        $student = StudentProfile::where('student_code', 'STU-9901')->first();
        $this->assertNotNull($student);
        $this->assertNotNull($student->avatar);
        $this->assertStringStartsWith('avatars/students/', $student->avatar);
        Storage::disk('public')->assertExists($student->avatar);
    }

    public function test_admin_can_update_student_avatar_and_old_avatar_is_deleted(): void
    {
        $initialAvatar = UploadedFile::fake()->image('initial_student.jpg', 200, 200);

        $this->actingAs($this->admin)->post(route('admin.students.store'), [
            'name' => 'Sokha Meng',
            'email' => 'sokha.meng@school.edu',
            'student_code' => 'STU-9902',
            'gender' => 'male',
            'avatar' => $initialAvatar,
        ]);

        $student = StudentProfile::where('student_code', 'STU-9902')->first();
        $oldPath = $student->avatar;
        Storage::disk('public')->assertExists($oldPath);

        $newAvatar = UploadedFile::fake()->image('updated_student.png', 250, 250);

        $response = $this->actingAs($this->admin)->put(route('admin.students.update', $student->id), [
            'name' => 'Sokha Meng Updated',
            'email' => 'sokha.meng@school.edu',
            'student_code' => 'STU-9902',
            'gender' => 'male',
            'avatar' => $newAvatar,
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $student->refresh();

        $this->assertNotEquals($oldPath, $student->avatar);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($student->avatar);
    }

    public function test_admin_can_create_teacher_with_avatar(): void
    {
        $avatarFile = UploadedFile::fake()->image('teacher_avatar.jpg', 300, 300);

        $response = $this->actingAs($this->admin)->post(route('admin.teachers.store'), [
            'name' => 'Prof. Serey Vuth',
            'email' => 'serey.vuth@school.edu',
            'phone' => '012 888 999',
            'qualification' => 'Ph.D. in Computer Science',
            'avatar' => $avatarFile,
        ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $user = User::where('email', 'serey.vuth@school.edu')->first();
        $this->assertNotNull($user);
        $teacher = $user->teacherProfile;
        $this->assertNotNull($teacher);
        $this->assertNotNull($teacher->avatar);
        $this->assertStringStartsWith('avatars/teachers/', $teacher->avatar);
        Storage::disk('public')->assertExists($teacher->avatar);
    }

    public function test_admin_can_update_teacher_avatar_and_old_avatar_is_deleted(): void
    {
        $initialAvatar = UploadedFile::fake()->image('initial_teacher.jpg', 300, 300);

        $this->actingAs($this->admin)->post(route('admin.teachers.store'), [
            'name' => 'Dr. Rithy Lim',
            'email' => 'rithy.lim@school.edu',
            'phone' => '015 111 222',
            'qualification' => 'Master of Education',
            'avatar' => $initialAvatar,
        ]);

        $user = User::where('email', 'rithy.lim@school.edu')->first();
        $teacher = $user->teacherProfile;
        $oldPath = $teacher->avatar;
        Storage::disk('public')->assertExists($oldPath);

        $newAvatar = UploadedFile::fake()->image('new_teacher.webp', 300, 300);

        $response = $this->actingAs($this->admin)->put(route('admin.teachers.update', $teacher->id), [
            'name' => 'Dr. Rithy Lim Updated',
            'email' => 'rithy.lim@school.edu',
            'phone' => '015 111 222',
            'qualification' => 'Master of Education',
            'avatar' => $newAvatar,
        ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $teacher->refresh();

        $this->assertNotEquals($oldPath, $teacher->avatar);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($teacher->avatar);
    }

    public function test_teacher_can_update_own_avatar(): void
    {
        $teacherUser = User::create([
            'name' => 'Prof. Sopheak',
            'email' => 'sopheak@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Teacher,
        ]);

        $teacherProfile = TeacherProfile::create([
            'user_id' => $teacherUser->id,
            'phone' => '012 345 678',
        ]);

        $avatarFile = UploadedFile::fake()->image('faculty_photo.jpg', 200, 200);

        $response = $this->actingAs($teacherUser)->put(route('teacher.profile.update'), [
            'name' => 'Prof. Sopheak',
            'phone' => '012 345 678',
            'avatar' => $avatarFile,
        ]);

        $response->assertRedirect(route('teacher.profile.index'));
        $teacherProfile->refresh();

        $this->assertNotNull($teacherProfile->avatar);
        Storage::disk('public')->assertExists($teacherProfile->avatar);
    }

    public function test_avatar_url_accessors(): void
    {
        $studentUser = User::create([
            'name' => 'Student Demo',
            'email' => 'student.demo@school.edu',
            'password' => bcrypt('password123'),
            'role' => Role::Student,
        ]);

        $student = StudentProfile::create([
            'user_id' => $studentUser->id,
            'student_code' => 'STU-9999',
            'gender' => 'other',
            'avatar' => 'avatars/students/demo.jpg',
        ]);

        $this->assertStringContainsString('storage/avatars/students/demo.jpg', $student->avatar_url);
        $this->assertStringContainsString('storage/avatars/students/demo.jpg', $studentUser->avatar_url);
        $this->assertEquals('avatars/students/demo.jpg', $studentUser->avatar);
    }
}
