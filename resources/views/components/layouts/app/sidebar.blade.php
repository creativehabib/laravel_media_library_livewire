<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">

<div class="min-h-screen flex">
    {{-- ============= SIDEBAR ============= --}}
    <flux:sidebar
        sticky
        collapsible
        breakpoint="0" {{-- সব স্ক্রিনে desktop-style sidebar, কোনো drawer নয় --}}
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
    >
        {{-- HEADER: Logo + collapse --}}
        <flux:sidebar.header class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}"
               class="me-5 flex items-center space-x-2 rtl:space-x-reverse"
               wire:navigate
            >
                <x-app-logo />
            </a>

            {{-- সব স্ক্রিনেই collapse বাটন --}}
            <flux:sidebar.collapse class="ms-auto" />

            {{-- drawer ব্যবহার করছি না, তাই toggle দরকার নেই --}}
            {{-- <flux:sidebar.toggle class="lg:hidden" icon="x-mark" /> --}}
        </flux:sidebar.header>

        {{-- QUICK LINK --}}
        <flux:sidebar.nav>
            <flux:sidebar.item
                icon="home"
                :href="route('dashboard')"
                :current="request()->routeIs('dashboard')"
                tooltip="{{ __('Dashboard') }}"
                wire:navigate
            >
                {{ __('Dashboard') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="book-open"
                :href="route('media')"
                :current="request()->routeIs('media')"
                tooltip="{{ __('Media') }}"
                wire:navigate
            >
                {{ __('Media') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        {{-- MAIN NAVIGATION --}}
        <flux:sidebar.nav>
            <flux:sidebar.group
                heading="{{ __('Platform') }}"
                icon="square-3-stack-3d"
                expandable
                class="grid"
                :expanded="
                request()->routeIs('dashboard') ||
                request()->routeIs('mediamanager.*') ||
                request()->routeIs('home')
            "
            >
                <flux:sidebar.item
                    icon="home"
                    :href="route('home')"
                    :current="request()->routeIs('home')"
                    tooltip="{{ __('Visit Website') }}"
                    wire:navigate
                >
                    {{ __('Visit Website') }}
                </flux:sidebar.item>

            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        {{-- SECONDARY LINKS --}}
        <flux:sidebar.nav>
            <flux:sidebar.item
                icon="folder-git-2"
                href="https://github.com/laravel/livewire-starter-kit"
                target="_blank"
                tooltip="{{ __('Repository') }}"
            >
                {{ __('Repository') }}
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="book-open-text"
                :href="route('home')"
                :current="request()->routeIs('home')"
                target="_blank"
                tooltip="{{ __('Visit Website') }}"
            >
                {{ __('Visit Website') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        {{-- DESKTOP + MOBILE – দুই জায়গাতেই user menu দেখাতে চাই --}}
        <div class="mt-4">
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                >
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">
                                    {{ auth()->user()->name }}
                                </span>
                                    <span class="truncate text-xs">
                                    {{ auth()->user()->email }}
                                </span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item
                            :href="route('profile.edit')"
                            icon="cog"
                            wire:navigate
                        >
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:sidebar>
    {{-- ============= /SIDEBAR ============= --}}

    {{-- ============= MAIN CONTENT ============= --}}
    <main class="flex-1 min-h-screen bg-white dark:bg-zinc-800">
        <div class="p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </div>
    </main>
    {{-- ============= /MAIN CONTENT ============= --}}
</div>

@fluxScripts
@include('mediamanager::includes.media-modal')

<link rel="stylesheet" href="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.css">
<script src="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    // CKEditor 4 ইনিশিয়ালাইজ
    CKEDITOR.replace('post_content');

    function openCkeditorImagePicker(editorId) {
        openMediaManagerForEditor(function (url, data) {
            var editor = CKEDITOR.instances[editorId];
            if (!editor) return;

            var selection = editor.getSelection();
            var element   = selection.getStartElement();

            if (element && element.getName && element.getName() === 'img') {
                element.setAttribute('src', url);
                if (data?.name) {
                    element.setAttribute('alt', data.name);
                }
            } else {
                editor.insertHtml(
                    '<img src="' + url + '" alt="' + (data?.name || '') + '"/>'
                );
            }
        });
    }
</script>
</body>
</html>
