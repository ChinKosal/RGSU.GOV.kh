@component('admin::components.dialog', ['dialog' => 'storeSlideDialog'])
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
    <div x-data="storeSlideDialog" class="form-admin" style="width: calc(100vw - 200px);">
        <form class="form-wrapper">
            <div class="form-header">
                <h3 x-show="!dialogData?.id">@lang('form.header.create', ['name' => __('form.title.slider')])</h3>
                <h3 x-show="dialogData?.id">@lang('form.header.update', ['name' => __('form.title.slider')])</h3>
                @include('admin::components.form-change-language', ['dialog' => 'storeDialog'])
            </div>
            {{ csrf_field() }}
            <div class="form-body" style="display: grid;grid-template-columns: minmax(200px, 40%) 1fr;grid-gap: 20px;">
                <div class="form-item">
                    <div class="row-2">
                        <div class="form-row">
                            <label>@lang('form.body.label.ordering')</label>
                            <input x-mask.numeral placeholder="Enter ordering" x-model="form.ordering"
                                :disabled="form.disabled" autocomplete="off" maxlength="10">
                            <span class="error" x-show="validate?.ordering" x-text="validate?.ordering"></span>
                        </div>
                        <div class="form-row">
                            <label>@lang('admin.form.status.label')<span>*</span></label>
                            <select x-model="form.status" :disabled="form.disabled">
                                @foreach (config('dummy.status') as $key => $status)
                                    <option value="{{ $status['key'] }}"
                                        x-bind:selected="form.status == {{ $status['key'] }}">
                                        {{ $status['text'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-row">
                            <label>@lang('form.body.label.thumbnail')<span>*</span></label>
                            <div class="form-select-photo !h-[300px]" @click="selectThumbnail(event)">
                                <div class="select-photo" :class={active:form?.thumbnail}>
                                    <div class="icon">
                                        <i data-feather="image"></i>
                                    </div>
                                    <div class="title">
                                        <span>@lang('form.body.placeholder.thumbnail')</span>
                                    </div>
                                </div>
                                <template x-if="form?.thumbnail">
                                    <div class="image-view active">
                                        <img x-bind:src="baseImageUrl + form?.thumbnail" alt="">
                                    </div>
                                </template>
                                <input type="hidden" x-model="form.thumbnail" autocomplete="off" role="presentation">
                            </div>
                            <span class="error" x-show="validate?.thumbnail" x-text="validate?.thumbnail"></span>
                        </div>
                    </div>
                </div>
                <div class="form-item">
                    <div class="row">
                        <div class="form-row" x-show="locale == km">
                            <label>@lang('form.body.label.content_km')<span>*</span></label>
                            <textarea id="mytextareakm" rows="24" class="h-[500px]" placeholder="@lang('form.body.placeholder.content_km')" x-model="form.content_km"></textarea>
                            <span class="error" x-show="validate?.content_km" x-text="validate?.content_km"></span>
                        </div>
                        <div class="form-row" x-show="locale == en">
                            <label>@lang('form.body.label.content_en')</label>
                            <textarea id="mytextareaen" rows="24" class="h-[500px]" placeholder="@lang('form.body.placeholder.content_en')" x-model="form.content_en"></textarea>
                            <span class="error" x-show="validate?.content_en" x-text="validate?.content_en"></span>
                        </div>
                    </div>
                    <div class="form-button">
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
            </div>
        </form>
    </div>
    <script>
        Alpine.data('storeSlideDialog', () => ({
            form: new FormGroup({
                link: [null, ['required']],
                ordering: [null, ['required']],
                content_km: [null, ['required']],
                content_en: [null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
                thumbnail: [null, ['required']],
            }),
            dialogData: null,
            validate: null,
            loading: false,
            baseImageUrl: "{{ asset('file_manager') }}",
            locale: @json(config('dummy.locale.km')),
            km: @json(config('dummy.locale.km')),
            en: @json(config('dummy.locale.en')),

            async init() {
                this.getOrdering();
                this.dialogData = this.$store.storeSlideDialog.data;
                if (this.dialogData) {
                    this.form.patchValue(this.dialogData ?? {});
                    let content = JSON.parse(this.dialogData?.content);
                    this.form.patchValue({
                        content_km: content.km,
                        content_en: content.en,
                    });
                }

                await this.initTinymce();
            },
            async initTinymce() {
                await tinymce.remove();
                await tinymce.init({
                    relative_urls: false,
                    selector: 'textarea#mytextareaen,textarea#mytextareakm',
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'file', 'charmap',
                        'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'wordcount'
                    ],
                    toolbar: 'fullscreen | bold italic underline | addImage addFile media link | numlist bullist | styles | alignleft aligncenter alignright alignjustify | outdent indent ',
                    setup: function(editor) {
                        editor.ui.registry.addButton('addImage', {
                            text: 'Image',
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

                        editor.ui.registry.addButton('addFile', {
                            text: 'File',
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
                                                        'a', {
                                                            href: basePath +
                                                                file
                                                                .path,
                                                            target: '_blank'
                                                        }, file
                                                        .name);
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
            selectThumbnail() {
                fileManager({
                    multiple: false,
                    afterClose: (data, basePath) => {
                        if (data?.length > 0) {
                            this.form.thumbnail = data[0].path;
                        }
                    }
                })
            },
            getOrdering() {
                Axios({
                    url: `{{ route('admin-slider-ordering') }}`,
                    method: 'GET',
                }).then((res) => {
                    this.dialogData ? this.form.ordering : this.form.ordering = res.data;
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
                            this.form.content_km = tinymce.get("mytextareakm").getContent();
                            this.form.content_en = tinymce.get("mytextareaen").getContent();
                            const data = this.form.value();
                            Axios({
                                url: `{{ route('admin-slider-store') }}`,
                                method: 'POST',
                                data: {
                                    ...data,
                                    id: this.dialogData?.id,
                                }
                            }).then((res) => {
                                if (res.data.error == false) {
                                    this.form.reset();
                                    this.$store.storeSlideDialog.close(true);
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });

                                    // destroy tinymce
                                    tinymce.remove();
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
                this.$store.storeSlideDialog.close();

                // destroy tinymce
                tinymce.remove();
            },
        }));
    </script>
@endcomponent
