@extends($activeTemplate . 'layouts.master')
@section('panel')
    <div class="dashboard__content contact__form__wrapper">
        <div class="profile__edit__wrapper">
            <div class="profile__edit__form">
                <form class="register" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <x-image-uploader
                                name="image"
                                :imagePath="getImage(getFilePath('userProfile') . '/' . $user->image,getFileSize('userProfile'),true)"
                                :size="false"
                                class="w-100"
                                id="uploadImage"
                                :required="false" />
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label class="form-label">@lang('First Name')</label>
                                    <input type="text" class="form-control form--control" name="firstname"
                                        value="{{ $user->firstname }}" required>
                                </div>
                                <div class="form-group  col-sm-6">
                                    <label class="form-label">@lang('Last Name')</label>
                                    <input type="text" class="form-control form--control" name="lastname"
                                        value="{{ $user->lastname }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label class="form-label">@lang('E-mail Address')</label>
                                    <input class="form-control form--control" value="{{ $user->email }}" disabled>
                                </div>
                                <div class="form-group  col-sm-6">
                                    <label class="form-label">@lang('Mobile Number')</label>
                                    <input class="form-control form--control" value="{{ $user->mobile }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label class="form-label">@lang('Address')</label>
                                    <input type="text" class="form-control form--control" name="address"
                                        value="{{ @$user->address }}">
                                </div>
                                <div class="form-group  col-sm-6">
                                    <label class="form-label">@lang('State')</label>
                                    <input type="text" class="form-control form--control" name="state"
                                        value="{{ @$user->state }}">
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group  col-sm-4">
                                    <label class="form-label">@lang('Zip Code')</label>
                                    <input type="text" class="form-control form--control" name="zip"
                                        value="{{ @$user->zip }}">
                                </div>

                                <div class="form-group  col-sm-4">
                                    <label class="form-label">@lang('City')</label>
                                    <input type="text" class="form-control form--control" name="city"
                                        value="{{ @$user->city }}">
                                </div>

                                <div class="form-group  col-sm-4">
                                    <label class="form-label">@lang('Country')</label>
                                    <input class="form-control form--control" value="{{ @$user->country_name }}" disabled>
                                </div>

                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .image-upload-wrapper {
            height: 280px;
            position: relative;
        }

        .image-upload-preview {
            width: 100%;
            height: 100%;
            display: block;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            border-radius: 10px;
            border: 3px solid #f1f1f1;
            box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25);
        }

        .image-upload-input-wrapper {
            position: absolute;
            display: inline-flex;
            bottom: -14px;
            right: -7px;
        }

        .image-upload-input-wrapper input {
            width: 0;
            opacity: 0;
        }

        .image-upload-input-wrapper label {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            border: 2px solid #fff;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 0;
        }

        i.la.la-cloud-upload {
            color: #111;
        }
    </style>
@endpush

@push('script')
    <script>
            'use strict'

            function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                var preview = $(input).closest('.image-upload-wrapper').find('.image-upload-preview');
                $(preview).css('background-image', 'url(' + e.target.result + ')');
                $(preview).addClass('has-image');
                $(preview).hide();
                $(preview).fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
            }

            $(".image-upload-input").on('change', function () {
                proPicURL(this);
            });

    </script>
@endpush
