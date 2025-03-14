@extends($activeTemplate . 'layouts.app')
@php
    $registerContent = getContent('register.content', true);
    $policyPages = getContent('policy_pages.element', false, null, true);
@endphp

@section('app')

    @if (gs('registration'))
        <section class="account-section ">
            <div class="account__right bg_img" style="background: url({{ getImage('assets/images/frontend/register/' . @$registerContent->data_values->image, '1920x1080') }}) center;">
            </div>
            <div class="account-left sign-up">
                <div class="account__header">
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ siteLogo() }}" alt="@lang('logo')">
                    </a>
                    <h2 class="account__header-title">{{ trans_case($registerContent?->data_values->heading) }}</h2>
                    <p>{{ trans_case($registerContent?->data_values->subheading) }}</p>
                </div>

                @include($activeTemplate . 'partials.social_login')

                <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('First Name')</label>
                            <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Last Name')</label>
                            <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">@lang('E-Mail Address')</label>
                                <input type="email" class="form-control form--control checkUser" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control" name="password_confirmation" required>
                            </div>
                        </div>

                        <x-captcha />
                    </div>
                    @if (gs('agree'))
                        @php
                            $policyPages = getContent('policy_pages.element', false, orderById: true);
                        @endphp
                        <div class="form-group">
                            <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                            <label for="agree">@lang('I agree with')</label> <span>
                                @foreach ($policyPages as $policy)
                                    <a href="{{ route('policy.pages', $policy->slug) }}" target="_blank">{{ trans_case($policy->data_values->title) }}</a>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    @endif
                    <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Register')</button>
                    <p class="mb-0">@lang('Already have an account?')
                        <a href="{{ route('user.login') }}" class="text--base">@lang('Login')</a>
                    </p>
                </form>
            </div>
        </section>
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif

    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base  btn--sm">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
@push('script')
    <script>
        "use strict";
        (function($) {

            @if (gs('secure_password'))
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



            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .agree::after {
            display: none
        }

        .register-disable {
            height: 100vh;
            width: 100%;
            background-color: #fff;
            color: black;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-disable-image {
            max-width: 300px;
            width: 100%;
            margin: 0 auto 32px;
        }

        .register-disable-title {
            color: rgb(0 0 0 / 80%);
            font-size: 42px;
            margin-bottom: 18px;
            text-align: center
        }

        .register-disable-icon {
            font-size: 16px;
            background: rgb(255, 15, 15, .07);
            color: rgb(255, 15, 15, .8);
            border-radius: 3px;
            padding: 6px;
            margin-right: 4px;
        }

        .register-disable-desc {
            color: rgb(0 0 0 / 50%);
            font-size: 18px;
            max-width: 565px;
            width: 100%;
            margin: 0 auto 32px;
            text-align: center;
        }

        .register-disable-footer-link {
            color: #fff;
            background-color: #5B28FF;
            padding: 13px 24px;
            border-radius: 6px;
            text-decoration: none
        }

        .register-disable-footer-link:hover {
            background-color: #440ef4;
            color: #fff;
        }
    </style>
@endpush
