@component('admin::components.dialog', ['dialog' => 'storeDialog'])
    <div x-data="storeDialog" class="form-admin" style="width: 900px">
        <form class="form-wrapper">
            <div class="form-header">
                <h3 x-show="!dialogData?.id">
                    @lang('admin.form.title.create')
                </h3>
                <h3 x-show="dialogData?.id">
                    @lang('admin.form.title.update')
                </h3>
                <span @click="$store.storeDialog.close()"><i data-feather="x"></i></span>
            </div>
            {{ csrf_field() }}
            <div class="form-body">
                <div class="row-2">
                    <div class="form-row">
                        <label>@lang('admin.form.name.label') <span>*</span></label>
                        <input placeholder="@lang('admin.form.name.placeholder')" type="text" x-model="form.name" :disabled="form.disabled"
                            autocomplete="off">
                        <span class="error" x-show="validate?.name" x-text="validate?.name"></span>
                    </div>
                    <div class="form-row">
                        <label>@lang('admin.form.username.label')<span>*</span> </label>
                        <input type="text" placeholder="@lang('admin.form.username.placeholder')" x-model="form.username"
                            :disabled="form.disabled" autocomplete="off">
                        <span class="error" x-show="validate?.username" x-text="validate?.username"></span>
                    </div>
                </div>
                <div class="row-2">
                    <div class="form-row">
                        <label>@lang('admin.form.phone.label') <span>*</span></label>
                        <input placeholder="@lang('admin.form.phone.placeholder')" type="number" inputmode="numeric" x-model="form.phone"
                            :disabled="form.disabled" autocomplete="off">
                        <span class="error" x-show="validate?.phone" x-text="validate?.phone"></span>
                    </div>
                    <div class="form-row">
                        <label>@lang('admin.form.email.label')</label>
                        <input type="text" placeholder="@lang('admin.form.email.placeholder')" x-model="form.email" :disabled="form.disabled"
                            autocomplete="off">
                    </div>
                </div>
                <template x-if="!dialogData?.id">
                    <div class="row-2">
                        <div class="form-row">
                            <label>@lang('admin.form.password.label')<span>*</span> </label>
                            <input type="password" placeholder="@lang('admin.form.password.placeholder')" x-model="form.password"
                                :disabled="form.disabled" autocomplete="off">
                            <span class="error" x-show="validate?.password" x-text="validate?.password"></span>
                        </div>
                        <div class="form-row">
                            <label>@lang('admin.form.password_confirmation.label')<span>*</span> </label>
                            <input type="password" placeholder="@lang('admin.form.password_confirmation.placeholder')" x-model="form.password_confirmation">
                            <span class="error" x-show="validate?.password_confirmation"
                                x-text="validate?.password_confirmation"></span>
                        </div>
                    </div>
                </template>
                <div class="row-2">
                    <div class="form-row">
                        <label>@lang('admin.form.status.label')<span>*</span></label>
                        <select x-model="form.status" :disabled="form.disabled">
                            @foreach (config('dummy.status') as $key => $status)
                                <option value="{{ $status['key'] }}" x-bind:selected="form.status == {{ $status['key'] }}">
                                    {{ $status['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <label>@lang('admin.form.profile.label')</label>
                        <div class="form-select-photo image" @click="selectImage(event)" style="height: 300px;">
                            <div class="select-photo" :class={active:form?.avatar}>
                                <div class="icon">
                                    <i data-feather="image"></i>
                                </div>
                                <div class="title">
                                    <span>@lang('admin.form.profile.placeholder')</span>
                                </div>
                            </div>
                            <template x-if="form?.avatar">
                                <div class="image-view active">
                                    <img x-bind:src="baseImageUrl + form?.avatar" alt=""
                                        style="object-fit: contain">
                                </div>
                            </template>
                            <input type="hidden" x-model="form.image" autocomplete="off" role="presentation">
                        </div>
                    </div>
                </div>
                <div class="form-button">
                    <button type="button" color="primary" @click="onSave()" :disabled="form.disabled || loading">
                        <i data-feather="save"></i>
                        <span>@lang('admin.form.button.save')</span>
                        <div class="loader" style="display: none" x-show="loading"></div>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        Alpine.data('storeDialog', () => ({
            form: new FormGroup({
                name: [null, ['required']],
                username: [null, ['required']],
                phone: [null, ['required']],
                email: [null, []],
                status: [1, []],
                password: [null, ['required']],
                password_confirmation: [null, ['required']],
                avatar: [null, []],
                image: [null, []],
            }),
            dialogData: null,
            baseImageUrl: "{{ asset('file_manager') }}",
            validate: null,
            loading: false,
            init() {
                this.dialogData = this.$store.storeDialog.data;
                if (this.dialogData) {
                    this.form.patchValue(this.dialogData);
                }
            },
            selectImage() {
                fileManager({
                    multiple: false,
                    afterClose: (data, basePath) => {
                        if (data?.length > 0) {
                            this.form.avatar = data[0].path;
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
                                url: this.dialogData?.id ?
                                    `{{ route('admin-admin-update') }}` :
                                    `{{ route('admin-admin-save') }}`,
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
