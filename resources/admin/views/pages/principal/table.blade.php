<div class="table">
    <template x-if="table?.loading">
        @include('admin::components.progress-bar', ['top' => true]);
    </template>
    <template x-if="!table.loading && !table?.empty()">
        <div class="table-wrapper">
            <div class="table-header">
                <div class="row table-row-5">
                    <input class="form-check-input cursor-pointer" type="checkbox" value=""
                        @change="checkAll($event.target, table.data)" />
                </div>
                <div class="row table-row-5">
                    <span>@lang('table.field.no')</span>
                </div>
                <div class="row table-row-20">
                    <span>@lang('table.field.category')</span>
                </div>
                <div class="row table-row-25">
                    <span>@lang('table.field.title_km')</span>
                </div>
                <div class="row table-row-25">
                    <span>@lang('table.field.title_en')</span>
                </div>
                <div class="row table-row-15">
                    <span>@lang('table.field.status')</span>
                </div>
                <div class="row table-row-5">
                    <span></span>
                </div>
            </div>
            <div class="table-body">
                <template x-for="item in table.data">
                    <div class="column" x-data="{ status: item.status }">
                        <div class="row table-row-5">
                            <input class="form-check-input cursor-pointer product-checkbox" type="checkbox"
                                value="" @change="selectProduct($event.target, item.id)" />
                        </div>
                        <div class="row table-row-5">
                            <span x-text="item.iteration"></span>
                        </div>
                        <div class="row table-row-20">
                            <template x-if="item?.category.length > 0">
                                <template x-for="category in item.category.slice(0, 1)">
                                    <span x-text="JSON.parse(category.name).en"></span>
                                </template>
                            </template>
                            <template x-if="item?.category.length > 1">
                                <span class="text-gray-500" x-text="'+' + (item.category.length - 1)"></span>
                            </template>
                            <template x-if="item?.category.length == 0">
                                <span>--</span>
                            </template>
                        </div>
                        <div class="row table-row-25">
                            <span x-text="JSON.parse(item.title).km || '--'"></span>
                        </div>
                        <div class="row table-row-25">
                            <span x-text="JSON.parse(item.title).en || '--'"></span>
                        </div>
                        <div class="row table-row-15">
                            <template x-if="status == active">
                                <span class="text-green-600">{{ config('dummy.status.active.text') }}</span>
                            </template>
                            <template x-if="status == disabled">
                                <span class="text-rose-600">{{ config('dummy.status.disabled.text') }}</span>
                            </template>
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
                                    @can('principal-recipient-update')
                                        <template x-if="item.status == active && !item.deleted_at">
                                            <li>
                                                <a class="dropdown-item" @click="openStoreDialog(item)">
                                                    <i data-feather="edit" class="text-violet-600"></i>
                                                    <span>@lang('table.option.edit')</span>
                                                </a>
                                            </li>
                                        </template>
                                        <template x-if="!item.deleted_at && item.status == active">
                                            <li>
                                                <a class="dropdown-item" @click="onUpdateOption(item, disabled)">
                                                    <i data-feather="alert-triangle" class="text-rose-600"></i>
                                                    <span>@lang('table.option.hidden')</span>
                                                </a>
                                            </li>
                                        </template>
                                        <template x-if="!item.deleted_at && item.status == disabled">
                                            <li>
                                                <a class="dropdown-item" @click="onUpdateOption(item, active)">
                                                    <i data-feather="eye" class="text-emerald-500"></i>
                                                    <span>@lang('table.option.show')</span>
                                                </a>
                                            </li>
                                        </template>
                                    @endcan
                                    @can('principal-recipient-delete')
                                        <template x-if="!item.deleted_at">
                                            <li>
                                                <a class="dropdown-item" @click="onDelete(item)">
                                                    <i data-feather="trash" class="text-rose-600"></i>
                                                    <span>@lang('table.option.move_to_trash')</span>
                                                </a>
                                            </li>
                                        </template>
                                        <template x-if="item.deleted_at">
                                            <section>
                                                <li>
                                                    <a class="dropdown-item" @click="onRestore(item)">
                                                        <i data-feather="rotate-ccw" class="text-success"></i>
                                                        <span>@lang('table.option.restore')</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item danger" @click="onDestroy(item)">
                                                        <i data-feather="trash" class="text-rose-600"></i>
                                                        <span>@lang('table.option.delete')</span>
                                                    </a>
                                                </li>
                                            </section>
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
        @component('admin::components.empty', [
            'name' => __('table.empty.title', ['name' => __('form.title.' . request('type'))]),
            'msg' => __('table.empty.message', ['name' => __('form.title.' . request('type'))]),
        ])
        @endcomponent
    </template>
</div>
