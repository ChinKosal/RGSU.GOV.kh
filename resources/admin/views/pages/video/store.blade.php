@component('admin::components.dialog', ['dialog' => 'storeDialog'])
    <div x-data="storeDialog" class="form-admin">
        <form class="form-wrapper scroll-form">
            <div class="form-header">
                <h3 x-show="!dialogData?.id">@lang('form.header.create', ['name' => __('form.title.video')])</h3>
                <h3 x-show="dialogData?.id">@lang('form.header.update', ['name' => __('form.title.video')])</h3>
                <span @click="$store.storeDialog.close()"><i data-feather="x"></i></span>
            </div>
            {{ csrf_field() }}
            <div class="form-body">
                <div class="form-item">
                    <div class="row-2">
                        <div class="form-row">
                            <label>@lang('form.body.label.title_km')<span>*</span></label>
                            <textarea x-model="form.title_km" :disabled="form.disabled" cols="10" rows="2"
                                placeholder="@lang('form.body.placeholder.title_km')"></textarea>
                            <span class="error" x-show="validate?.title_km" x-text="validate?.title_km"></span>
                        </div>
                        <div class="form-row">
                            <label>@lang('form.body.label.title_en')<span>*</span></label>
                            <textarea x-model="form.title_en" :disabled="form.disabled" cols="10" rows="2"
                                placeholder="@lang('form.body.placeholder.title_en')"></textarea>
                            <span class="error" x-show="validate?.title_en" x-text="validate?.title_en"></span>
                        </div>
                    </div>
                    <div class="row-2">
                        <div class="form-row">
                            <label>@lang('form.body.label.video')</label>
                            <textarea x-model="form.full_url" :disabled="form.disabled" cols="10" rows="3"
                                placeholder="@lang('form.body.placeholder.video')"></textarea>
                            <span class="error" x-show="validate?.full_url" x-text="validate?.full_url"></span>
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
                    <div class="row">
                        <div class="form-row" x-show="form.video_id">
                            <label>@lang('form.body.label.video_preview')</label>
                            <div class="preview w-full"></div>
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
                title_km: [null, ['required']],
                title_en: [null, ['required']],
                full_url: [null, ['required']],
                video_id: [null, ['required']],
                thumbnail: [null, ['required']],
                status: [@json(config('dummy.status.active.key')), ['required']],
            }),
            dialogData: null,
            baseImageUrl: @json(asset('file_manager')),
            validate: null,
            loading: false,
            locale: @json(config('dummy.locale.km')),
            km: @json(config('dummy.locale.km')),
            en: @json(config('dummy.locale.en')),

            async init() {
                this.dialogData = this.$store.storeDialog.data;
                if (this.dialogData?.id) {
                    this.form.patchValue(this.dialogData);
                    this.form.patchValue({
                        title_km: JSON.parse(this.dialogData.title).km,
                        title_en: JSON.parse(this.dialogData.title).en,
                    })

                    if (this.dialogData?.full_url) {
                        this.onPreview(this.dialogData?.full_url);
                    }
                }

                this.$watch('form.full_url', (value) => {
                    value ? this.onPreview(value) : this.form.video_id = null;
                });
            },
            getVideoUrl(url) {
                // if url is match with youtube url pattern and return video id
                var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;

                // if url is match with youtube embed iframe pattern and return video id
                var regYotubeEmbed = /<iframe.*?src="(.*?)"[^>]*><\/iframe>/;

                var match = url.match(regExp);
                var result = '';

                if (match && match[2].length == 11) {
                    result = match[2];
                } else if (url.match(regYotubeEmbed)) {
                    result = url.match(regYotubeEmbed)[1].split('/')[4];
                } else {
                    result = url;
                }

                return result;
            },
            onPreview(url) {
                const result = this.getVideoUrl(url);
                let frameVideo = '';
                if (result.length == 11) {
                    frameVideo =
                        `<iframe src="https://www.youtube.com/embed/${result}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                } else {
                    frameVideo =
                        `<iframe src="https://www.facebook.com/plugins/video.php?height=315&href=${result}&show_text=false&width=560&t=0" width="560" height="315" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>`;
                }

                this.$el.querySelector('.preview').innerHTML = frameVideo;

                this.form.patchValue({
                    video_id: `${result}`,
                    thumbnail: result.length == 11 ? `https://img.youtube.com/vi/${result}/0.jpg` :
                        null,
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
                                url: `{{ route('admin-video-save') }}`,
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
