@component('admin::components.dialog', ['dialog' => 'storeDialog'])
    <div x-data="storeDialog" class="form-admin" style="width: 600px">
        <form class="form-wrapper">
            <div class="form-header">
                <h3 class="capitalize" x-show="!dialogData?.id">@lang('category.form.title.create', ['title' => request('type')])</h3>
                <h3 class="capitalize" x-show="dialogData?.id">@lang('category.form.title.update', ['title' => request('type')])</h3>
            </div>
            {{ csrf_field() }}
            <div class="form-body">
                <div class="row">
                    <div class="form-row">
                        <label>@lang('form.body.label.name_km') <span>*</span> </label>
                        <input type="text" placeholder="@lang('form.body.placeholder.name_km')" x-model="form.name_km"
                            :disabled="form.disabled" autocomplete="off">
                        <span class="error" x-show="validate?.name_km" x-text="validate?.name_km"></span>
                    </div>
                    <div class="form-row">
                        <label>@lang('form.body.label.name_en') <span>*</span> </label>
                        <input type="text" placeholder="@lang('form.body.placeholder.name_en')" x-model="form.name_en"
                            :disabled="form.disabled" autocomplete="off">
                        <span class="error" x-show="validate?.name_en" x-text="validate?.name_en"></span>
                    </div>
                </div>
                <div class="row-2">
                    <div class="form-row">
                        <label>@lang('category.form.ordering.label') <span>*</span></label>
                        <input x-mask.numeral placeholder="@lang('category.form.ordering.placeholder')" x-model="form.ordering"
                            :disabled="form.disabled" autocomplete="off" maxlength="10">
                        <span class="error" x-show="validate?.ordering" x-text="validate?.ordering"></span>
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
                <div class="form-button">
                    <button type="button" @click="$store.storeDialog.close()" color="cancel" :disabled="form.disabled">
                        <i data-feather="x"></i>
                        <span>@lang('form.button.cancel')</span>
                    </button>
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
        Alpine.data('storeDialog', () => ({
            form: new FormGroup({
                type: [@json(request()->type), ''],
                name_km: [null, ['required']],
                name_en: [null, ['required']],
                ordering: [null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),
            dialogData: null,
            validate: null,
            loading: false,
            id: null,
            locale: @json(config('dummy.locale.km')),
            km: @json(config('dummy.locale.km')),
            en: @json(config('dummy.locale.en')),

            init() {
                this.getOrdering();
                this.dialogData = this.$store.storeDialog.data?.data;

                if (this.dialogData?.id) {
                    this.form.patchValue(this.dialogData ?? {});
                    let name = JSON.parse(this.dialogData?.name);
                    this.form.patchValue({
                        name_km: name?.km,
                        name_en: name?.en,
                    });
                }
            },

            getOrdering() {
                Axios({
                    url: `{{ route('admin-category-ordering') }}`,
                    method: 'GET',
                    params: {
                        type: @json(request()->type),
                        parent_id: @json(request()->parent_id),
                    }
                }).then((res) => {
                    this.form.ordering = this.dialogData?.ordering ?? res.data;
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
                            const data = this.form.value();
                            Axios({
                                url: `{{ route('admin-category-store') }}`,
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
