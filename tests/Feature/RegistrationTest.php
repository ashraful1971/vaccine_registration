<?php

use App\Models\User;
use App\Models\VaccineCenter;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('register route returns a successfull response', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('registering user works', function () {
    $vaccineCenter = VaccineCenter::factory()->create();
    
    $this->post('/register', [
        'name' => 'Md. Ashraful',
        'phone' => '12345678901',
        'email' => 'test@test.com',
        'nid' => '1234567890123',
        'vaccine_center_id' => $vaccineCenter->id,
    ]);

    $this->assertDatabaseCount('vaccine_centers',1);
    $this->assertDatabaseCount('users',1);
});

test('user can not register multiple times', function () {
    $vaccineCenter = VaccineCenter::factory()->create();
    
    $this->post('/register', [
        'name' => 'Md. Ashraful',
        'phone' => '12345678901',
        'email' => 'test@test.com',
        'nid' => '1234567890123',
        'vaccine_center_id' => $vaccineCenter->id,
    ]);
    
    $this->post('/register', [
        'name' => 'Md. Ashraful',
        'phone' => '12345678901',
        'email' => 'test@test.com',
        'nid' => '1234567890123',
        'vaccine_center_id' => $vaccineCenter->id,
    ]);
    
    $this->assertDatabaseCount('users', 1);
});

test('vaccination schedule works', function(){
    $vaccineCenter = VaccineCenter::factory()->create();
    
    $this->post('/register', [
        'name' => 'Md. Ashraful',
        'phone' => '12345678901',
        'email' => 'test@test.com',
        'nid' => '1234567890123',
        'vaccine_center_id' => $vaccineCenter->id,
    ]);

    $user = User::first();

    $this->assertNotNull($user->vaccine_scheduled_at);
});

test('vaccination schedule follows the daily limit of the center with first come first serve strategy', function(){
    $vaccineCenter = VaccineCenter::factory()->create([
        'daily_limit' => 2
    ]);
    
    $this->post('/register', [
        'name' => 'Md. Ashraful',
        'phone' => '12345678901',
        'email' => 'test@test.com',
        'nid' => '1234567890123',
        'vaccine_center_id' => $vaccineCenter->id,
    ]);
    
    $this->post('/register', [
        'name' => 'Rana',
        'phone' => '12345678901',
        'email' => 'test2@test.com',
        'nid' => '0234567890123',
        'vaccine_center_id' => $vaccineCenter->id,
    ]);
    
    $this->post('/register', [
        'name' => 'Hasan',
        'phone' => '12345678901',
        'email' => 'hasan@test.com',
        'nid' => '2234567890123',
        'vaccine_center_id' => $vaccineCenter->id,
    ]);

    $users = User::all();

    $this->assertNotNull($users[0]->vaccine_scheduled_at);
    $this->assertNotNull($users[1]->vaccine_scheduled_at);
    $this->assertNotNull($users[2]->vaccine_scheduled_at);

    $this->assertEquals($users[0]->vaccine_scheduled_at, $users[1]->vaccine_scheduled_at);
    $this->assertNotEquals($users[1]->vaccine_scheduled_at, $users[2]->vaccine_scheduled_at);
});