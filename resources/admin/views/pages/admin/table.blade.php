<div class="table">
    <template x-if="table?.loading">
        @include('admin::components.progress-bar', ['top' => true]);
    </template>
    <template x-if="!table.loading && !table?.empty()">
        <div class="table-wrapper">
            <div class="table-header">
                <div class="row table-row-5">
                    <span>@lang('table.field.no')</span>
                </div>
                <div class="row table-row-10">
                    <span>@lang('table.field.profile')</span>
                </div>
                <div class="row table-row-15">
                    <span>@lang('table.field.name')</span>
                </div>
                <div class="row table-row-10">
                    <span>@lang('table.field.username')</span>
                </div>
                <div class="row table-row-20">
                    <span>@lang('table.field.email')</span>
                </div>
                <div class="row table-row-15">
                    <span>@lang('table.field.phone')</span>
                </div>
                <div class="row table-row-10">
                    <span>@lang('table.field.status')</span>
                </div>
                <div class="row table-row-10">
                    <span>@lang('table.field.created_at')</span>
                </div>
                <div class="row table-row-5">
                    <span></span>
                </div>
            </div>
            <div class="table-body">
                <template x-for="item in table.data">
                    <div class="column" x-data="{ status: item.status }">
                        <div class="row table-row-5">
                            <span x-text="item.iteration"></span>
                        </div>
                        <div class="row table-row-10">
                            <div class="image">
                                <img class="border-circle" x-bind:src="item.avatar_url"
                                    onerror="(this).src='{{ asset('images/logo/profile.png') }}'" alt="">
                            </div>
                        </div>
                        <div class="row table-row-15">
                            <span x-text="item.name"></span>
                        </div>
                        <div class="row table-row-10">
                            <span x-text="item.username"></span>
                        </div>
                        <div class="row table-row-20">
                            <span x-text="item?.email ? item?.email : '-'"></span>
                        </div>
                        <div class="row table-row-15">
                            <span x-text="item?.phone ? item?.phone : '-'"></span>
                        </div>
                        <div class="row table-row-10">
                            <template x-if="status == {{ config('dummy.status.active.key') }}">
                                <span class="text-success">{{ config('dummy.status.active.text') }}</span>
                            </template>
                            <template x-if="status == {{ config('dummy.status.disabled.key') }}">
                                <span class="text-danger">{{ config('dummy.status.disabled.text') }}</span>
                            </template>
                        </div>
                        <div class="row table-row-10">
                            <span x-text="item.created_date"></span>
                        </div>
                        <div class="row table-row-5">
                            <div x-data="{
                                open: false,
                                toggle() {
                                    if (this.open) {
                                        return this.close()
                                    }
                            
                                    this.$refs.button.focus()
                            
                                    this.open = true
                                },
                                close(focusAfter) {
                                    if (!this.open) return
                            
                                    this.open = false
                            
                                    focusAfter && focusAfter.focus()
                                }
                            }" x-on:keydown.escape.prevent.stop="close($refs.button)"
                                x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                                x-id="['dropdown-button']" class="relative dropdown">
                                <div x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                                    :aria-controls="$id('dropdown-button')" type="button" class="action-btn">
                                    <i data-feather="more-vertical"></i>
                                </div>
                                <ul x-ref="panel" x-show="open" x-transition.origin.top.right
                                    x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')"
                                    style="display: none;" class="absolute right-0 dropdown-menu">
                                    @can('admin-update')
                                        <li x-show="!item.deleted_at">
                                            <a class="dropdown-item" @click="openStoreDialog(item)">
                                                <i data-feather="edit" class="text-violet-600"></i>
                                                <span>@lang('table.option.edit')</span>
                                            </a>
                                        </li>
                                        <li x-show="!item.deleted_at">
                                            <a class="dropdown-item" @click="openChangePasswordDialog(item)">
                                                <i data-feather="key" class="text-info"></i>
                                                <span>@lang('table.option.change_password')</span>
                                            </a>
                                        </li>
                                        <li x-show="!item.deleted_at">
                                            <a class="dropdown-item" @click="setPermission(item)">
                                                <i data-feather="alert-triangle" class="text-blue-600"></i>
                                                <span>Set Permisson</span>
                                            </a>
                                        </li>
                                        <template
                                            x-if="!item.deleted_at && item.status == {{ config('dummy.status.disabled.key') }}">
                                            <li>
                                                <a class="dropdown-item" @click="onUpdateStatus(item, 1)">
                                                    <i data-feather="rotate-ccw" class="text-green-600"></i>
                                                    <span>Enable</span>
                                                </a>
                                            </li>
                                        </template>
                                        <template
                                            x-if="!item.deleted_at && item.status == {{ config('dummy.status.active.key') }}">
                                            <li>
                                                <a class="dropdown-item" @click="onUpdateStatus(item, 2)">
                                                    <i data-feather="x" class="text-orange-500"></i>
                                                    <span>Disable</span>
                                                </a>
                                            </li>
                                        </template>
                                    @endcan
                                    @can('admin-delete')
                                        <template x-if="!item.deleted_at">
                                            <li>
                                                <a class="dropdown-item" @click="onDelete(item)">
                                                    <i data-feather="trash" class="text-rose-600"></i>
                                                    <span>Delete</span>
                                                </a>
                                            </li>
                                        </template>
                                        <template x-if="item.deleted_at">
                                            <li>
                                                <a class="dropdown-item" @click="onRestore(item)">
                                                    <i data-feather="rotate-ccw" class="text-emerald-500"></i>
                                                    <span>Restore</span>
                                                </a>
                                            </li>
                                        </template>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="table-footer">
                @include('admin::components.pagination')
            </div>
        </div>
    </template>
    <template x-if="table && table?.empty()">
        @component('admin::components.empty',
            [
                'name' => __('user.empty.title'),
                'msg' => __('user.empty.description'),
            ])
        @endcomponent
    </template>
</div>
