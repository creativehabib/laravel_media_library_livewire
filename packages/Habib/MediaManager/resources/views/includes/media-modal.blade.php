{{-- mediamanager::includes.media-modal --}}
<div
    x-data="{
        open: false,
        selected: null
    }"
    x-on:open-media-manager.window="open = true"
    x-on:close-media-manager.window="open = false"
    x-cloak
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    x-on:media-selected.window="selected = $event.detail.id"
    x-on:media-unselected.window="selected = null">



    {{-- বাইরে ক্লিক করলে বন্ধ করার জন্য wrapper --}}
    <div class="absolute inset-0"
        @click="open = false">
    </div>

    {{-- Modal --}}
    <div class="relative bg-white dark:bg-slate-900 rounded-lg shadow-xl w-[100vw] sm:w-[90vw] lg:w-[75vw] max-h-[90vh] flex flex-col pb-2 overflow-hidden border border-gray-200 dark:border-slate-700">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 dark:border-slate-700 shrink-0">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Media gallery</h2>

            <button type="button"
                    class="text-xl leading-none text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 cursor-pointer"
                    @click="open = false">
                &times;
            </button>
        </div>

        {{-- Body: Livewire কম্পোনেন্ট --}}
        <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-slate-950/40">
            @livewire('media-manager', [], key('media-manager-modal'))
        </div>

        {{-- Footer: Insert / Close --}}
        <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-2 shrink-0 bg-white dark:bg-slate-900">
            <button type="button" @click="open = false"
                    class="px-3 py-1.5 text-xs border border-gray-200 dark:border-slate-700 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-800 dark:text-gray-100">
                Close
            </button>

            <button type="button"
                    @click="Livewire.dispatch('media-insert')"
                    :disabled="!selected"
                    class="px-3 py-1.5 text-xs rounded-md bg-blue-600 text-white cursor-pointer
               hover:bg-blue-700
               disabled:opacity-60 disabled:cursor-not-allowed">
                Insert
            </button>
        </div>
    </div>
</div>

<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        timeoutId: null
    }"
    x-on:media-toast.window="
        message  = $event.detail.message;
        type     = $event.detail.type || 'success';
        show     = true;

        // আগের টাইমার ক্লিয়ার
        if (timeoutId) clearTimeout(timeoutId);

        timeoutId = setTimeout(() => show = false, 3000);
    "
    x-show="show"
    x-transition
    class="fixed bottom-4 right-4 z-[9999] max-w-xs px-4 py-3 rounded-md shadow-lg
           text-sm"
    :class="{
        'bg-green-600 text-white': type === 'success',
        'bg-yellow-500 text-white': type === 'warning',
        'bg-red-600 text-white': type === 'error',
        'bg-blue-600 text-white': type === 'info',
    }"
>
    <div class="flex items-start gap-2">
        <span>
            <template x-if="type === 'success'">✅</template>
            <template x-if="type === 'warning'">⚠️</template>
            <template x-if="type === 'error'">❌</template>
            <template x-if="type === 'info'">ℹ️</template>
        </span>
        <p x-text="message"></p>
    </div>
</div>


<script>
    document.addEventListener('livewire:init', () => {
        // ১) ইনপুট / প্রিভিউ টার্গেট
        window._mediaTargetField = null;

        // ২) এডিটরের জন্য callback টার্গেট
        window._mediaEditorCallback = null;

        /**
         * পুরনো সিস্টেম: নির্দিষ্ট input + preview আপডেট করবে
         * উদাহরণ: openMediaManager('post_thumbnail')
         */
        window.openMediaManager = function (fieldName) {
            window._mediaTargetField   = fieldName;
            window._mediaEditorCallback = null; // নিশ্চিত করি যেন editor মোড না থাকে

            window.dispatchEvent(new CustomEvent('open-media-manager'));
        };

        /**
         * নতুন সিস্টেম: যেকোনো editor / কাস্টম জায়গায় ইনসার্ট করার জন্য
         * উদাহরণ:
         * openMediaManagerForEditor((url, data) => { ... })
         */
        window.openMediaManagerForEditor = function (callback) {
            window._mediaTargetField    = null;
            window._mediaEditorCallback = callback;

            window.dispatchEvent(new CustomEvent('open-media-manager'));
        };

        // Livewire → media-selected ইভেন্ট
        Livewire.on('media-selected', (payload) => {
            const data = Array.isArray(payload) ? payload[0] : payload;
            const url  = data?.url;

            if (!url) return;

            // 1️⃣ যদি editor callback সেট করা থাকে → ওটার মাধ্যমে হ্যান্ডেল
            if (typeof window._mediaEditorCallback === 'function') {
                try {
                    window._mediaEditorCallback(url, data);
                } catch (e) {
                    console.error('Media editor callback error:', e);
                }

                // মডাল বন্ধ
                window.dispatchEvent(new CustomEvent('close-media-manager'));
                window._mediaEditorCallback = null;
                return;
            }

            // 2️⃣ নরমাল ইনপুট + প্রিভিউ মোড
            const field = window._mediaTargetField;
            if (!field) {
                // কিছুই সেট নেই, শুধু মডাল বন্ধ করে দিব
                window.dispatchEvent(new CustomEvent('close-media-manager'));
                return;
            }

            // ---- ইনপুট আপডেট ----
            let input = document.querySelector('[data-media-input="'+field+'"]');
            if (!input) input = document.getElementById(field);

            if (input) {
                input.value = url;
                input.dispatchEvent(new Event('change'));
            }

            // ---- প্রিভিউ আপডেট ----
            let preview = document.querySelector('[data-media-preview="'+field+'"]');
            if (!preview) preview = document.getElementById(field + '_preview');

            if (preview) {
                preview.src = url;
            }

            window.dispatchEvent(new CustomEvent('close-media-manager'));
            window._mediaTargetField    = null;
            window._mediaEditorCallback = null;
        });
    });
</script>

