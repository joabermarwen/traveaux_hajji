@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section padding-top padding-bottom ">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-8 col-md-12">
                    <div class="announcement__details">
                        <div class="announcement__details__thumb">
                            <img alt="blog" src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '800x600') }}">
                        </div>
                        <h3 class="blog-title">{{ trans_case($blog->data_values->title) }}</h3>
                        <ul class="announcement__meta d-flex flex-wrap mt-2 mb-3 align-items-center">
                            <li>
                                <a href="#">
                                    <i class="far fa-calendar"></i>
                                    {{ showDateTime($blog->created_at, 'j F,Y g:i a') }}
                                </a>
                            </li>
                        </ul>
                        <div class="announcement__details__content">
                            <p>@php echo $blog->data_values->description @endphp</p>

                            <ul class="social-links m-0 me-3">

                                <li><strong class="me-3">@lang('Share'):</strong> </li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}">
                                        <span class="lab la-facebook-f"></span>
                                    </a>
                                </li>
                                <li><a href="https://twitter.com/intent/tweet?text={{ trans_case($blog->data_values->title) }}&amp;url={{ urlencode(url()->current()) }}">
                                        <span class="lab la-twitter"></span>
                                    </a>
                                </li>
                                <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ trans_case($blog->data_values->title) }}&amp;summary=dit is de linkedin summary">
                                        <span class="lab la-linkedin-in"></span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <div class="mt-3">
                            <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 sidebar-right theiaStickySidebar">
                    <div class="widget-box post-widget">
                        <h4 class="pro-title">@lang('Latest Blogs')</h4>
                        <ul class="latest-posts m-0">
                            @foreach ($blogs as $blog)
                                <li>
                                    <div class="post-thumb">
                                        <a href="{{ route('blog.details', $blog->slug) }}">
                                            <img class="img-fluid" src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->image, '410x300') }}" alt="@lang('image')">
                                        </a>
                                    </div>
                                    <div class="post-info">
                                        <h6>
                                            <a href="{{ route('blog.details', $blog->slug) }}">
                                                {{ shortDescription($blog->data_values->title) }}
                                            </a>
                                        </h6>
                                        <a href="{{ route('blog.details', $blog->slug) }}" class="posts-date"><i class="far fa-calendar-alt"></i>
                                            {{ showDateTime($blog->created_at, 'j F,Y g:i a') }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
