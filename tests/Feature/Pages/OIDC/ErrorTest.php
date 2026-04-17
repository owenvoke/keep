<?php

declare(strict_types=1);

test('OIDC error page can be rendered', function () {
    $this->get(route('oidc.error'))
        ->assertOk()
        ->assertSee('OIDC Error');
});
