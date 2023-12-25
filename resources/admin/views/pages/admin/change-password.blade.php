@component('admin::components.dialog', ['dialog' => 'changePasswordDialog'])
    <div x-data="changePasswordDialog" class="form-admin" style="width: 400px">
        <form class="form-wrapper">
            <div class="form-header">
                <h3>
                    @lang('user.form.title.change_password') ( <span x-text="$store.changePasswordDialog.data?.username"></span> )
                </h3>
                <span @click="$store.changePasswordDialog.close()"><i data-feather="x"></i></span>
            </div>
            {{ csrf_field() }}
            <div class="form-body">
                <div class="form-row">
                    <label>@lang('user.form.new_password.label')<span>*</span> </label>
                    <input type="password" placeholder="@lang('user.form.new_password.placeholder')" x-model="form.new_password" autocomplete="off">
                    <span class="error" x-show="validate?.new_password" x-text="validate?.new_password"></span>
                </div>
                <div class="form-row">
                    <label>@lang('user.form.password_confirmation.label')<span>*</span> </label>
                    <input type="password" placeholder="@lang('user.form.password_confirmation.label')" x-model="form.confirm_new_password" autocomplete="off">
                    <span class="error" x-show="validate?.confirm_new_password"
                        x-text="validate?.confirm_new_password"></span>
                </div>
                <input type="hidden" x-model="form.id">
                <div class="form-button">
                    <button type="button" color="primary" @click="onChangePassword()" :disabled="form.disabled || loading">
                        <i data-feather="save"></i>
                        <span>@lang('user.form.button.update')</span>
                        <div class="loader" style="display: none" x-show="loading"></div>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        Alpine.data('changePasswordDialog', () => ({
            form: new FormGroup({
                id: [null, []],
                new_password: [null, ['required']],
                confirm_new_password: [null, []],
            }),
            errors: {},
            validate: {},
            loading: false,
            dialogData: null,
            init() {
                this.dialogData = this.$store.changePasswordDialog.data;
                this.changePasswordForm(this.dialogData);
            },
            changePasswordForm(data) {
                if (!data) return;
                this.form.patchValue({
                    id: data.id,
                });
            },
            onChangePassword() {
                this.$store.confirmDialog.open({
                    data: {
                        title: `@lang('dialog.title')`,
                        message: `@lang('dialog.msg.update') <b>${this.dialogData?.first_name} ${this.dialogData?.last_name}</b>?`,
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.update')",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            this.loading = true;
                            const data = this.form.value();
                            Axios({
                                url: `{{ route('admin-admin-save-password') }}`,
                                method: 'POST',
                                data: data
                            }).then((res) => {
                                if (res.data.error == false) {
                                    this.form.reset();
                                    this.$store.changePasswordDialog.close();
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });
                                }
                            }).catch((e) => {
                                this.errors = e.response.data.errors;
                                this.validate.new_password = this.errors.new_password;
                                this.validate.confirm_new_password = this.errors
                                    .confirm_new_password;
                            }).finally(() => {
                                this.loading = false;
                            });
                        }
                    }
                });
            },
        }));
    </script>
@endcomponent
