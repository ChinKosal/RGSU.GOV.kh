@extends('admin::shared.layout')
@section('layout')
    <div class="content-wrapper" x-data="form">
        @include('admin::shared.header', [
            'title' => __('form.title.' . request('page')),
            'header_name' => __('form.name.' . request('page')),
        ])
        <div class="content-body">
            <div class="content-tab">
                <div class="content-tab-wrapper">
                    <span class="title capitalize">
                        @lang('form.total', ['name' => __('form.title.' . request('page'))]) <span x-text="table?.paginate?.totalItems"></span>
                    </span>
                </div>
                <div class="content-action-button">
                    <div class="filter">
                        <div class="form-row search-inline">
                            <input type="text" x-model="formFilter.search" name="search" placeholder="@lang('admin.filter.search')"
                                value="{!! request('search') !!}">
                            <button @click="onFilter()">
                                <i data-feather="search"></i>
                            </button>
                        </div>
                    </div>
                    @can('about-us-create')
                        <button class="btn-create" @click="openStoreDialog()">
                            <i data-feather="plus"></i>
                            <span>@lang('category.button.create')</span>
                        </button>
                    @endcan
                    @can('about-us-delete')
                        <button @click="viewTrash()" class="!text-rose-500">
                            <i data-feather="trash-2"></i>
                            <span>@lang('table.option.trash')</span>
                        </button>
                    @endcan
                    <button @click="onReset()">
                        <i data-feather="refresh-ccw"></i>
                    </button>
                </div>
            </div>
            @include('admin::pages.about-us.table')
        </div>
        @include('admin::pages.about-us.store')
    </div>
@stop
@section('script')
    <script type="module">
        Alpine.data('form', () => ({
            table: new Table("{{ route('admin-about-us-row-data', request('page')) }}"),
            page: @json(request()->page),

            init() {
                this.table.init();
                this.formFilter.search = '{{ request('search') ?? null }}';
            },
            formFilter: new FormGroup({
                search: [null, []],
            }),
            viewTrash() {
                this.table.init({
                    page : this.page,
                    trash: true,
                });
            },
            onFilter() {
                this.table.init(this.formFilter.value());
            },
            onReset() {
                this.formFilter.reset();
                this.table.reset();
            },
            openStoreDialog(data) {
                this.$store.openStoreDialog.open({
                    data: {
                        data: data
                    },
                    afterClosed: (res) => {
                        if (res) {
                            this.table.reload();
                        }
                    }
                });
            },
            onUpdateStatus(data, status) {
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('dialog.title')",
                        message: (status == 1 ? `@lang('dialog.msg.enable')` : `@lang('dialog.msg.disable')`)+ '?',
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: status == 1 ? "@lang('dialog.button.enable')" : "@lang('dialog.button.disable')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-about-us-status') }}`,
                                method: 'POST',
                                data: {
                                    id: data.id,
                                    status: status
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
            onDelete(data) {
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('dialog.title')",
                        message: `@lang('dialog.msg.delete')?`,
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.delete')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-about-us-delete') }}`,
                                method: 'DELETE',
                                data: {
                                    id: data.id
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
            onRestore(data) {
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('dialog.title')",
                        message: `@lang('dialog.msg.restore')?`,
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.restore')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-about-us-restore') }}`,
                                method: 'PUT',
                                data: {
                                    id: data.id
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
            onDestroy(data) {
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('dialog.title')",
                        message: `@lang('dialog.msg.delete')?`,
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.delete')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-about-us-destroy') }}`,
                                method: 'DELETE',
                                data: {
                                    id: data.id
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
