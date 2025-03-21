@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $bannerContent = getContent('banner.content', true);
    @endphp
    <section class="banner-section bg_img overflow-hidden"
        style="background: url({{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->background_image, '1920x1080') }});">
        <div class="container">
            <div class="banner__wrapper d-flex align-items-center">
                <div class="banner__content">
                    <span class="subtitle">{{ trans_case(@$bannerContent->data_values->heading) }}</span>
                    <h1 class="banner__content-title">{{ trans_case(@$bannerContent->data_values->subheading) }}</h1>
                    <p>{{ trans_case(@$bannerContent->data_values->description) }}</p>
                    <form class="job__search" action="{{ route('job.search') }}">
                        <div class="form--group d-flex align-items-center">
                            <select class="form-select form--control border-0 select2" name="category">
                                <option value="" selected disabled>@lang('Select Category')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ trans_case($category->name) }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control form--control" name="search" autocomplete="off"
                                placeholder="@lang('Search jobs...')">
                            <button class="btn btn--base btn--round px-md-5"
                                type="submit">{{ trans_case(@$bannerContent->data_values->button_text) }}</button>
                        </div>
                    </form>
                    <div class="popular__tags">
                        <h6 class="title d-inline-block">@lang('Popular Jobs Category')</h6>
                        <ul class="tags-list">
                            @foreach ($keywords as $keyword)
                                <li>
                                    <a
                                        href="{{ route('category.jobs', [@$keyword->category->id, slug(@$keyword->category->name)]) }}">
                                        {{ trans_case(@$keyword->category->name) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="banner__thumb d-none d-lg-block">
                    <img
                        src="{{ getImage('assets/images/frontend/banner/' . @$bannerContent->data_values->banner_image, '750x600') }}">
                </div>
            </div>
        </div>
    </section>
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.select2').select2();
        })(jQuery);
    </script>
@endpush

