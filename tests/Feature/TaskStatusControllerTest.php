<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_statuses_index()
    {
        $response = $this->get('/task_statuses');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_create_status()
    {
        $response = $this->get('/task_statuses/create');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_store_status()
    {
        $response = $this->post('/task_statuses', ['name' => 'Test Status']);
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_edit_status()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->get("/task_statuses/{$status->id}/edit");
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_update_status()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->patch("/task_statuses/{$status->id}", ['name' => 'Updated Status']);
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_delete_status()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->delete("/task_statuses/{$status->id}");
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_statuses_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/task_statuses');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_status()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/task_statuses/create');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_store_status()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post('/task_statuses', ['name' => 'Test Status']);
        
        $response->assertRedirect('/task_statuses');
        $this->assertDatabaseHas('task_statuses', ['name' => 'Test Status']);
    }

    public function test_authenticated_user_can_edit_status()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        
        $response = $this->actingAs($user)->get("/task_statuses/{$status->id}/edit");
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_update_status()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        
        $response = $this->actingAs($user)
            ->patch("/task_statuses/{$status->id}", ['name' => 'Updated Status']);
        
        $response->assertRedirect('/task_statuses');
        $this->assertDatabaseHas('task_statuses', ['name' => 'Updated Status']);
    }

    public function test_authenticated_user_can_delete_status()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        
        $response = $this->actingAs($user)->delete("/task_statuses/{$status->id}");
        
        $response->assertRedirect('/task_statuses');
        $this->assertDatabaseMissing('task_statuses', ['id' => $status->id]);
    }

    public function test_status_name_is_required()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post('/task_statuses', ['name' => '']);
        
        $response->assertSessionHasErrors(['name']);
    }

    public function test_status_name_must_be_unique()
    {
        $user = User::factory()->create();
        TaskStatus::factory()->create(['name' => 'Test Status']);
        
        $response = $this->actingAs($user)
            ->post('/task_statuses', ['name' => 'Test Status']);
        
        $response->assertSessionHasErrors(['name']);
    }

    public function test_status_name_cannot_exceed_255_characters()
    {
        $user = User::factory()->create();
        $longName = str_repeat('a', 256);
        
        $response = $this->actingAs($user)
            ->post('/task_statuses', ['name' => $longName]);
        
        $response->assertSessionHasErrors(['name']);
    }
}
