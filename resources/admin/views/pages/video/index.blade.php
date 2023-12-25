@extends('admin::shared.layout')
@section('layout')
    <div class="content-wrapper" x-data="page">
        @include('admin::shared.header', [
            'title' => __('form.title.video'),
            'header_name' => __('form.name.video'),
        ])
        <div class="content-body">
            <div class="content-tab">
                <div class="content-tab-wrapper">
                    <span class="title">
                        @lang('form.total', ['name' => __('form.title.video')]) <span x-text="table?.paginate?.totalItems"></span>
                    </span>
                </div>
                <div class="content-action-button">
                    @can('video-create')
                        <button class="btn-create" @click="openStoreDialog()">
                            <i data-feather="plus"></i>
                            <span>@lang('form.header.button.create')</span>
                        </button>
                    @endcan
                    <button @click="onReset()">
                        <i data-feather="refresh-ccw"></i>
                    </button>
                </div>
            </div>
            @include('admin::pages.video.table')
        </div>
        @include('admin::pages.video.store')
    </div>
@stop
@section('script')
    <script type="module">
        Alpine.data('page', () => ({
            table: new Table("{{ route('admin-video-data') }}"),
            status: false,
            active: @json(config('dummy.status.active.key')),
            disabled: @json(config('dummy.status.disabled.key')),
            
            async init() {
                this.table.init();
            },
            viewTrash() {
                this.table.init({
                    status: true
                });
                this.status = true;
            },
            onReset() {
                this.formFilter.reset();
                this.table.reset();
                this.status = false;
                this.partnerIds = [];
            },
            openStoreDialog(data) {
                this.$store.storeDialog.open({
                    data: data,
                    afterClosed: (res) => {
                        if (res) {
                            this.table.reload();
                        }
                    }
                });
            },
            onUpdateOption(data, option){
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('dialog.title')",
                        message: (option == this.active ? `@lang('dialog.msg.enable')` : `@lang('dialog.msg.disable')`) + data
                            ?.name + '?',
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: option == this.active ? "@lang('dialog.button.enable')" : "@lang('dialog.button.disable')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-video-saveSingleOption') }}`,
                                method: 'POST',
                                data: {
                                    id: data.id,
                                    option: option
                                }
                            }).then((res) => {
                                if (res.data.error == false) {
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });
                                    this.table.reload();
                                }
                            }).catch((e) => {
                                console.log(e);
                            });
                        }
                    }
                });
            },
        }));
    </script>
@stop
