@component('admin::components.dialog', ['dialog' => 'storeDialog'])
    <div x-data="storeDialog" class="form-admin">
        <form class="form-wrapper scroll-form">
            <div class="form-header">
                <h3 x-show="!dialogData?.id">@lang('form.header.create', ['name' => __('form.title.partner')])</h3>
                <h3 x-show="dialogData?.id">@lang('form.header.update', ['name' => __('form.title.partner')])</h3>
                <span @click="$store.storeDialog.close()"><i data-feather="x"></i></span>
            </div>
            {{ csrf_field() }}
            <div class="form-body">
                <div class="form-item">
                    <div class="row-2">
                        <div class="form-row">
                            <label>Name<span>*</span></label>
                            <input type="text" x-model="form.name" :disabled="form.disabled" placeholder="Enter name">
                            <span class="error" x-show="validate?.name" x-text="validate?.name"></span>
                        </div>
                        <div class="form-row">
                            <label>Status<span>*</span></label>
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
                    <div class="row-2">
                        <div class="form-row">
                            <label>Link</label>
                            <textarea x-model="form.link" cols="30" rows="5" placeholder="Enter link url" :disabled="form.disabled"></textarea>
                            <span class="error" x-show="validate?.link" x-text="validate?.link"></span>
                        </div>
                        <div class="form-row">
                            <label>Logo<span>*</span></label>
                            <div class="form-select-photo image" @click="selectLogo(event)">
                                <div class="select-photo" :class={active:form?.logo}>
                                    <div class="icon">
                                        <i data-feather="image"></i>
                                    </div>
                                    <div class="title">
                                        <span>Choose logo</span>
                                    </div>
                                </div>
                                <template x-if="form?.logo">
                                    <div class="image-view active">
                                        <img x-bind:src="baseImageUrl + form?.logo" alt="">
                                    </div>
                                </template>
                                <input type="hidden" x-model="form.logo" autocomplete="off" role="presentation">
                            </div>
                            <span class="error" x-show="validate?.logo" x-text="validate?.logo"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-button">
                            <button type="button" @click="onSave()" color="primary">
                                <i data-feather="save"></i>
                                <span>Save</span>
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
                name: [null, ['required']],
                link: [null, []],
                logo: [null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),
            dialogData: null,
            baseImageUrl: @json(asset('file_manager')),
            validate: null,
            loading: false,
            async init() {
                this.dialogData = this.$store.storeDialog.data;
                if (this.dialogData?.id) {
                    this.form.patchValue(this.dialogData);
                }
            },
            selectLogo() {
                fileManager({
                    multiple: false,
                    afterClose: (data, basePath) => {
                        if (data?.length > 0) {
                            this.form.logo = data[0].path;
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
                                url: `{{ route('admin-partner-save') }}`,
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
                                    this.validate = null;
                                    Toast({
                                        message: res.data.message,
                                        status: 'danger',
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
