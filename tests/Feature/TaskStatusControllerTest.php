<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCanViewStatusesIndex()
    {
        $response = $this->get('/task_statuses');
        $response->assertStatus(200);
    }

    public function testGuestCannotCreateStatus()
    {
        $response = $this->get('/task_statuses/create');
        $response->assertRedirect('/login');
    }

    public function testGuestCannotStoreStatus()
    {
        $response = $this->post('/task_statuses', ['name' => 'Test Status']);
        $response->assertRedirect('/login');
    }

    public function testGuestCannotEditStatus()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->get("/task_statuses/{$status->id}/edit");
        $response->assertRedirect('/login');
    }

    public function testGuestCannotUpdateStatus()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->patch("/task_statuses/{$status->id}", ['name' => 'Updated Status']);
        $response->assertRedirect('/login');
    }

    public function testGuestCannotDeleteStatus()
    {
        $status = TaskStatus::factory()->create();
        $response = $this->delete("/task_statuses/{$status->id}");
        $response->assertRedirect('/login');
    }

    public function testAuthenticatedUserCanViewStatusesIndex()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/task_statuses');
        $response->assertStatus(200);
    }

    public function testAuthenticatedUserCanCreateStatus()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/task_statuses/create');
        $response->assertStatus(200);
    }

    public function testAuthenticatedUserCanStoreStatus()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post('/task_statuses', ['name' => 'Test Status']);

        $response->assertRedirect('/task_statuses');
        $this->assertDatabaseHas('task_statuses', ['name' => 'Test Status']);
    }

    public function testAuthenticatedUserCanEditStatus()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();

        $response = $this->actingAs($user)->get("/task_statuses/{$status->id}/edit");
        $response->assertStatus(200);
    }

    public function testAuthenticatedUserCanUpdateStatus()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();

        $response = $this->actingAs($user)
            ->patch("/task_statuses/{$status->id}", ['name' => 'Updated Status']);

        $response->assertRedirect('/task_statuses');
        $this->assertDatabaseHas('task_statuses', ['name' => 'Updated Status']);
    }

    public function testAuthenticatedUserCanDeleteStatus()
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();

        $response = $this->actingAs($user)->delete("/task_statuses/{$status->id}");

        $response->assertRedirect('/task_statuses');
        $this->assertDatabaseMissing('task_statuses', ['id' => $status->id]);
    }

    public function testStatusNameIsRequired()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post('/task_statuses', ['name' => '']);

        $response->assertSessionHasErrors(['name']);
    }

    public function testStatusNameMustBeUnique()
    {
        $user = User::factory()->create();
        TaskStatus::factory()->create(['name' => 'Test Status']);

        $response = $this->actingAs($user)
            ->post('/task_statuses', ['name' => 'Test Status']);

        $response->assertSessionHasErrors(['name']);
    }

    public function testStatusNameCannotExceed255Characters()
    {
        $user = User::factory()->create();
        $longName = str_repeat('a', 256);

        $response = $this->actingAs($user)
            ->post('/task_statuses', ['name' => $longName]);

        $response->assertSessionHasErrors(['name']);
    }
}
