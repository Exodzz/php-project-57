<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;
use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsTasksView()
    {
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
    }

    public function testShowReturnsTaskView()
    {
        $task = Task::factory()->create();
        $response = $this->get(route('tasks.show', $task));
        $response->assertStatus(200);
        $response->assertViewIs('tasks.show');
    }

    public function testCreateReturnsCreateView()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('tasks.create'));
        $response->assertStatus(200);
        $response->assertViewIs('tasks.create');
    }

    public function testStoreCreatesNewTask()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'assigned_to_id' => $user->id,
        ];

        $response = $this->post(route('tasks.store'), $taskData);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'assigned_to_id' => $user->id,
            'created_by_id' => $user->id,
        ]);
    }

    public function testStoreCreatesTaskWithLabels()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        $label = Label::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'assigned_to_id' => $user->id,
            'labels' => [$label->id],
        ];

        $response = $this->post(route('tasks.store'), $taskData);

        $response->assertRedirect(route('tasks.index'));
        $task = Task::where('name', 'Test Task')->first();
        $this->assertTrue($task->labels->contains($label));
    }

    public function testEditReturnsEditView()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create(['created_by_id' => $user->id]);

        $response = $this->get(route('tasks.edit', $task));
        $response->assertStatus(200);
        $response->assertViewIs('tasks.edit');
    }

    public function testUpdateModifiesTask()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id]);
        $this->actingAs($user);

        $newData = [
            'name' => 'Updated Task',
            'description' => 'Updated Description',
            'status_id' => $status->id,
            'assigned_to_id' => $user->id,
        ];

        $response = $this->put(route('tasks.update', $task), $newData);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task',
            'description' => 'Updated Description',
        ]);
    }

    public function testUpdateModifiesTaskLabels()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id]);
        $label1 = Label::factory()->create();
        $label2 = Label::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'name' => 'Updated Task',
            'description' => 'Updated Description',
            'status_id' => $task->status_id,
            'assigned_to_id' => $user->id,
            'labels' => [$label1->id, $label2->id],
        ];

        $response = $this->put(route('tasks.update', $task), $taskData);

        $response->assertRedirect(route('tasks.index'));
        $task->refresh();
        $this->assertTrue($task->labels->contains($label1));
        $this->assertTrue($task->labels->contains($label2));
    }

    public function testDestroyDeletesTask()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testDestroyDetachesLabels()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id]);
        $label = Label::factory()->create();
        $task->labels()->attach($label);
        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('task_label', [
            'task_id' => $task->id,
            'label_id' => $label->id,
        ]);
    }

    public function testUnauthorizedUserCannotDeleteTask()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $otherUser->id]);
        $this->actingAs($user);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function testUnauthenticatedUserCannotAccessCreate()
    {
        $response = $this->get(route('tasks.create'));
        $response->assertRedirect('/login');
    }

    public function testUnauthenticatedUserCannotAccessStore()
    {
        $response = $this->post(route('tasks.store'), ['name' => 'Test']);
        $response->assertRedirect('/login');
    }

    public function testUnauthenticatedUserCannotAccessEdit()
    {
        $task = Task::factory()->create();
        $response = $this->get(route('tasks.edit', $task));
        $response->assertRedirect('/login');
    }

    public function testUnauthenticatedUserCannotAccessUpdate()
    {
        $task = Task::factory()->create();
        $response = $this->put(route('tasks.update', $task), ['name' => 'Test']);
        $response->assertRedirect('/login');
    }

    public function testUnauthenticatedUserCannotAccessDestroy()
    {
        $task = Task::factory()->create();
        $response = $this->delete(route('tasks.destroy', $task));
        $response->assertRedirect('/login');
    }

    public function testIndexWithFilters()
    {
        $status = TaskStatus::factory()->create();
        $user = User::factory()->create();

        $response = $this->get(route('tasks.index', [
            'filter' => [
                'status_id' => $status->id,
                'created_by_id' => $user->id,
                'assigned_to_id' => $user->id,
            ]
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
    }
}
