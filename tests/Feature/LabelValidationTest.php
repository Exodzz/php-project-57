<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_label_description_cannot_exceed_255_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/labels', [
            'name' => 'Test Label',
            'description' => str_repeat('a', 256), // 256 символов
        ]);

        $response->assertSessionHasErrors(['description']);
    }

    public function test_label_description_can_be_255_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/labels', [
            'name' => 'Test Label',
            'description' => str_repeat('a', 255), // 255 символов
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_label_description_can_be_null(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/labels', [
            'name' => 'Test Label',
            'description' => null,
        ]);

        $response->assertSessionHasNoErrors();
    }
} 