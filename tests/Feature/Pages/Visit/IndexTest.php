<?php

declare(strict_types=1);

use App\Livewire\Pages\Visit\Index as VisitIndex;
use App\Models\User;
use Database\Factories\KeepFactory;
use Database\Factories\VisitFactory;

test('visit index page can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('visit.index'))
        ->assertOk()
        ->assertSee('Visits');
});

test('visit index defaults to visits by the authenticated user', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    $ownKeep = KeepFactory::new()->create(['name' => 'Own Keep']);
    $otherKeep = KeepFactory::new()->create(['name' => 'Other User Keep']);

    VisitFactory::new()->create([
        'keep_uuid' => $ownKeep->uuid,
        'user_id' => $user->id,
    ]);

    $otherVisit = VisitFactory::new()->create([
        'keep_uuid' => $otherKeep->uuid,
        'user_id' => $anotherUser->id,
    ]);

    $this->actingAs($user);

    Livewire::test(VisitIndex::class)
        ->assertSet('user', $user->id)
        ->assertSet('allUsers', false)
        ->assertSee($ownKeep->name)
        ->assertDontSee($otherVisit->keep->name);
});

test('visit index can set the selected user from a visit row', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    $visit = VisitFactory::new()->create(['user_id' => $anotherUser->id]);

    $this->actingAs($user);

    Livewire::test(VisitIndex::class)
        ->set('allUsers', true)
        ->call('filterUserFromVisit', $visit->uuid)
        ->assertSet('allUsers', false)
        ->assertSet('user', $anotherUser->id);
});

test('private visits are not visible to non-owners', function () {
    $owner = User::factory()->create();
    $visitor = User::factory()->create();
    $keep = KeepFactory::new()->create(['name' => 'Private Keep']);

    VisitFactory::new()->for($keep)->for($owner)->public(false)->create();

    $this->actingAs($visitor);

    Livewire::test(VisitIndex::class)
        ->set('allUsers', true)
        ->assertDontSee($keep->name);
});

test('private visits are visible to the owner', function () {
    $owner = User::factory()->create();
    $keep = KeepFactory::new()->create(['name' => 'Private Keep']);

    VisitFactory::new()->for($keep)->for($owner)->public(false)->create();

    $this->actingAs($owner);

    Livewire::test(VisitIndex::class)
        ->assertSee($keep->name);
});

test('public visits are visible to all users', function () {
    $owner = User::factory()->create();
    $visitor = User::factory()->create();
    $keep = KeepFactory::new()->create(['name' => 'Public Keep']);

    VisitFactory::new()->for($keep)->for($owner)->public(true)->create();

    $this->actingAs($visitor);

    Livewire::test(VisitIndex::class)
        ->set('allUsers', true)
        ->assertSee($keep->name);
});
