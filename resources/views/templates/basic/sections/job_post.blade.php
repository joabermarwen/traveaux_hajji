@php
    $jobPostConent = getContent('job_post.content', true);
    $jobs = App\Models\JobPost::active()
        ->orderBy('id', 'desc')
        ->take(8)
        ->get();
@endphp
<section class="job-section padding-top padding-bottom section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="section__header text-center">
                    <h2 class="section__header-title">{{ trans_case($jobPostConent?->data_values->heading) }}</h2>
                    <p>{{ trans_case($jobPostConent?->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach ($jobs as $job)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="job__item">
                        <a href="{{ route('job.details', [$job->id, slug($job->title)]) }}">
                            <div class="job__item-thumb">
                                <img src="{{ getImage(getFilePath('jobPoster') . '/' . @$job->attachment, getFileSize('jobPoster')) }}" alt="@lang('job')">
                            </div>
                        </a>
                        <div class="job__item-content">
                            <div class="wrapper d-flex justify-content-between align-items-center">
                                <a href="{{ route('job.details', [$job->id, slug($job->title)]) }}" class="tag btn btn--sm">
                                    <i class="las la-tag"></i>
                                    @lang('Vacancy Available')
                                </a>
                                <a class="job-author text--primary tag btn btn--sm" href="{{ route('job.details', [$job->id, slug($job->title)]) }}">
                                    {{ $job->vacancy_available }}
                                </a>
                            </div>
                            <h5 class="title">
                                <a href="{{ route('job.details', [$job->id, slug($job->title)]) }}">
                                    {{ trans_case($job->title) }}
                                </a>
                            </h5>
                            <ul class="job__info">
                                <li>
                                    <h6 class="job__info-title">@lang('published ')</h6>
                                    <span class="text--primary">
                                        {{ showDateTime($job->created_at, 'F j, Y') }}
                                    </span>
                                </li>
                            </ul>
                            <div class="content__footer d-flex align-items-center justify-content-between">
                                <h3 class="price">{{ showAmount($job->rate) }}</h3>
                                <a href="{{ route('job.details', [$job->id, slug($job->title)]) }}" class="btn btn--base btn--sm">@lang('APPLY')</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 mt-4">
                <div class="section__header text-center">
                    <a href="{{ route('job.list') }}" class="btn btn--base">@lang('View all')</a>
                </div>
            </div>
        </div>
    </div>
</section>
