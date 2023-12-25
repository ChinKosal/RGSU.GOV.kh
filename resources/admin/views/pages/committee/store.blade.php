@component('admin::components.dialog', ['dialog' => 'storeDialog'])
    <div x-data="storeDialog" class="form-admin">
        <form class="form-wrapper scroll-form">
            <div class="form-header">
                <h3 x-show="!dialogData?.id">@lang('form.header.create', ['name' => __('form.title.member_list')])</h3>
                <h3 x-show="dialogData?.id">@lang('form.header.update', ['name' => __('form.title.member_list')])</h3>
                @include('admin::components.form-change-language')
            </div>
            @csrf
            <div class="form-body">
                <div class="row-2">
                    <div class="form-row">
                        <label>@lang('form.body.label.title_km')<span>*</span></label>
                        <textarea x-model="form.title_km" :disabled="form.disabled" cols="10" rows="2"
                            placeholder="@lang('form.body.placeholder.title_km')"></textarea>
                        <span class="error" x-show="validate?.title_km" x-text="validate?.title_km"></span>
                    </div>
                    <div class="form-row">
                        <label>@lang('form.body.label.title_en')<span>*</span></label>
                        <textarea x-model="form.title_en" :disabled="form.disabled" cols="10" rows="2"
                            placeholder="@lang('form.body.placeholder.title_en')"></textarea>
                        <span class="error" x-show="validate?.title_en" x-text="validate?.title_en"></span>
                    </div>
                </div>
                <div class="row-2">
                    <div class="form-row">
                        <label>@lang('form.body.label.category')<span>*</span></label>
                        <input type="text" x-model="form.category_name" @click="selectCategory()"
                            placeholder="@lang('form.body.placeholder.category')" readonly />
                        <span class="error" x-show="validate?.category" x-text="validate?.category"></span>
                    </div>
                    <div class="form-row">
                        <label>@lang('form.body.label.status')<span>*</span></label>
                        <select x-model="form.status" :disabled="form.disabled">
                            @foreach (config('dummy.status') as $key => $status)
                                <option value="{{ $status['key'] }}" x-bind:selected="form.status == {{ $status['key'] }}">
                                    {{ $status['text'] }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error" x-show="validate?.status" x-text="validate?.status"></span>
                    </div>
                </div>
                <div class="row-2">
                    <!-- choose file -->
                    <div class="form-row">
                        <div class="w-full flex items-center justify-between">
                            <label>@lang('form.body.label.file')</label>
                            <div class="delete-icon" @click="deleteFile">
                                <i data-feather="trash-2"></i>
                                @lang('form.body.label.remove')
                            </div>
                        </div>
                        <div class="form-select-photo image" @click="selectFile(event)">
                            <div class="select-photo" :class={active:form?.file}>
                                <div class="icon">
                                    <i data-feather="file"></i>
                                </div>
                                <div class="title">
                                    <span>@lang('form.body.placeholder.file')</span>
                                </div>
                            </div>
                            <template x-if="form?.file">
                                <div class="image-view active">
                                    <img x-bind:src="file_thumbnail" alt="">
                                </div>
                            </template>
                            <input type="hidden" x-model="form.file" autocomplete="off" role="presentation">
                        </div>
                        <span class="error" x-show="validate?.file" x-text="validate?.file"></span>

                        <!-- preview file -->
                        <template x-if="file_thumbnail">
                            <a class="text-sm text-blue-500 pt-3" x-bind:href="baseImageUrl + form?.file" target="_blank"
                                rel="noopener noreferrer">
                                click here to preview file
                            </a>
                        </template>
                    </div>
                </div>
                <div class="row">
                    <div class="form-row">
                        <div class="form-button">
                            <button type="button" @click="$store.storeDialog.close()" color="cancel">
                                <i data-feather="x"></i>
                                <span>@lang('form.button.cancel')</span>
                            </button>
                            <button type="button" @click="onSave()" color="primary">
                                <i data-feather="save"></i>
                                <span>@lang('form.button.save')</span>
                                <div class="loader" style="display: none" x-show="loading"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        Alpine.data('storeDialog', () => ({
            form: new FormGroup({
                title_km: [null, ['required']],
                title_en: [null, ['required']],
                type: [@json(config('dummy.committee.member_list.key')), ['required']],
                category: [[],['required']],
                category_name: [null, ['required']],
                file: [null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),

            dialogData: null,
            baseImageUrl: @json(asset('file_manager')),
            validate: null,
            loading: false,
            locale: @json(config('dummy.locale.km')),
            km: @json(config('dummy.locale.km')),
            en: @json(config('dummy.locale.en')),
            file_thumbnail: null,
            dataCategory: null,

            async init() {
                this.dialogData = this.$store.storeDialog.data;
                if (this.dialogData?.id) {
                    this.form.patchValue(this.dialogData);
                    this.form.patchValue({
                        title_km: JSON.parse(this.dialogData.title)?.km,
                        title_en: JSON.parse(this.dialogData.title)?.en,
                    });
                }

                if (this.dialogData?.category) {
                    this.form.category_name = [];
                    this.dialogData?.category.map(item => {
                        this.form.category_name.push(JSON.parse(item.name)?.[this.locale]);
                    });

                    // convert form category to array id
                    this.form.category = this.dialogData?.category.map(item => {
                        return {
                            _id: item.id,
                            _title: JSON.parse(item.name)?.[this.locale],
                        }
                    });

                    this.dataCategory = this.dialogData?.category;
                }

                if (this.dialogData?.file) {
                    let file = this.dialogData?.file.split('.');
                    let extension = file[file.length - 1];
                    this.file_thumbnail =
                        `https://via.placeholder.com/300x300/eff3f5/4871f7?text=${extension?.toUpperCase()}`;
                }

                this.$watch('locale', (value) => {
                    this.form.patchValue({
                        category_name: this.dataCategory?.map(item => {
                            return JSON.parse(item.name)?.[value];
                        }),
                        category: this.dataCategory?.map(item => {
                            return {
                                _id: item.id,
                                _title: JSON.parse(item.name)?.[value],
                            }
                        }),
                    });
                });
            },
            selectCategory() {
                SelectOption({
                    title: "@lang('form.body.label.category')",
                    placeholder: "@lang('form.body.placeholder.category')",
                    multiple: true,
                    selected: this.form.category || [],
                    onReady: (callback_data) => {
                        Axios({
                                url: `{{ route('admin-category-data') }}`,
                                params: {
                                    type: @json(config('dummy.category.committee.key')),
                                },
                                method: 'GET',
                            })
                            .then(response => {
                                this.dataCategory = response?.data?.data;
                                const data = response?.data?.data?.map(item => {
                                    return {
                                        _id: item.id,
                                        _title: this.locale == this.km ? JSON.parse(item
                                            .name)?.km : JSON.parse(item.name)?.en,
                                    }
                                });
                                callback_data(data);
                            });
                    },
                    onSearch: (value, callback_data) => {
                        queueSearch = setTimeout(() => {
                            Axios({
                                    url: `{{ route('admin-category-data') }}`,
                                    params: {
                                        search: value,
                                        type: @json(config('dummy.category.committee.key')),
                                    },
                                    method: 'GET'
                                })
                                .then(response => {
                                    this.dataCategory = response?.data?.data;
                                    const data = response?.data?.data?.map(
                                        item => {
                                            return {
                                                _id: item.id,
                                                _title: this.locale == this.km ?
                                                    JSON.parse(item.name)?.km : JSON
                                                    .parse(item.name)?.en,
                                            }
                                        });
                                    callback_data(data);
                                });
                        }, 1000);
                    },
                    afterClose: (res) => {
                        if (res.length > 0) {
                            let data = res.map(item => {
                                return {
                                    _id: item._id,
                                    _title: item._title
                                }
                            });

                            this.form.category = data.map(item => item);
                            this.form.category_name = data.map(item => item._title);
                            this.dataCategory = this.dataCategory.filter(item => {
                                return data.find(data => data._id == item.id);
                            });
                        }
                    }
                });
            },
            selectFile() {
                fileManager({
                    multiple: false,
                    afterClose: (data, basePath) => {
                        if (data?.length > 0) {
                            this.form.file = data[0].path;
                            let file = data[0];
                            let createThumbnail =
                                `https://via.placeholder.com/300x300/eff3f5/4871f7?text=${file?.extension?.toUpperCase()}`;
                            this.file_thumbnail = createThumbnail;
                        }
                    }
                })
            },
            deleteFile() {
                this.form.file = null;
                this.file_thumbnail = null;
            },
            onSave() {
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('dialog.title')",
                        message: "@lang('dialog.msg.save')",
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.save')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            this.form.disable();
                            this.loading = true;
                            const data = this.form.value();
                            Axios({
                                url: `{{ route('admin-committee-store') }}`,
                                method: 'POST',
                                data: {
                                    ...data,
                                    id: this.dialogData?.id,
                                }
                            }).then((res) => {
                                if (res.data.error == false) {
                                    this.form.reset();
                                    this.$store.storeDialog.close(true);
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });
                                }

                                if (res.data.error != false) {
                                    Toast({
                                        message: res.data.message,
                                        status: 'danger',
                                        size: 'small',
                                    });
                                }
                            }).catch((e) => {
                                this.validate = e.response.data.errors;

                                // check validate with khmer tab
                                let validateKhmer = Object.keys(this.validate).filter((
                                    item) => item.includes('km'));

                                // check validate with english tab
                                let validateEnglish = Object.keys(this.validate).filter((
                                    item) => item.includes('en'));

                                if (validateKhmer.length > 0) {
                                    this.locale = this.km;
                                } else {
                                    if (validateEnglish.length > 0) {
                                        this.locale = this.en;
                                    }
                                }
                            }).finally(() => {
                                this.form.enable();
                                this.loading = false;
                            });
                        }
                    }
                });
            }
        }));
    </script>
@endcomponent
