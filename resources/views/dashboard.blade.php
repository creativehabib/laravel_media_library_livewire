<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            {{--            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />--}}
            @livewire('media-manager')


        </div>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">

                @include('mediamanager::includes.media-modal')
                @include('mediamanager::includes.media-input', [
                    'name'  => 'thumbnail',
                    'id'    => 'post_thumbnail',
                    'label' => 'Thumbnail',
                    'value' => old('thumbnail', $post->thumbnail ?? ''),
                ])

            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                @include('mediamanager::includes.media-input', [
                   'name'  => 'thumbnail',
                   'id'    => 'user_avatar',
                   'label' => 'Thumbnail',
                   'value' => old('thumbnail', $post->thumbnail ?? ''),
               ])


            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                @include('mediamanager::includes.media-input', [
                  'name'  => 'thumbnail',
                  'id'    => 'posts_thumbnail',
                  'label' => 'Post Thumbnail',
                  'value' => old('thumbnail', $post->thumbnail ?? ''),
              ])

            </div>

            <livewire:media-selector name="featured_image_id" />




        </div>
        <div class="">
            <button type="button"
                    onclick="openCkeditorImagePicker('post_content')"
                    class="mt-2 px-3 py-1.5 text-xs rounded bg-blue-600 text-white cursor-pointer">
                Insert image from media
            </button>
            <textarea id="post_content"
                      name="content"
                      class="border rounded w-full min-h-[300px]">
                    {{ old('content', $post->content ?? '') }}
                </textarea>
        </div>
    </div>
</x-layouts.app>
