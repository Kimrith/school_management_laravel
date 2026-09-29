<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Level;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectLevelClassRelationshipTest extends TestCase
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

    public function test_subject_belongs_to_level_and_has_many_classrooms(): void
    {
        $level = Level::create([
            'name' => 'Grade 10',
            'status' => 'Active',
        ]);

        $classA = Classroom::create([
            'name' => 'Grade 10-A',
            'grade_level' => 'Grade 10',
            'level_id' => $level->id,
            'academic_year' => '2025-2026',
        ]);

        $classB = Classroom::create([
            'name' => 'Grade 10-B',
            'grade_level' => 'Grade 10',
            'level_id' => $level->id,
            'academic_year' => '2025-2026',
        ]);

        $subject = Subject::create([
            'name' => 'Geometry & Trigonometry',
            'code' => 'MATH101',
            'level_id' => $level->id,
            'description' => 'Grade 10 core mathematics curriculum',
        ]);

        TeacherSubject::create([
            'teacher_id' => null,
            'subject_id' => $subject->id,
            'classroom_id' => $classA->id,
        ]);
        TeacherSubject::create([
            'teacher_id' => null,
            'subject_id' => $subject->id,
            'classroom_id' => $classB->id,
        ]);

        // Verify Subject -> Level relationship
        $this->assertNotNull($subject->level);
        $this->assertEquals('Grade 10', $subject->level->name);

        // Verify Level -> Subjects relationship
        $this->assertTrue($level->subjects->contains($subject));

        // Verify Subject -> Classrooms relationship (many classes)
        $this->assertCount(2, $subject->classrooms);
        $this->assertTrue($subject->classrooms->contains($classA));
        $this->assertTrue($subject->classrooms->contains($classB));

        // Verify Classroom -> Level relationship
        $this->assertEquals($level->id, $classA->level->id);

        // Verify Classroom -> Subjects relationship
        $this->assertTrue($classA->subjects->contains($subject));
    }

    public function test_admin_can_create_subject_with_level_and_multiple_classes(): void
    {
        $level = Level::create([
            'name' => 'Grade 11',
            'status' => 'Active',
        ]);

        $class1 = Classroom::create([
            'name' => 'Grade 11-A',
            'grade_level' => 'Grade 11',
            'level_id' => $level->id,
            'academic_year' => '2025-2026',
        ]);
        $class2 = Classroom::create([
            'name' => 'Grade 11-B',
            'grade_level' => 'Grade 11',
            'level_id' => $level->id,
            'academic_year' => '2025-2026',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.subjects.store'), [
            'name' => 'Advanced Biology',
            'code' => 'BIO301',
            'level_id' => $level->id,
            'description' => 'Genetics and cell biology',
            'classrooms' => [$class1->id, $class2->id],
        ]);

        $response->assertRedirect(route('admin.subjects.index'));

        $subject = Subject::where('code', 'BIO301')->first();
        $this->assertNotNull($subject);
        $this->assertEquals($level->id, $subject->level_id);
        $this->assertEquals('Grade 11', $subject->level->name);
        $this->assertCount(2, $subject->classrooms);
    }

    public function test_subjects_index_renders_level_and_classes(): void
    {
        $level = Level::create([
            'name' => 'Grade 12',
            'status' => 'Active',
        ]);

        $class = Classroom::create([
            'name' => 'Grade 12-A',
            'grade_level' => 'Grade 12',
            'level_id' => $level->id,
            'academic_year' => '2025-2026',
        ]);

        $subject = Subject::create([
            'name' => 'Organic Chemistry',
            'code' => 'CHEM401',
            'level_id' => $level->id,
        ]);

        TeacherSubject::create([
            'teacher_id' => null,
            'subject_id' => $subject->id,
            'classroom_id' => $class->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.subjects.index'));

        $response->assertStatus(200);
        $response->assertSee('Organic Chemistry');
        $response->assertSee('CHEM401');
        $response->assertSee('Grade 12');
        $response->assertSee('Grade 12-A');
    }
}
