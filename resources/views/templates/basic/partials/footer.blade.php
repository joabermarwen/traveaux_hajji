@php
    $socialElement = getContent('social_icon.element', orderById: true);
    $policyPages = getContent('policy_pages.element', orderById: true);
    $footerContent = getContent('footer.content', true);
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', App\Constants\Status::NO)
        ->get();
@endphp

<footer class="footer-section bg_img bg_fixed" style="background: url({{ getImage('assets/images/frontend/footer/' . @$footerContent->data_values->image, '1920x1080') }});">
    <div class="footer-top">
        <div class="container">
            <div class="row justify-content-between gy-5">
                <div class="col-xl-5 col-lg-6 col-md-10 col-sm-10">
                    <div class="footer__widget widget-about">
                        <div class="logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ siteLogo() }}" alt="@lang('logo')">
                            </a>
                        </div>
                        <p>{{ trans_case(@$footerContent->data_values->description) }}</p>
                        <ul class="social-links mt-4">
                            @foreach ($socialElement as $social)
                                <li>
                                    <a href="{{ $social->data_values->url }}" target="__blank">
                                        @php echo $social->data_values->icon @endphp
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-5 col-sm-5">
                    <div class="footer__widget">
                        <h4 class="widget-title">@lang('About Company')</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('job.list') }}">@lang('Jobs')</a></li>
                            @foreach ($pages as $page)
                                <li>
                                    <a href="{{ route('pages', [$page->slug]) }}">
                                        {{ trans_case($page->name) }}
                                    </a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('blogs') }}">@lang('Blogs')</a></li>
                            <li><a href="{{ route('contact') }}">@lang('Contact Us')</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-5 col-sm-5">
                    <div class="footer__widget">
                        <h4 class="widget-title">@lang('Policy Pages')</h4>
                        <ul class="footer-links">
                            @foreach ($policyPages as $page)
                                <li>
                                    <a href="{{ route('policy.pages', $page->slug) }}">
                                        {{ $page->data_values->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer__bottom__wrapper d-flex flex-wrap justify-content-center">
                <p class="copyright text--white">@lang('Copyright') &copy; {{ date('Y') }} @lang('All Rights Reserved')</p>
            </div>
        </div>
    </div>
</footer>
