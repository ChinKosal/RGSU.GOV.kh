<div class="sidebar-wrapper" x-data="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo/logo-2.png') }}" alt="">
    </div>
    <div class="menu-list">
        @foreach ($menu as $key => $item)
            @if (isset($item->permission) &&
                    auth()->user()->can($item->permission))
                <a class="menu-item {{ routeActive($item->active) ? 'active' : '' }}"
                    @if ($item->path && $item->children->count() == 0) @click="$link(`{!! url($item->path) !!}`)" @else @click="onClick({{ $key }}, {{ routeActive($item->active) }})" @endif
                    x-init="firstCheck({{ $key }}, {{ routeActive($item->active) }})">
                    <div class="menu-text">
                        <i data-feather="{{ $item->icon ?? 'disc' }}"></i>
                        <span>{{ json_decode($item->name)->en }}</span>
                        @if ($item->children->count() > 0)
                            <p :class="{ show: show_menu === {{ $key }} }">
                                <i data-feather="chevron-down" class="angle-icon"></i>
                            </p>
                        @endif
                    </div>
                </a>
                @if ($item->children->count() > 0)
                    <div class="sub-menu" x-show="show_menu == {{ $key }}" style="display:none"
                        x-transition:enter.duration.300ms>
                        @foreach ($item->children as $child)
                            <div class="sub-item {{ routeActive($child->active) ? 'active' : '' }}"
                                @click="$link(`{{ url($child->path) }}`)">
                                <i data-feather="disc"></i>
                                <span>{{ json_decode($child->name)->en }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</div>
<script type="module">
    Alpine.data("sidebar", () => ({
        show_menu: null,
        onClick(key, def) {
            this.show_menu != key ? this.show_menu = key : this.show_menu = null;
        },
        firstCheck(key, def) {
            if (def) {
                this.show_menu = key;
            }
        }
    }));
</script>
