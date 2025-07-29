<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function testTaskCanBeDeletedWithDeleteMethod()
    {
        $user = User::factory()->create();
        
        $task = Task::factory()->create([
            'created_by_id' => $user->id,
        ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);

        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testTaskCannotBeDeletedWithGetMethod()
    {
        $user = User::factory()->create();
        
        $task = Task::factory()->create([
            'created_by_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('tasks.destroy', $task));

        $response->assertStatus(404);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function testUnauthorizedUserCannotDeleteTask()
    {
        $user = User::factory()->create();
        
        $task = Task::factory()->create([
            'created_by_id' => User::factory()->create()->id,
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
