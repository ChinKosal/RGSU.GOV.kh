@extends('admin::shared.layout')
@section('style')
    <link rel="stylesheet" href="{{ asset('plugin/form.css') }}">
@endsection
@section('layout')
    <div class="form-admin" x-data="formPage">
        @include('admin::shared.header', [
            'title' => ucfirst($page),
            'header_name' => ucfirst($page),
        ])
        <br>
        <div class="form-bg"></div>
        <form id="form" class="form-wrapper" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" x-model="form.page" :disabled="form.disabled" />
            <div class="form-body">
                <div class="row-2">
                    <div class="form-row">
                        <label>Status<span>*</span></label>
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
                <div class="row">
                    <div class="form-row textarea-form">
                        <label>Content<span>*</span></label>
                        <textarea id="mytextarea" rows="28" placeholder="Enter description" x-model="form.content"></textarea>
                        <span class="error" x-show="validate?.content" x-text="validate?.content"></span>
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
@stop
@section('script')
    <script src="{{ asset('plugin/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script type="module">
        Alpine.data('formPage', () => ({
            form: new FormGroup({
                page: [null, ['required']],
                content: [null, []],
                status: ['{{ config('dummy.status.active.key') }}', ['required']],
            }),
            id: null,
            validate: null,
            loading: false,
            async init() {
                await this.fetchData();
                await this.initTinymce();

                this.form.patchValue({
                    page: '{{ $page }}',
                });
            },

            async fetchData(){
                const data = await Axios({
                    url: `{{ route('admin-setting-page-data', $page) }}`,
                    method: 'GET',
                }).then((res)=> res.data);

                if(data){
                    this.id = data.id;
                    this.form.patchValue(data);
                    if (data.content) {
                        this.form.content = JSON.parse(data?.content)?.en || null;
                    }
                }
            },
            async initTinymce(){
                await tinymce.remove();
                await tinymce.init({
                    selector: 'textarea#mytextarea, textarea#why-choose-us',
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
                                        if (result && result.length > 0) {
                                            result.map((file) => {
                                                const img = editor.dom
                                                    .createHTML(
                                                        'img', {
                                                            src: basePath +
                                                                file
                                                                .path,
                                                            style: 'width:100% !important;'
                                                        });
                                                editor.insertContent(img);
                                            });
                                        }
                                    }
                                })
                            }
                        });
                    },
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
                            this.form.content = tinymce.get("mytextarea").getContent();
                            const data = this.form.value();
                            Axios({
                                url: `{{ route('admin-setting-page-update') }}`,
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
