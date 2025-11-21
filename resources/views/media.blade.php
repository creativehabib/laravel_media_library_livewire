<x-layouts.app :title="__('Media Manager')">
    <div class=" h-full w-full rounded-xl">
        <div class="relative p-2 h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            @livewire('media-manager')
        </div>
    </div>
</x-layouts.app>
