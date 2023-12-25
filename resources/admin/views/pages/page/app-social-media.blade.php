@extends('admin::shared.layout')
@section('style')
    <link rel="stylesheet" href="{{ asset('plugin/form.css') }}">
@endsection
@section('layout')
    <div class="form-admin" x-data="form_data">
        @include('admin::shared.header', [
            'header_name' => 'App Social Media',
            'title' => 'App Social Media',
        ])
        <br>
        <div class="form-bg"></div>
        <form id="form" class="form-wrapper" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-body">
                <div class="row-2">
                    <div class="form-row">
                        <label>Facebook</label>
                        <input type="text" x-model="form.facebook" :disabled="form.disabled"
                            placeholder="Enter facebook link">
                    </div>
                    <div class="form-row">
                        <label>Twitter</label>
                        <input type="text" x-model="form.twitter" :disabled="form.disabled"
                            placeholder="Enter twitter link">
                    </div>
                </div>
                <div class="row-2">
                    <div class="form-row">
                        <label>Instagram</label>
                        <input type="text" x-model="form.instagram" :disabled="form.disabled"
                            placeholder="Enter instagram link">
                    </div>
                    <div class="form-row">
                        <label>Telegram</label>
                        <input type="text" x-model="form.telegram" :disabled="form.disabled"
                            placeholder="Enter telegram link">
                    </div>
                </div>
                <div class="row-2">
                    <div class="form-row">
                        <label>Youtube</label>
                        <input type="text" x-model="form.youtube" :disabled="form.disabled"
                            placeholder="Enter youtube link">
                    </div>
                    <div class="form-row">
                        <label>Website</label>
                        <input type="text" x-model="form.website" :disabled="form.disabled"
                            placeholder="Enter website link">
                    </div>
                </div>
                <div class="row-3">
                    <div class="form-row">
                        <label>Status<span>*</span></label>
                        <select x-model="form.status" :disabled="form.disabled">
                            @foreach (config('dummy.status') as $key => $status)
                                <option value="{{ $status['key'] }}" x-bind:selected="form.status == {{ $status['key'] }}">
                                    {{ $status['text'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-button">
                    <button type="button" @click="onSave()" color="primary">
                        <i data-feather="save"></i>
                        <span>Save</span>
                        <div class="loader" style="display: none" x-show="loading"></div>
                    </button>
                </div>
            </div>
            <div class="form-footer"></div>
        </form>
    </div>
    @include('admin::file-manager.popup')
@stop
@section('script')
    <script type="module">
        Alpine.data('form_data', () => ({
            form: new FormGroup({
                page: ['app-social-media', ['required']],
                facebook: [null, ['required']],
                twitter: [null, ['required']],
                instagram: [null, ['required']],
                telegram: [null, ['required']],
                youtube: [null, ['required']],
                website: [null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),

            id: null,
            dialogData: null,
            validate: null,
            loading: false,

            async init() {
                await this.fetchData();
            },

            async fetchData() {
                var data = await Axios({
                    url: `{{ route('admin-setting-page-data', 'app-social-media') }}`,
                    method: 'GET',
                }).then((res) => res.data);

                if (data && data.id) {
                    const content = JSON.parse(data?.content);
                    this.id = data.id;
                    this.form.patchValue(content);
                    this.form.page      = data.page;
                    this.form.status    = data.status;
                }
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
                                url: `{{ route('admin-setting-save-contact') }}`,
                                method: 'POST',
                                data: {
                                    ...data,
                                    id: this.id,
                                }
                            }).then((res) => {
                                if (res.data.error == false) {
                                    this.form.reset();
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });
                                    this.fetchData();
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
@endsection
