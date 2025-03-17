@php
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', App\Constants\Status::NO)
        ->get();
@endphp
<div class="header-bottom">
    <div class="container">
        <div class="header-bottom-area">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ siteLogo() }}" alt="@lang('logo')">
                </a>
            </div>
            <ul class="menu">
                <li><a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'active' : '' }}">@lang('Home')</a></li>
                @foreach ($pages as $page)
                    <li>
                        <a href="{{ route('pages', [$page->slug]) }}"
                            class="{{ request()->routeIs('pages') ? 'active' : '' }}">{{ trans_case($page->name) }}</a>
                    </li>
                @endforeach
                <li><a href="{{ route('job.list') }}"
                        class="{{ request()->routeIs('job.list') ? 'active' : '' }}">@lang('Jobs')</a></li>
                <li><a href="{{ route('subscription.index') }}"
                     class="{{ request()->routeIs('subscription.index') ? 'active' : '' }}">@lang('Subscriptions')</a></li>
                <li><a href="{{ route('blogs') }}"
                        class="{{ request()->routeIs('blogs') ? 'active' : '' }}">@lang('Blogs')</a></li>
                <li><a href="{{ route('contact') }}"
                        class="{{ request()->routeIs('contact') ? 'active' : '' }}">@lang('Contact Us')</a></li>
                <li class="p-0">
                    @guest
                        <a class="btn btn--base btn--round btn--md ms-2 my-2 my-lg-0 text-white"
                            href="{{ route('user.login') }}">@lang('Login')
                        </a>
                        <a class="btn btn--base btn--round btn--md ms-2 text-white"
                            href="{{ route('user.register') }}">@lang('Register')
                        </a>
                    @else
                        <a class="btn btn--base btn--round btn--md ms-2 my-2 my-lg-0 text-white"
                            href="{{ route('user.home') }}">@lang('Dashboard')
                        </a>
                        <a class="btn btn--base btn--round btn--md ms-2 text-white"
                            href="{{ route('user.logout') }}">@lang('Logout')
                        </a>
                    @endguest

                </li>
            </ul>
            <div class="header-trigger-wrapper d-flex d-lg-none align-items-center">
                <div class="header-top-trigger lh-1 p-1">
                    <i class="las la-ellipsis-v"></i>
                </div>
                <div class="header-trigger d-block d--none">
                    <span></span>
                </div>
            </div>

        </div>
    </div>
</div>
