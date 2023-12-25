@component('admin::components.dialog', ['dialog' => 'openStoreDialog'])
    <style>
        .tox-dialog-wrap .tox-dialog-wrap__backdrop {
            background: #75757547 !important;
        }

        .dialog {
            z-index: 110 !important;
        }

        .tox-dialog__header .tox-button.tox-button--icon.tox-button--naked {
            display: none;
        }
    </style>
    <div x-data="openStoreDialog" class="form-admin !w-[500px]">
        <form class="form-wrapper">
            <div class="form-header">
                <h3 x-show="!dialogData?.data?.id">@lang('form.header.create', ['name' => __('form.title.' . request('page'))])</h3>
                <h3 x-show="dialogData?.data?.id">@lang('form.header.update', ['name' => __('form.title.' . request('page'))])</h3>
                <span @click="$store.openStoreDialog.close()"><i data-feather="x"></i></span>
            </div>
            {{ csrf_field() }}
            <div class="form-body">
                <div class="row">
                    <div class="form-row">
                        <label>@lang('form.body.label.title_km')<span>*</span></label>
                        <textarea x-model="form.title_km" cols="30" rows="1" placeholder="@lang('form.body.placeholder.title_km')"
                            :disabled="form.disabled"></textarea>
                        <span class="error" x-show="validate?.title_km" x-text="validate?.title_km"></span>
                    </div>
                    <div class="form-row">
                        <label>@lang('form.body.label.title_en')<span>*</span></label>
                        <textarea x-model="form.title_en" cols="30" rows="1" placeholder="@lang('form.body.placeholder.title_en')"
                            :disabled="form.disabled"></textarea>
                        <span class="error" x-show="validate?.title_en" x-text="validate?.title_en"></span>
                    </div>
                    <div class="form-row">
                        <label>@lang('admin.form.status.label')<span>*</span></label>
                        <select x-model="form.status" :disabled="form.disabled">
                            @foreach (config('dummy.status') as $key => $status)
                                <option value="{{ $status['key'] }}" x-bind:selected="form.status == {{ $status['key'] }}">
                                    {{ $status['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <!-- choose file -->
                    <div class="form-row">
                        <label>@lang('form.body.label.file')</label>
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
                <div class="form-button">
                    <button type="button" color="primary" @click="onSave()" :disabled="form.disabled || loading">
                        <i data-feather="save"></i>
                        <span>@lang('category.form.button.save')</span>
                        <div class="loader" style="display: none" x-show="loading"></div>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        Alpine.data('openStoreDialog', () => ({
            form: new FormGroup({
                page: [@json(request()->page), ''],
                title_km: [null, ['required']],
                title_en: [null, ['required']],
                file: [null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),

            dialogData: null,
            baseImageUrl: "{{ asset('file_manager') }}",
            validate: null,
            loading: false,
            file_thumbnail: null,

            async init() {
                this.dialogData = this.$store.openStoreDialog.data?.data;
                this.form.patchValue(this.dialogData ?? {});
                if (this.dialogData?.content) {
                    let dataContent = JSON.parse(this.dialogData?.content);
                    this.form.patchValue({
                        title_km: JSON.parse(dataContent?.title)?.km,
                        title_en: JSON.parse(dataContent?.title)?.en,
                        file: dataContent?.file,
                    })

                    if (dataContent?.file) {
                        let file = dataContent?.file.split('.');
                        let extension = file[file.length - 1];
                        this.file_thumbnail =
                            `https://via.placeholder.com/300x300/eff3f5/4871f7?text=${extension?.toUpperCase()}`;
                    }
                }
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
                                url: `{{ route('admin-about-us-row-data-save', request('page')) }}`,
                                method: 'POST',
                                data: {
                                    ...data,
                                    id: this.dialogData?.id,
                                }
                            }).then((res) => {
                                if (res.data.error == false) {
                                    this.form.reset();
                                    this.$store.openStoreDialog.close(true);
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });
                                }
                            }).catch((e) => {
                                this.validate = e.response.data.errors;
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
