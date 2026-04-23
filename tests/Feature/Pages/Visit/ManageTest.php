<?php

declare(strict_types=1);

use App\Livewire\Pages\Visit\Manage as VisitManage;
use App\Models\User;
use App\Models\Visit;
use Database\Factories\KeepFactory;
use Database\Factories\VisitFactory;
use Illuminate\Support\Number;

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

    expect($visit)
        ->not->toBeNull()
        ->comment->toBe('Great day out.')
        ->visited_at->format('Y-m-d H:i')->toBe($visitedAt->format('Y-m-d H:i'));
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

    expect($visit->refresh())
        ->comment->toBe('Updated comment');
});

test('visit manage prevents users from updating another users visit', function () {
    $user = User::factory()->create();
    $visit = VisitFactory::new()->create();

    $this->actingAs($user);

    Livewire::test(VisitManage::class, ['keep' => $visit->keep, 'visit' => $visit])
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

test('visit manage can delete an existing visit', function () {
    $user = User::factory()->create();
    $visit = VisitFactory::new()->create([
        'user_id' => $user->id,
        'comment' => 'Old comment',
    ]);

    $this->actingAs($user);

    Livewire::test(VisitManage::class, ['keep' => $visit->keep, 'visit' => $visit])
        ->call('delete')
        ->assertHasNoErrors()
        ->assertDispatched(
            event: 'toast-show',
            slots: [
                'text' => __('Your visit has been deleted.'),
            ],
        );

    $this->assertModelMissing($visit);
});

test('visit manage sends a congratulatory notification on every 10th visit', function () {
    $user = User::factory()->create();
    $keep = KeepFactory::new()->create();
    $visitedAt = now()->subDay();

    VisitFactory::new()->count(9)->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    Livewire::test(VisitManage::class, ['keep' => $keep])
        ->set('comment', 'Milestone visit.')
        ->set('visited', $visitedAt->format('Y-m-d\TH:i'))
        ->call('save')
        ->assertDispatched(
            event: 'toast-show',
            duration: 10000,
            slots: [
                'text' => __('Congratulations! This is your :count visit.', [
                    'count' => Number::ordinal(10),
                ]),
            ],
            dataset: [
                'variant' => 'success',
            ],
        );
});
