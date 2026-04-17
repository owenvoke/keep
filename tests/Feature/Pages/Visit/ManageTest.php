<?php

declare(strict_types=1);

use App\Livewire\Pages\Visit\Manage as VisitManage;
use App\Models\User;
use App\Models\Visit;
use Database\Factories\KeepFactory;
use Database\Factories\VisitFactory;

test('visit manage can create a visit', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();
    $visitedAt = now()->subDay();

    $this->actingAs($user);

    Livewire::test(VisitManage::class, ['keep' => $keep])
        ->set('comment', 'Great day out.')
        ->set('visited', $visitedAt->format('Y-m-d\TH:i'))
        ->call('save')
        ->assertRedirect();

    $visit = Visit::query()
        ->where('keep_uuid', $keep->uuid)
        ->where('user_id', $user->id)
        ->first();

    expect($visit)->not->toBeNull();
    expect($visit?->comment)->toBe('Great day out.');
    expect($visit?->visited_at->format('Y-m-d H:i'))->toBe($visitedAt->format('Y-m-d H:i'));
});

test('visit manage can update an existing visit', function () {
    $user = User::factory()->create();
    $visit = VisitFactory::new()->create([
        'user_id' => $user->id,
        'comment' => 'Old comment',
    ]);

    $this->actingAs($user);

    Livewire::test(VisitManage::class, ['keep' => $visit->keep, 'visit' => $visit])
        ->set('comment', 'Updated comment')
        ->call('save')
        ->assertHasNoErrors();

    expect($visit->refresh()->comment)->toBe('Updated comment');
});

test('visit manage prevents users from updating another users visit', function () {
    $user = User::factory()->create();
    $visit = VisitFactory::new()->create();

    $this->actingAs($user);

    Livewire::test(VisitManage::class, ['keep' => $visit->keep, 'visit' => $visit])
        ->call('save')
        ->assertForbidden();
});

test('visit manage validates that visited date is not in the future', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();

    $this->actingAs($user);

    Livewire::test(VisitManage::class, ['keep' => $keep])
        ->set('visited', now()->addDay()->format('Y-m-d\TH:i'))
        ->call('save')
        ->assertHasErrors(['visited']);
});
