@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Email-Phone')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Submited Date')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Balance')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($job->proves as $jobProve)
                                    <tr>
                                        <td>
                                            <span class="fw-bold d-block">{{ $jobProve->user->fullname }}</span>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $jobProve->user->id) }}"><span>@</span>{{ $jobProve->user->username }}
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            {{ $jobProve->user->email }}<br>{{ $jobProve->user->mobile }}
                                        </td>
                                        <td> <span class="fw-bold">{{ $jobProve->user->country_name }}</span></td>
                                        <td>
                                            <span class="d-block">{{ showDateTime($jobProve->created_at) }}</span>
                                            {{ diffForHumans($jobProve->created_at) }}
                                        </td>
                                        <td>
                                            @php echo $jobProve->statusBadge; @endphp
                                        </td>
                                        <td>
                                            <span class="fw-bold">
                                               {{ showAmount($job->rate) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ trans_case($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
