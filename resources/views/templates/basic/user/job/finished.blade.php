@extends($activeTemplate . 'layouts.master')
@section('panel')
    <div class="dashboard__content ">
        <table class="table transaction__table">
            <thead>
                <tr>
                    <th>@lang('Job Code')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as  $job)
                    <tr>
                        <td>
                            <span class="invoice-id">{{ __(@$job->job->job_code) }}</span>
                        </td>
                        <td>
                            <span class="amount">
                              {{ showAmount(@$job->job->rate) }}
                            </span>
                        </td>
                        <td>
                            @if ($job->status == Status::JOB_PROVE_PENDING)
                                <span class="badge badge--warning">@lang('Pending')</span>
                            @elseif($job->status == Status::JOB_PROVE_APPROVE)
                                <span class="badge badge--success">@lang('Approved')</span>
                            @else
                                <span class="badge badge--danger">@lang('Rejected')</span>
                            @endif
                        </td>
                        <td>
                            <span class="time">{{ showDateTime($job->created_at, 'M d, Y h:i:s a') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="justify-content-center text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($jobs->hasPages($jobs))
        {{ paginateLinks($jobs) }}
    @endif
@endsection
