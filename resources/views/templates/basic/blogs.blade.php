@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section padding-top padding-bottom ">
        <div class="container">
            <div class="row g-4 justify-content-center">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="post__item">
                            <div class="post__item-thumb">
                                <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->image, '410x300') }}" alt="@lang('announcement')">
                            </div>
                            <div class="post__item-content">
                                <h5 class="title"><a href="{{ route('blog.details', $blog->slug) }}">
                                        {{ trans_case($blog->data_values->title) }}
                                    </a>
                                </h5>
                                <ul class="post-meta">
                                    <li>
                                        <span class="date">
                                            <i class="fas fa-calendar"></i>
                                            {{ showDateTime($blog->created_at) }}
                                        </span>
                                    </li>
                                </ul>
                                <p>{{ shortDescription($blog->data_values->description, 100) }}</p>
                                <a href="{{ route('blog.details', $blog->slug) }}" class="read-more">
                                    @lang('Read More')
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($blogs->hasPages())
                {{ paginateLinks($blogs) }}
            @endif
        </div>
    </section>
    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
