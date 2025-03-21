@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>{{ trans_case('ID')}}</th>
                                    <th>{{ trans_case('User ID')}}</th>
                                    <th>{{ trans_case('User')}}</th>
                                    <th>{{ trans_case('Type') }}</th>
                                    <th>{{ trans_case('Price')}}</th>
                                    <th>{{ trans_case('Payment gateway') }}</th>
                                    <th>{{ trans_case('Payment status')}}</th>
                                    <th>{{ trans_case('Status') }}</th>
                                    <th>{{ trans_case('Purchase date')}}</th>
                                    <th>{{ trans_case('Expire date') }}</th>
                                    <th>{{ trans_case('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($all_subscriptions as $sub)
                                    <tr>
                                        <td>{{ $sub->id }}</td>
                                        <td>{{ $sub->user_id }}</td>
                                        <td>{{ $sub->user->firstname.' '.$sub->user->lastname }}</td>
                                        <td>{{ $sub->subscription->subscription_type->type }}</td>
                                        <td>{{ $sub->price }} €</td>
                                        <td>{{ $sub->payment_gateway }}</td>
                                        <td>
                                            @if($sub->payment_status == '' || $sub->payment_status == 'cancel')
                                                <span class="btn btn-danger btn-sm">{{ __('Cancel') }}</span>
                                            @elseif($sub->payment_status == 'pending')
                                                <span class="btn btn-warning btn-sm">{{ ucfirst($sub->payment_status) }}</span>

                                            @else
                                                <span class="btn btn-success btn-sm">{{ ucfirst($sub->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($sub->status == 0)
                                                <span class="btn btn-danger btn-sm">{{ __('Inactive') }}</span>
                                            @else
                                                <span class="btn btn-success btn-sm">{{ __('Active')  }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $sub->created_at->format('Y-m-d') ?? '' }}</td>
                                        <td>{{ Carbon\Carbon::parse($sub->expire_date)->format('Y-m-d') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary" data-bs-toggle="dropdown"><i class="las la-ellipsis-v"></i>
                                                @lang('Action')
                                            </button>
                                            <div class="dropdown-menu p-0">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-item confirmationBtn"
                                                    data-question="{{ trans_case('Are you sure to change the status?') }}"
                                                    data-action="{{ route('admin.subscription.users.status', $sub->id) }}"
                                                    >
                                                        <i class="la la-toggle-on"></i> {{ trans_case('Change status') }}
                                                </a>


                                            </div>
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
                @if ($all_subscriptions->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($all_subscriptions) @endphp
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="{{ trans_case('Search here...')}}" />
   
@endpush
@push('script')
    <script>
        $(document).on('click', '.change-status', function (e) {
            e.preventDefault();

            let question = $(this).data('question');
            let action = $(this).data('action');

            if (confirm(question)) {
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: { _method: 'POST', _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        console.log(response);
                        // alert("Type removed successfully!");
                        location.reload(); // Reload page or update UI dynamically
                    },
                    error: function (xhr) {
                        alert("Error: " + xhr.responseText);
                    }
                });
            }
        });
    </script>
@endpush
