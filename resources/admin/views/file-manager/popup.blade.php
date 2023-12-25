<template x-data="{}" x-if="$store?.page?.active">
    <div class="dialog" x-bind:style="{ zIndex: $store.libs.getLastIndex() + 1 }">
        <div class="dialog-container" x-ref="popupRef" x-bind:onload="$store.libs.playAnimateOnLoad($refs.popupRef)">
            <template x-if="$store.page.active == 'all_files'">
                @include('admin::file-manager.all_files')
            </template>
            <template x-if="$store.page.active == 'trash_bin'">
                @include('admin::file-manager.trash_bin')
            </template>
            <template x-if="$store.page.active == 'settings'">
                @include('admin::file-manager.settings')
            </template>
        </div>
    </div>
</template>
<script type="module">
    moment.locale('{{ App::currentLocale() }}');
    Alpine.store('page', {
        active: false,
        options: {
            multiple: false,
            afterClose: () => {}
        }
    });

    window.fileManager = (options) => {
        Alpine.store('page', {
            active: 'all_files',
            options: options
        });
    };
</script>
