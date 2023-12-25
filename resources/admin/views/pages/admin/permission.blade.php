@component('admin::components.dialog', ['dialog' => 'permissionDialog'])
    <div x-data="permissionDialog" class="form-admin" style="width: 800px">
        <form class="form-wrapper">
            <div class="form-header">
                <h3 x-show="!dialogData?.id">
                    @lang('admin.form.title.create')
                </h3>
                <h3 x-show="dialogData?.id">
                    @lang('admin.form.title.update')
                </h3>
                <span @click="$store.permissionDialog.close()"><i data-feather="x"></i></span>
            </div>
            {{ csrf_field() }}
            <div class="form-body">
                <div class="row">
                    <div class="permissionLayoutGp">
                        <h3><span>Set Permission To</span>&nbsp;:&nbsp;<span
                                x-text="$store?.userPermission?.data?.full_name"></span></h3>
                        <div class="headerListPermission">
                            <label class="titlePer">Permission Listing<span></span></label>
                            <label for="chk-permissionSelectAll" class="permissionListCheckall">
                                <span>Select All</span>
                                <input type="checkbox" id="chk-permissionSelectAll" class="chk-permissionSelectAll" />
                            </label>
                        </div>
                        <template x-for="(modulParent,index) in ModulPermission">
                            <div class="permissionControl">
                                <label class="parentLabel" x-text="modulParent?.name"></label>
                                <div class="permissionLayout">
                                    <div class="permissionItem">
                                        <div class="permissionHeader arrowPermission">
                                            <i data-feather="chevron-down"></i>
                                            <div class="textItem" x-text="'All Option'">
                                            </div>
                                            <label class="form-check-label" :for="'chk-permission-group-'.modulParent?.id">
                                                <div class="inputItem">
                                                    <input type="checkbox" :id="'chk-permission-group-' + modulParent?.id"
                                                        :data-permission-id="modulParent?.id"
                                                        class="role_permission permissionAllitem chk-permission-group"
                                                        :class="'chk-permission-group-' + modulParent?.id"
                                                        :checked="modulParent?.check" />
                                                </div>
                                            </label>
                                        </div>
                                        <div class="permissionListItemGpCh">
                                            <template x-for="(action,key) in modulParent?.permission">
                                                <label class="permissionItemCh" :for="'permission' + action?.name">
                                                    <i data-feather="disc"></i>
                                                    <div class="textItem" x-text="action?.name"></div>
                                                    <div class="inputItem">
                                                        <input type="checkbox" :value="action?.name"
                                                            class="permissionAllitem"
                                                            :class="'permission-item-' + modulParent?.id"
                                                            :id="'permission' + action?.name"
                                                            :data-permission-id="modulParent?.id" name="permission"
                                                            :checked="action?.check" />
                                                    </div>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="form-button btnPermissionActinGp">
                        <button type="button" color="primary" @click="SubmitData()" :disabled="loading">
                            <i data-feather="save"></i>
                            <span>@lang('admin.form.button.save')</span>
                            <div class="loaderCus" style="display: none" x-show="loading"></div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        Alpine.data('permissionDialog', () => ({
            ModulPermission: [],
            form: {
                permission: []
            },
            dialogData: null,
            baseImageUrl: "{{ asset('file_manager') }}",
            validate: null,
            loading: false,
            async init() {
                const {
                    id
                } = this.$store.permissionDialog.data;

                await this.fetchDataSeleted(`/admin/admin/user-permission?id=${id}`, (res) => {
                    return this.ModulPermission = res.ModulePermission;
                });

                var data = [];
                feather.replace();
                let arrow = document.querySelectorAll('.arrowPermission');
                for (var i = 0; i < arrow.length; i++) {
                    arrow[i].addEventListener('click', (e) => {
                        let arrowParent = e.target.parentElement.parentElement;
                        arrowParent.classList.toggle('showMenu');
                    });
                }
                $('.chk-permission-group').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                });
                $('.chk-permissionSelectAll').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                });

                //checkAll
                $('.chk-permissionSelectAll').on('ifChecked', function() {
                    $('.permissionAllitem').each(function() {
                        $(this).iCheck('check');
                    });
                });

                $('.chk-permissionSelectAll').on('ifUnchecked', function() {
                    $('.permissionAllitem').each(function() {
                        $(this).iCheck('uncheck');
                    });
                });
                //endCheckAll

                //checkByGroup
                $('.chk-permission-group').on('ifChecked', function() {
                    $('.permission-item-' + $(this).attr('data-permission-id')).each(function() {
                        $(this).iCheck('check');
                    });
                });

                $('.chk-permission-group').on('ifUnchecked', function() {
                    $('.permission-item-' + $(this).attr('data-permission-id')).each(function() {
                        $(this).iCheck('uncheck');
                    });
                });
                //endCheckBYGroup
            },
            async fetchDataSeleted(url, callback) {
                await fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                        }
                    })
                    .then(async (res) => {
                        let data = await res.json();
                        if (data) {
                            callback(data);
                        }
                    })
                    .catch(() => {})
                    .finally(() => {});
            },
            async SubmitData() {
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('dialog.title')",
                        message: "@lang('dialog.msg.save')",
                        btnClose: "@lang('dialog.button.close')",
                        btnSave: "@lang('dialog.button.save')",
                    },
                    afterClosed: async (result) => {
                        if (result) {
                            this.loading = true;
                            var permission = [];
                            const {
                                id
                            } = this.$store.permissionDialog.data;

                            await $.each($("input[name='permission']:checked"), function() {
                                permission.push($(this).val());
                            });

                            await Axios({
                                url: `{{ route('admin-admin-user-permission-save') }}`,
                                method: 'POST',
                                data: {
                                    id: id,
                                    permission: permission
                                }
                            }).then((res) => {
                                if (res.data.error == false) {
                                    this.$store.permissionDialog.close();
                                    Toast({
                                        message: res.data.message,
                                        status: res.data.status,
                                        size: 'small',
                                    });
                                }
                            }).catch((e) => {
                                this.validate = e.response.data.errors;
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
