<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-2">

                @include('mediamanager::includes.media-input', [
                    'name'  => 'thumbnail',
                    'id'    => 'post_thumbnail',
                    'label' => 'Thumbnail',
                    'value' => old('thumbnail', $post->thumbnail ?? ''),
                ])

            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-2">
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
        <flux:modal.trigger name="edit-profile">
            <flux:button>Edit profile</flux:button>
        </flux:modal.trigger>

        <flux:modal name="edit-profile" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Update profile</flux:heading>
                    <flux:text class="mt-2">Make changes to your personal details.</flux:text>
                </div>

                <flux:input label="Name" placeholder="Your name" />

                <flux:input label="Date of birth" type="date" />

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</x-layouts.app>
