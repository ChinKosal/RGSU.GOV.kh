@extends('admin::shared.layout')

@section('style')
    <link rel="stylesheet" href="{{ asset('plugin/form.css') }}">
    <style>
        .map {
            position: relative;
            width: 100%;
            height: 400px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #d8dce5;
        }

        .map::before {
            content: "Paste Google Embed map";
            color: #fff;
            text-align: center;
            padding-top: 150px;
            position: absolute;
            top: 0;
            bottom: 0;
            z-index: 1;
            background-color: rgba(51, 51, 51, 0.788);
            width: 100%;
            height: 100%;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }

        iframe {
            width: 100%;
            height: 100%;
        }
    </style>
@endsection
@section('layout')
    <div class="form-admin" x-data="form_data">
        @include('admin::shared.header', [
            'header_name' => 'Contact Us',
            'title' => 'Contact Us',
        ])
        <br>
        <div class="form-bg"></div>
        <form id="form" class="form-wrapper" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-body">
                <div class="row-2">
                    <div class="form-row">
                        <label>Footer (Khmer)</label>
                        <textarea x-model="form.footer_km" cols="30" rows="2" placeholder="Enter footer (Khmer)"></textarea>
                    </div>
                    <div class="form-row">
                        <label>Footer (English)</label>
                        <textarea x-model="form.footer_en" cols="30" rows="2" placeholder="Enter footer (English) "></textarea>
                    </div>
                </div>
                <div class="row-2">
                    <div class="form-row">
                        <label>Address (Khmer)</label>
                        <textarea x-model="form.address_km" cols="30" rows="2" placeholder="Enter address (Khmer)"></textarea>
                    </div>
                    <div class="form-row">
                        <label>Address (English)</label>
                        <textarea x-model="form.address_en" cols="30" rows="2" placeholder="Enter address (English) "></textarea>
                    </div>
                </div>
                <div class="row-4">
                    <div class="form-row col-span-2">
                        <label>E-mail</label>
                        <input type="email" x-model="form.email" :disabled="form.disabled" placeholder="Enter email">
                    </div>
                    <div class="form-row">
                        <label>Phone</label>
                        <input type="text" x-model="form.phone" :disabled="form.disabled" placeholder="Enter phone">
                    </div>
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
                <div class="row">
                    <div class="form-row">
                        <label>Location</label>
                        <div class="map" @click="onAddMap()">
                            <iframe x-bind:src="form.embed_map" width="100%" height="100%" style="border:0;"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.494548728989!2d106.6299953147693!3d10.768500992316402!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f0f0b5b0a0b%3A0x7e1b2b2b2b2b2b2b!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBQaMaw4budbmcgVGjhu6cgQ-G7kQ!5e0!3m2!1svi!2s!4v1625581000000!5m2!1svi!2s">
                            </iframe>
                        </div>
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
                page: ['contact-us', ['required']],
                email:[null, ['required']],
                phone:[null, ['required']],
                footer_km:[null, ['required']],
                footer_en:[null, ['required']],
                address_km:[null, ['required']],
                address_en:[null, ['required']],
                telegram_chat:[null, ['required']],
                donation:[null, ['required']],
                embed_map:[null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),

            id: null,
            dialogData: null,
            baseImageUrl: "{{ asset('file_manager') }}",
            validate: null,
            loading: false,

            async init() {
                await this.fetchData();
            },

            async fetchData() {
                var data = await Axios({
                    url: `{{ route('admin-setting-page-data', 'contact-us') }}`,
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
            onAddMap() {
                this.$store.addMapDialog.open({
                    data: {
                        title: "Add Map",
                    },
                    afterClosed: (result) => {
                        if (result) {
                            this.form.embed_map = result.embedLink;
                        }
                    }
                });
            },
            changeLang(lang) {
                this.lang = lang;
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
