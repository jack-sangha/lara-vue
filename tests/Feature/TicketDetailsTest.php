<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated users can fetch fresh ticket details', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($user)->getJson(route('tickets.details', $ticket));

    $response
        ->assertOk()
        ->assertJsonPath('id', $ticket->id)
        ->assertJsonPath('title', $ticket->title)
        ->assertJsonPath('description', $ticket->description);
});

test('guests cannot fetch ticket details', function () {
    $ticket = Ticket::factory()->create();

    $this->getJson(route('tickets.details', $ticket))
        ->assertUnauthorized();
});
