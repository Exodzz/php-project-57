<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_description_can_be_long(): void
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

    public function test_task_description_can_be_null(): void
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