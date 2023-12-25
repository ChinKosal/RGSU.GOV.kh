@extends('admin::shared.layout')
@section('layout')
    <div class="content-wrapper" x-data="page">
        @include('admin::shared.header', [
            'title' => __('form.title.' . request('type')),
            'header_name' => __('form.name.' . request('type')),
        ])
        <div class="content-body">
            <div class="content-tab">
                <div class="content-tab-wrapper">
                    <span class="title">
                        @lang('form.total', ['name' => __('form.title.' . request('type'))]) <span x-text="table?.paginate?.totalItems"></span>
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
                    @can('committee-create')
                        <template x-if="Ids.length <= 0">
                            <button class="btn-create" @click="openStoreDialog()">
                                <i data-feather="plus"></i>
                                <span>@lang('form.header.button.create')</span>
                            </button>
                        </template>
                    @endcan
                    @can('committee-update')
                        <template x-if="Ids.length <= 0">
                            <button class="!text-rose-500" @click="viewBulk()">
                                <i data-feather="alert-triangle"></i>
                                <span>@lang('table.option.hidden')</span>
                            </button>
                        </template>
                        <template x-if="Ids.length > 0 && status == false">
                            <button class="!bg-rose-500 !text-white" type="button" @click="bulkHideShow(disabled)">
                                <i data-feather="alert-triangle"></i>
                                <span>@lang('table.option.bulk_hidden')</span>
                            </button>
                        </template>
                        <template x-if="Ids.length > 0 && status == true">
                            <button class="!bg-green-600 !text-white" type="button" @click="bulkHideShow(active)">
                                <i data-feather="eye"></i>
                                <span>@lang('table.option.bulk_show')</span>
                            </button>
                        </template>
                    @endcan
                    @can('committee-delete')
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
            @include('admin::pages.committee.table')
        </div>
        <div x-show="type == isTypeMemberList">
            @include('admin::pages.committee.store')
        </div>
        <div x-show="type != isTypeMemberList">
            @include('admin::pages.committee.page')
        </div>
    </div>
@stop
@section('script')
    <script src="{{ asset('plugin/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script type="module">
        Alpine.data('page', () => ({
            table: new Table("{{ route('admin-committee-data') }}"),
            Ids: [],
            status: false,
            active: @json(config('dummy.status.active.key')),
            disabled: @json(config('dummy.status.disabled.key')),
            type: @json(request('type')),
            isTypeMemberList : @json(config('dummy.committee.member_list.key')),

            async init() {
                this.table.init();
                this.formFilter.search = @json(request('search') ?? null);
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
                    trash: true,
                    status: '',
                });
                this.Ids = [];
                this.type = '?type=' + @json(request('type'));
            },
            viewBulk() {
                this.table.init({
                    status: true,
                    trash: '',
                });
                this.type = '?type=' + @json(request('type'));
                this.status = true;
            },
            onReset() {
                this.formFilter.reset();
                this.table.init({
                    type : @json(request('type')),
                    status: '',
                    trash: '',
                });
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
                        message: (option == this.active ? `@lang('dialog.msg.enable')` : `@lang('dialog.msg.disable')`)+'?',
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: option == this.active ? "@lang('dialog.button.enable')" : "@lang('dialog.button.disable')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-committee-saveSingleOption') }}`,
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
            checkAll(el, data) {
                let checkbox = $('.product-checkbox');
                if (el.checked == true) {
                    data.map((val) => {
                        this.Ids.push(val.id);
                    });
                    checkbox.prop('checked', true);
                } else {
                    this.Ids = [];
                    checkbox.prop('checked', false);
                }
            },
            bulkHideShow(option) {
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('dialog.title')",
                        message: (option == this.disabled ? `@lang('dialog.msg.disable')` : `@lang('dialog.msg.enable')`)+'?',
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: option == this.disabled ? "@lang('dialog.button.disable')" : "@lang('dialog.button.enable')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: "{{ route('admin-committee-bulk-hide-show') }}",
                                method: 'DELETE',
                                data: {Ids: this.Ids, option: option}
                            }).then((res) => {
                                if (res.data.error == false) {
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });
                                    this.Ids = [];
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
                        message: `@lang('dialog.msg.move_to_trash')`,
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.move_to_trash')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-committee-delete') }}`,
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
                        message: `@lang('dialog.msg.restore')`,
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.restore')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-committee-restore') }}`,
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
                        message: `@lang('dialog.msg.delete')`,
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.delete')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            Axios({
                                url: `{{ route('admin-committee-destroy') }}`,
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
