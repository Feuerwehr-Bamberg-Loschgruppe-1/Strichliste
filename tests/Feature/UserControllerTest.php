<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_drink()
    {
        // Erstelle einen Benutzer mit einem Guthaben von 10.00
        $user = User::factory()->create(['balance' => 10.00]);

        // Erstelle ein Item mit einem Preis von 3.50
        $item = Item::factory()->create(['price' => 3.50]);

        // Rufe den Endpunkt zum Buchen eines Getränks auf
        $response = $this->post("/users/{$user->id}/book-drink/{$item->id}");

        // Überprüfe, ob der Statuscode 200 ist
        $response->assertStatus(200);

        // Aktualisiere das Benutzerobjekt
        $user->refresh();

        // Überprüfe, ob das Guthaben korrekt abgezogen wurde
        $this->assertEquals(6.50, $user->balance);
    }

    public function test_add_funds()
    {
        // Erstelle einen Benutzer mit einem Guthaben von 5.00
        $user = User::factory()->create(['balance' => 5.00]);

        // Rufe den Endpunkt zum Aufladen von Guthaben auf
        $response = $this->post("/users/{$user->id}/add-funds", ['amount' => 10.00]);

        // Überprüfe, ob der Statuscode 200 ist
        $response->assertStatus(200);

        // Aktualisiere das Benutzerobjekt
        $user->refresh();

        // Überprüfe, ob das Guthaben korrekt aufgeladen wurde
        $this->assertEquals(15.00, $user->balance);
    }
}
