@extends('admin::shared.layout')
@section('layout')
    <div class="no-permission-container" x-data="permission">
        <div class="no-permission-wrapper">
            <div class="image">
                <img src="{!! asset('images/logo/403.gif') !!}" alt="">
            </div>
            <div class="message">
                {{-- <h1>403</h1> --}}
                <span class="title">@lang('permission.no_permission.title')</span>
                <span class="des">@lang('permission.no_permission.description')</span>
                <button @click="onSignOut">
                    <span>@lang('setting.sign_out')</span>
                </button>
            </div>
        </div>

        <script type="module">
            Alpine.data('permission', () => ({
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
@stop
