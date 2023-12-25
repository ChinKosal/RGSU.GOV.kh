@component('admin::components.dialog', ['dialog' => 'storeDialog'])
    <style>
        .tox-dialog-wrap .tox-dialog-wrap__backdrop {
            background: #75757547 !important;
        }

        .dialog {
            z-index: unset;
        }

        .tox-dialog__header .tox-button.tox-button--icon.tox-button--naked {
            display: none;
        }
    </style>
    <div x-data="storeDialog" class="form-admin" style="width: calc(100vw - 200px);">
        <form class="form-wrapper scroll-form">
            <div class="form-header">
                <h3 x-show="!dialogData?.id">@lang('form.header.create', ['name' => __('form.title.' . request('type'))])</h3>
                <h3 x-show="dialogData?.id">@lang('form.header.update', ['name' => __('form.title.' . request('type'))])</h3>
                @include('admin::components.form-change-language')
            </div>
            @csrf
            <div class="form-body" style="display: grid;grid-template-columns: minmax(200px, 25%) 1fr;grid-gap: 20px;">
                <div class="form-item">
                    <div class="row">
                        <div class="form-row" x-show="locale == km">
                            <label>@lang('form.body.label.title_km')<span>*</span></label>
                            <textarea x-model="form.title_km" :disabled="form.disabled" cols="10" rows="5"
                                placeholder="@lang('form.body.placeholder.title_km')"></textarea>
                            <span class="error" x-show="validate?.title_km" x-text="validate?.title_km"></span>
                        </div>
                        <div class="form-row" x-show="locale == en">
                            <label>@lang('form.body.label.title_en')<span>*</span></label>
                            <textarea x-model="form.title_en" :disabled="form.disabled" cols="10" rows="5"
                                placeholder="@lang('form.body.placeholder.title_en')"></textarea>
                            <span class="error" x-show="validate?.title_en" x-text="validate?.title_en"></span>
                        </div>
                    </div>
                    <div class="row">
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
                                    <option value="{{ $status['key'] }}"
                                        x-bind:selected="form.status == {{ $status['key'] }}">
                                        {{ $status['text'] }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error" x-show="validate?.status" x-text="validate?.status"></span>
                        </div>
                    </div>
                </div>
                <div class="form-item">
                    <div class="row">
                        <div class="form-row" x-show="locale == km">
                            <label>@lang('form.body.label.content_km')<span>*</span></label>
                            <textarea id="mytextareakm" rows="24" class="!h-[400px]" placeholder="@lang('form.body.placeholder.content_km')"
                                x-model="form.content_km"></textarea>
                            <span class="error" x-show="validate?.content_km" x-text="validate?.content_km"></span>
                        </div>
                        <div class="form-row" x-show="locale == en">
                            <label>@lang('form.body.label.content_en')<span>*</span></label>
                            <textarea id="mytextareaen" rows="24" class="h-[500px]" placeholder="@lang('form.body.placeholder.content_en')" x-model="form.content_en"></textarea>
                            <span class="error" x-show="validate?.content_en" x-text="validate?.content_en"></span>
                        </div>
                    </div>
                    <div class="form-button">
                        <button type="button" @click="onClose()" color="cancel">
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
        </form>
    </div>
    <script>
        Alpine.data('storeDialog', () => ({
            form: new FormGroup({
                title_km: [null, ['required']],
                title_en: [null, ['required']],
                type: [@json(request('type')), ['required']],
                category: [[],['required']],
                category_name: [null, ['required']],
                content_km: [null, ['required']],
                content_en: [null, ['required']],
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
                        content_km: JSON.parse(this.dialogData.content)?.km,
                        content_en: JSON.parse(this.dialogData.content)?.en,
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

                this.$watch('locale', (value) => {
                    this.form.patchValue({
                        category_name: this.dataCategory?.map(item => {
                            return JSON.parse(item.name)?.[value];
                        }),
                    });
                });

                await this.initTinymce();
            },
            async initTinymce() {
                await tinymce.remove();
                await tinymce.init({
                    selector: 'textarea#mytextareaen,textarea#mytextareakm',
                    content_style: 'body { font-family:Inter, sans-serif, Poppins, sans-serif, Hanuman, serif; font-size:14px }',
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'wordcount'
                    ],
                    toolbar: 'fullscreen | bold italic underline | addImage media link | numlist bullist | styles | alignleft aligncenter alignright alignjustify | outdent indent ',
                    setup: function(editor) {
                        editor.ui.registry.addButton('addImage', {
                            text: 'Image',
                            icon: 'image',
                            onAction: () => {
                                fileManager({
                                    multiple: true,
                                    afterClose: (result, basePath) => {
                                        if (result && result.length >
                                            0) {
                                            result.map((file) => {
                                                const img =
                                                    editor.dom
                                                    .createHTML(
                                                        'img', {
                                                            src: basePath +
                                                                file
                                                                .path,
                                                            style: 'width:100% !important;'
                                                        });
                                                editor
                                                    .insertContent(
                                                        img);
                                            });
                                        }
                                    }
                                })
                            }
                        });
                    },
                });
            },
            selectCategory() {
                SelectOption({
                    title: "@lang('form.body.label.category')",
                    placeholder: "@lang('form.body.placeholder.category')",
                    multiple: false,
                    selected: this.form.category || [],
                    onReady: (callback_data) => {
                        Axios({
                                url: `{{ route('admin-category-data') }}`,
                                params: {
                                    type: @json(config('dummy.category.principal_recipient.key')),
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
                                        type: @json(config('dummy.category.principal_recipient.key')),
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
                        this.form.category = Array.isArray(res) ? res : [res];
                        this.form.category_name = res?._title;
                        this.dataCategory = this.dataCategory?.find((item) => item.id == this.form
                            .category);
                    }
                });
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
                            this.form.content_km = tinymce.get('mytextareakm').getContent();
                            this.form.content_en = tinymce.get('mytextareaen').getContent();
                            const data = this.form.value();
                            Axios({
                                url: `{{ route('admin-principal-recipient-store') }}`,
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

                                    // destroy tinymce
                                    tinymce.remove();
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
            },
            onClose() {
                this.$store.storeDialog.close();

                // destroy tinymce
                tinymce.remove();
            },
        }));
    </script>
@endcomponent
