<div class="flex flex-col gap-6">
    <x-auth-header :title="__('OIDC Error')" :description="__('An OIDC error occurred, please check that it has been configured correctly.')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Return to') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('log in') }}</flux:link>
    </div>
</div>
