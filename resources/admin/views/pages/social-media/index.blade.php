@extends('admin::shared.layout')
@section('layout')
    <div class="content-wrapper" x-data="page">
        @include('admin::shared.header', [
            'title' => __('form.title.social_media'),
            'header_name' => __('form.name.social_media'),
        ])
        <div class="content-body">
            <div class="content-tab">
                <div class="content-tab-wrapper">
                    <span class="title">
                        @lang('form.total', ['name' => __('form.title.social_media')]) <span x-text="table?.paginate?.totalItems"></span>
                    </span>
                </div>
                <div class="content-action-button">
                    <template x-if="Ids.length <= 0">
                        <div class="filter">
                            <div class="form-row search-inline">
                                <input type="text" x-model="formFilter.search" name="search"
                                    placeholder="@lang('admin.filter.search')" value="{!! request('search') !!}">
                                <button @click="onFilter()"><i data-feather="search"></i></button>
                            </div>
                        </div>
                    </template>
                    @can('social-media-create')
                        <template x-if="Ids.length <= 0">
                            <button class="btn-create" @click="openStoreDialog()">
                                <i data-feather="plus"></i>
                                <span>@lang('form.header.button.create')</span>
                            </button>
                        </template>
                    @endcan
                    <button @click="onReset()">
                        <i data-feather="refresh-ccw"></i>
                    </button>
                </div>
            </div>
            @include('admin::pages.social-media.table')
        </div>
        @include('admin::pages.social-media.store')
    </div>
@stop
@section('script')
    <script type="module">
        Alpine.data('page', () => ({
            table: new Table("{{ route('admin-social-media-data') }}" + '?type=' + @json(request()->type)),
            Ids: [],
            status: false,
            active: @json(config('dummy.status.active.key')),
            disabled: @json(config('dummy.status.disabled.key')),

            async init() {
                this.table.init();
                this.formFilter.search = '{{ request('search') ?? null }}';
                this.formFilter.type = @json(request()->type);
                this.$watch('Ids', () => feather.replace());
            },
            formFilter: new FormGroup({
                search: [null, []],
            }),
            onFilter() {
                this.table.init(this.formFilter.value());
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
                this.Ids = [];
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
                                url: `{{ route('admin-social-media-saveSingleOption') }}`,
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
