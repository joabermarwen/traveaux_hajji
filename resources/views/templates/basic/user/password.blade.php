@extends($activeTemplate . 'layouts.master')

@section('panel')
    <div class="contact__form__wrapper">
        <form action="" method="post">
            @csrf
            <div class="form-group">
                <label class="form-label">@lang('Current Password')</label>
                <input type="password" class="form-control form--control" name="current_password" required autocomplete="current-password">
            </div>
            <div class="form-group">
                <label class="form-label">@lang('Password')</label>
                <input type="password" class="form-control form--control" name="password" required autocomplete="current-password">
                @if (gs("secure_password"))
                    <div class="input-popup">
                        <p class="error lower">@lang('1 small letter minimum')</p>
                        <p class="error capital">@lang('1 capital letter minimum')</p>
                        <p class="error number">@lang('1 number minimum')</p>
                        <p class="error special">@lang('1 special character minimum')</p>
                        <p class="error minimum">@lang('6 character password')</p>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-label">@lang('Confirm Password')</label>
                <input type="password" class="form-control form--control" name="password_confirmation" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
        </form>
    </div>
@endsection
@if (gs("secure_password"))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
@push('script')
    <script>
        "use strict";
        (function($) {

            @if (gs("secure_password"))
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });
                $('[name=password]').focus(function() {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });
                $('[name=password]').focusout(function() {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif

        })(jQuery);
    </script>
@endpush
