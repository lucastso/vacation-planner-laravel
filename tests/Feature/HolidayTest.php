<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Holiday;

class HolidayTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_create_holiday(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson("/api/v1/holiday", [
            "title" => "Testing Holiday Title",
            "description" => "Testing Holiday Desc",
            "date" => "2024-03-20",
            "location" => "Testing Location",
            "participants" => "1,2,3",
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas("holidays", ["title" => "Testing Holiday Title"]);
    }

    public function test_read_specific_holiday(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $holiday = Holiday::factory()->create();
        $response = $this->getJson("/api/v1/holiday/" . $holiday->id);

        $response->assertStatus(200);
    }

    public function test_update_specific_holiday(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $holiday = Holiday::factory()->create();
        $response = $this->putJson("/api/v1/holiday/" . $holiday->id, [
            "title" => "New Holiday Title",
            "description" => "New Holiday Desc",
            "date" => "2020-02-02",
            "location" => "New Holiday Location",
            "participants" => "20",
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_specific_holiday()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $holiday = Holiday::factory()->create();
        $response = $this->deleteJson("/api/v1/holiday/" . $holiday->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('holidays', [
            'id' => $holiday->id,
        ]);
    }
}
