<?php

use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('search route returns a successfull response', function () {
    $response = $this->get('/search');

    $response->assertStatus(200);
});

test('searching using nid works', function () {
    $vaccineCenter = VaccineCenter::factory()->create();
    
    $response = $this->post('/search', [
        'nid' => '1234567890123',
    ]);

    $response->assertSee('Not Registered');

    $this->post('/register', [
        'name' => 'Md. Ashraful',
        'phone' => '12345678901',
        'email' => 'test@test.com',
        'nid' => '1234567890123',
        'vaccine_center_id' => $vaccineCenter->id,
    ]);

    $response = $this->post('/search', [
        'nid' => '1234567890123',
    ]);

    $response->assertSee('Scheduled');
    $response->assertSee(User::first()->vaccine_scheduled_at->format('M d, Y'));

    User::first()->update([
        'vaccine_scheduled_at' => today()->subDay()
    ]);

    $response = $this->post('/search', [
        'nid' => '1234567890123',
    ]);

    $response->assertSee('Vaccinated');
});
