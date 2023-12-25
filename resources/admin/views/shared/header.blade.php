@section('title')
    | {{ $title ?? '' }}
@stop
<div class="header" x-data="Header">
    <div class="header-wrapper">
        <div class="btn-toggle-sidebar">
            <span>{{ $header_name ?? '' }}</span>
        </div>
        <span class="right">
            <div class="profile-info">
                <img class="image" src="{{ auth()->user()->avatar_url }}" alt="">
                <span class="name">{{auth()->user()->username}}</span>
            </div>
            <div class="btn-logout" @click="onSignOut">
                <i data-feather="log-out"></i>
                <span>@lang('message.sign_out.button')</span>
            </div>
        </span>
    </div>
    <script type="module">
        Alpine.data('Header', () => ({
            onSignOut() {
                this.$store.confirmDialog.open({
                    data: {
                        title: "@lang('message.sign_out.title')",
                        message: "@lang('message.sign_out.message')",
                        btnClose: "@lang('message.close')",
                        btnSave: "@lang('message.sign_out.button')",
                    },
                    afterClosed: (res) => {
                        if (res) {
                            location.href = "{{ route('admin-sign-out') }}";
                        }
                    }
                });
            }
        }));
    </script>
</div>
