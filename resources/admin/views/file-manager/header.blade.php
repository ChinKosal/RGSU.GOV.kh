<div class="file-manager-header">
    <div class="label">
        <span>@lang('file-manager.title')</span>
    </div>
    <div class="gap"></div>
    @if (!request()->is('*/file-manager/*'))
        <button @click="onClose()">
            <i data-feather="x"></i>
            <span>@lang('file-manager.button.close')</span>
        </button>
    @endIf
</div>
