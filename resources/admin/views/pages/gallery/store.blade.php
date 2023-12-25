@extends('admin::shared.layout')
@section('style')
    <link rel="stylesheet" href="{{ asset('plugin/form.css') }}">
@endsection
@section('layout')
    <div class="form-admin" x-data="formPage">
        @include('admin::shared.header', [
            'title'         => __('form.title.gallery'),
            'header_name'   => __('form.name.gallery'),
        ])
        <br>
        <div class="form-bg"></div>
        <form id="form" class="form-wrapper" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-body">
                <div class="row-4">
                    <div class="form-row">
                        <label>@lang('form.body.label.status')<span>*</span></label>
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
                    <div class="form-row">
                        <label>Gallery<span>*</span></label>
                        <div class="galleryLayoutGp">
                            <div class="galleryGp grid-7">
                                <template x-if="galleries.length > 0">
                                    <template x-for="(item,index) in galleries">
                                        <div class="imageItem">
                                            <img :src="baseImageUrl + item"
                                                onerror="(this).src='{{ asset('images/logo/default.png') }}'"
                                                alt="">
                                            <input type="hidden" x-model="form.gallery" autocomplete="off"
                                                role="presentation">
                                            <div class="delete" @click="imageDelete(index)">
                                                <span>-</span>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                                <div class="imageItem add" @click="selectImageGallery">
                                    <i data-feather="plus"></i>
                                </div>
                            </div>
                        </div>
                        <span class="error" x-show="validate?.gallery" x-text="validate?.gallery"></span>
                    </div>
                    <div class="form-button">
                        <button type="button" @click="onSave()" color="primary">
                            <i data-feather="save"></i>
                            <span>Save</span>
                            <div class="loader" style="display: none" x-show="loading"></div>
                        </button>
                    </div>
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
                gallery: [null, []],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),

            id: null,
            validate: null,
            loading: false,
            data: null,
            galleries: [],
            baseImageUrl: "{{ asset('file_manager') }}",

            async init() {
                await this.fetchData();
            },
            async fetchData(){
                this.loading = true;
                this.data = await Axios({
                    url: `{{ route('admin-gallery-data') }}`,
                    method: 'GET',
                }).then((res)=> res.data);

                if(this.data && this.data.id){
                    this.id = this.data.id;
                    this.form.patchValue(this.data);
                    if (this.data?.gallery) {
                        this.galleries = this.data?.gallery;
                        this.form.gallery = this.galleries ?? null;
                    }
                }

                this.loading = false;
            },
            selectImageGallery() {
                fileManager({
                    multiple: true,
                    afterClose: (data, basePath) => {
                        if (data?.length > 0) {
                            data.map((item) => {
                                this.galleries.push(item.path);
                                this.form.gallery = this.galleries;
                            });
                        }
                    }
                })
            },
            imageDelete(index) {
                this.galleries.splice(index, 1);
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
                                url: `{{ route('admin-gallery-store') }}`,
                                method: 'POST',
                                data: {
                                    ...data,
                                    id: this.id,
                                }
                            }).then((res) => {
                                if (res.data.error == false) {
                                    this.validate = null;
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });
                                    this.fetchData();
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
            }
        }));
    </script>
@endsection
