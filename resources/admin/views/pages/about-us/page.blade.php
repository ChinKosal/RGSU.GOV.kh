@extends('admin::shared.layout')
@section('style')
    <link rel="stylesheet" href="{{ asset('plugin/form.css') }}">
@endsection
@section('layout')
    <div class="form-admin" x-data="formPage">
        @include('admin::shared.header', [
            'title' => __('form.title.about_us.' . request('page')),
            'header_name' => __('form.name.about_us.' . request('page')),
        ])
        <br>
        <div class="form-bg"></div>
        <form id="form" class="form-wrapper" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-body">
                <div class="row-4">
                    <div class="form-row col-span-2" x-show="locale == km">
                        <label>@lang('form.body.label.title_km')<span>*</span></label>
                        <textarea x-model="form.title_km" :disabled="form.disabled" cols="10" rows="1"
                            placeholder="@lang('form.body.placeholder.title_km')"></textarea>
                        <span class="error" x-show="validate?.title_km" x-text="validate?.title_km"></span>
                    </div>
                    <div class="form-row col-span-2" x-show="locale == en">
                        <label>@lang('form.body.label.title_en')<span>*</span></label>
                        <textarea x-model="form.title_en" :disabled="form.disabled" cols="10" rows="1"
                            placeholder="@lang('form.body.placeholder.title_en')"></textarea>
                        <span class="error" x-show="validate?.title_en" x-text="validate?.title_en"></span>
                    </div>
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
                    <div class="form-header !p-0 !text-sm !flex items-center !justify-end">
                        @include('admin::components.form-change-language')
                    </div>
                </div>
                <div class="row">
                    <div class="form-row" x-show="locale == km">
                        <label>@lang('form.body.label.content_km')<span>*</span></label>
                        <textarea id="mytextareakm" rows="24" class="!h-[400px]" placeholder="@lang('form.body.placeholder.content_km')" x-model="form.content_km"></textarea>
                        <span class="error" x-show="validate?.content_km" x-text="validate?.content_km"></span>
                    </div>
                    <div class="form-row" x-show="locale == en">
                        <label>@lang('form.body.label.content_en')<span>*</span></label>
                        <textarea id="mytextareaen" rows="24" class="h-[500px]" placeholder="@lang('form.body.placeholder.content_en')" x-model="form.content_en"></textarea>
                        <span class="error" x-show="validate?.content_en" x-text="validate?.content_en"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-row">
                        <label>Gallery</label>
                        <div class="galleryLayoutGp">
                            <div class="galleryGp grid-7">
                                <template x-if="galleries.length > 0">
                                    <template x-for="(item,index) in galleries">
                                        <div class="imageItem">
                                            <img :src="item?.thumbnail"
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
                page: [@json(request('page')), ['required']],
                title_km: [null, ['required']],
                title_en: [null, ['required']],
                content_km: [null, ['required']],
                content_en: [null, ['required']],
                gallery: [null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),

            id: null,
            validate: null,
            loading: false,
            data: null,
            locale: @json(config('dummy.locale.km')),
            km: @json(config('dummy.locale.km')),
            en: @json(config('dummy.locale.en')),
            galleries: [],

            async init() {
                await this.fetchData();
                await this.initTinymce();
            },
            async fetchData(){
                this.loading = true;
                this.data = await Axios({
                    url: `{{ route('admin-about-us-data') }}`,
                    method: 'GET',
                    params: {
                        category: this.form.category,
                        page: this.form.page,
                    }
                }).then((res)=> res.data);

                if(this.data && this.data.id){
                    this.id = this.data.id;
                    this.form.patchValue(this.data);
                    let title = JSON.parse(this.data?.title) ?? null;
                    let content = JSON.parse(this.data?.content) ?? null;
                    this.form.patchValue({
                        title_km: title?.[this.km] ?? null,
                        title_en: title?.[this.en] ?? null,
                        content_km: content?.[this.km] ?? null,
                        content_en: content?.[this.en] ?? null,
                    });

                    let gallery = JSON.parse(this.data?.content)?.gallery ?? null;
                    this.galleries = JSON.parse(gallery) ?? [];
                }

                this.loading = false;
            },
            async initTinymce() {
                await tinymce.remove();
                await tinymce.init({
                    selector: 'textarea#mytextareaen,textarea#mytextareakm',
                    content_style: 'body { font-family:Inter, sans-serif, Poppins, sans-serif, Hanuman, serif; font-size:14px }',
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
                    },
                });
            },
            selectImageGallery() {
                fileManager({
                    multiple: true,
                    afterClose: (data, basePath) => {
                        if (data?.length > 0) {
                            data.map((item) => {
                                this.galleries.push({
                                    path: item?.path,
                                    thumbnail: `https://via.placeholder.com/300x300/eff3f5/4871f7?text=${item?.extension?.toUpperCase()}`,
                                });
                                this.form.gallery = this.galleries;
                            });
                        }
                    }
                })
            },
            imageDelete(index) {
                this.galleries.splice(index, 1);
                this.form.gallery = this.galleries;
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
                            this.form.content_km = tinymce.get('mytextareakm').getContent();
                            this.form.content_en = tinymce.get('mytextareaen').getContent();
                            const data = this.form.value();
                            Axios({
                                url: `{{ route('admin-about-us-store') }}`,
                                method: 'POST',
                                data: {
                                    ...data,
                                    id: this.id,
                                    category_id: this.form.category,
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
