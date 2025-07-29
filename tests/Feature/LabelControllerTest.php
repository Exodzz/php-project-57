<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsLabelsView()
    {
        $response = $this->get(route('labels.index'));
        $response->assertStatus(200);
        $response->assertViewIs('labels.index');
    }

    public function testCreateReturnsCreateView()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('labels.create'));
        $response->assertStatus(200);
        $response->assertViewIs('labels.create');
    }

    public function testStoreCreatesNewLabel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $labelData = ['name' => 'Test Label'];

        $response = $this->post(route('labels.store'), $labelData);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', $labelData);
    }

    public function testEditReturnsEditView()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $label = Label::factory()->create();

        $response = $this->get(route('labels.edit', $label));
        $response->assertStatus(200);
        $response->assertViewIs('labels.edit');
    }

    public function testUpdateModifiesLabel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $label = Label::factory()->create();
        $newData = ['name' => 'Updated Label'];

        $response = $this->put(route('labels.update', $label), $newData);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', $newData);
    }

    public function testDestroyDeletesLabel()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $label = Label::factory()->create();

        $response = $this->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    public function testDestroyPreventsDeletionWhenLabelHasTasks()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $label = Label::factory()->create();
        $task = \App\Models\Task::factory()->create();
        $task->labels()->attach($label);

        $response = $this->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['id' => $label->id]);
    }

    public function testUnauthenticatedUserCannotAccessCreate()
    {
        $response = $this->get(route('labels.create'));
        $response->assertRedirect('/login');
    }

    public function testUnauthenticatedUserCannotAccessStore()
    {
        $response = $this->post(route('labels.store'), ['name' => 'Test']);
        $response->assertRedirect('/login');
    }

    public function testUnauthenticatedUserCannotAccessEdit()
    {
        $label = Label::factory()->create();
        $response = $this->get(route('labels.edit', $label));
        $response->assertRedirect('/login');
    }

    public function testUnauthenticatedUserCannotAccessUpdate()
    {
        $label = Label::factory()->create();
        $response = $this->put(route('labels.update', $label), ['name' => 'Test']);
        $response->assertRedirect('/login');
    }

    public function testUnauthenticatedUserCannotAccessDestroy()
    {
        $label = Label::factory()->create();
        $response = $this->delete(route('labels.destroy', $label));
        $response->assertRedirect('/login');
    }
}
