@extends($activeTemplate . 'layouts.master')
@section('panel')
    <div class="dashboard__content ">
        <form action="" class="float-end">
            <div class="mb-3 d-flex justify-content-end  table-form ">
                <div class="input-group table_data_search">
                    <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Search here ...')">
                    <button class="input-group-text btn btn--base text-white">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <table class="table transaction__table job__history">
            <thead>
                <tr>
                    <th>@lang('Job Code')</th>
                    <th>@lang('Job Title')</th>
                    <th>@lang('Quantity')</th>
                    <th>@lang('Rate')</th>
                    <th>@lang('Total')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('More')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as $job)
                    <tr>
                        <td>
                            <span>{{ trans_case($job->job_code) }}</span>
                        </td>
                        <td>
                            {{ trans_case(strLimit($job->title, 20)) }}
                            @if (strlen($job->title) > 20)
                                <br>
                                <small class="jobTitle text--base " data-title_details="{{ trans_case($job->title) }}">@lang('Read More')</small>
                            @endif
                        </td>
                        <td>
                            <span>{{ $job->quantity }} </span>
                        </td>
                        <td>
                            <span>
                                {{ showAmount($job->rate) }}
                            </span>
                        </td>
                        <td>
                            <span>
                                {{ showAmount($job->total) }}
                            </span>
                        </td>
                        <td>
                            @php
                                echo $job->statusJob;
                            @endphp
                        </td>
                        <td>
                            <div class="text--end">
                                <span class="time">{{ showDateTime($job->created_at, 'M d, Y h:i:s a') }}<br>{{ diffForHumans($job->created_at) }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $isEnable = $job->status == Status::JOB_REJECTED || $job->status == App\Constants\Status::JOB_COMPLETED;
                            @endphp
                            <div class="d-flex flex-wrap table-icon">
                                <a href="{{ !$isEnable ? route('user.job.edit', $job->id) : 'javascript:void(0)' }}" class="text--success me-2 {{ $isEnable ? 'disabled' : '' }} " @if (!$isEnable) data-bs-toggle="tooltip" title="Edit" @endif>
                                    <i class="lar la-edit"></i>
                                </a>
                                @if ($job->status == App\Constants\Status::JOB_PAUSE)
                                    <a href="JavaScript:void(0)" class="text--base statusBtn me-2 confirmationBtn" data-bs-toggle="tooltip" data-action="{{ route('user.job.status', $job->id) }}" data-question="@lang('Are you sure to approve this job ')" title="@lang('Approve')">
                                        <i class="las la-check-circle"></i>
                                    </a>
                                @elseif($job->status == App\Constants\Status::JOB_APPROVED)
                                    <a href="JavaScript:void(0)" class="text--warning statusBtn me-2 confirmationBtn" data-bs-toggle="tooltip" data-action="{{ route('user.job.status', $job->id) }}" data-question="@lang('Are you sure to paused this job ')" title="@lang('Paused')">
                                        <i class="las la-pause-circle"></i>
                                    </a>
                                @endif

                                <a href="{{ route('user.job.details', $job->id) }}" class="text--info notification-holder" data-bs-toggle="tooltip" title="@lang('Job prove')">
                                    <i class="las la-desktop"></i>
                                    @if (@$job->proves->where('notification', 0)->count() > 0)
                                        <span class="notification-count">
                                            {{ @$job->proves->where('notification', 0)->count() }}
                                        </span>
                                    @endif

                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="justify-content-center text-center" colspan="100%">{{ trans_case($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($jobs->hasPages())
            {{ paginateLinks($jobs) }}
        @endif
    </div>

    <x-confirmation-modal />

    <div class="modal TitleModal fade " tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Job Title')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        (function($) {
            $('.modal-footer').find('button').addClass('btn--sm');
            $('.modal-header').find('button').replaceWith(
                '<span class="las la-times" data-bs-dismiss="modal" ></span>');


            $('.jobTitle').on('click', function() {
                let details = $(this).data('title_details');
                let modal = $('.TitleModal');
                modal.find('.modal-body p').html(details)
                modal.modal('show');
            })

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .jobTitle {
            cursor: pointer;
        }
    </style>
@endpush
