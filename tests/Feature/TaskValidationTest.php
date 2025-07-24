<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskValidationTest extends TestCase
{
    use RefreshDatabase;

    public function testTaskDescriptionCanLong(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'name' => 'Test Task',
            'description' => str_repeat('a', 1000), // 1000 символов
            'status_id' => $status->id,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function testTaskDescriptionCanNull(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'name' => 'Test Task',
            'description' => null,
            'status_id' => $status->id,
        ]);

        $response->assertSessionHasNoErrors();
    }
}
