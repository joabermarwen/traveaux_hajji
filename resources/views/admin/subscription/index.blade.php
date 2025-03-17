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
                                <th>{{ trans_case('Type')}}</th>
                                <th>{{ trans_case('Title') }}</th>
                                <th>{{ trans_case('Logo')}}</th>
                                <th>{{ trans_case('Price')}}</th>
                                <th>{{ trans_case('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($all_subscriptions as $sub)
                                <tr>
                                    <td>{{ $sub->id }}</td>
                                    <td>{{ $sub->subscription_type->type }}</td>
                                    <td>{{ $sub->title }}</td>
                                    <td>
                                        <div class="user">
                                            <div class="thumb">
                                                <img src="{{ getImage(getFilePath('subscription') . '/' . $sub->logo, getFileSize('subscription')) }}" class="plugin_bg">
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $sub->price }} €</td>

                                    <td>
                                        <button class="btn btn-sm btn-outline--primary" data-bs-toggle="dropdown"><i class="las la-ellipsis-v"></i>
                                            {{ trans_case('Action') }}
                                        </button>
                                        <div class="dropdown-menu p-0">
                                            <a href="{{ route('admin.subscription.edit', $sub->id) }}" class="dropdown-item">
                                                <i class="las la-pen"></i> {{ trans_case('Edit')}}
                                            </a>
                                            <a href="javascript:void(0);"
                                                class="dropdown-item remove-subscription"
                                                data-question="{{ trans_case('Are you sure to remove this subscription?') }}"
                                                data-action="{{ route('admin.subscription.delete', $sub->id) }}">
                                                    <i class="la la-trash"></i> {{ trans_case('Remove') }}
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
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="{{ trans_case('Search here...')}}" />
    <a href="{{ route('admin.subscription.create') }}" class="btn btn-outline--primary h-45">
        <i class="las la-plus"></i>{{trans_case('Add New') }}
    </a>
@endpush
@push('script')
    <script>
        $(document).on('click', '.remove-subscription', function (e) {
            e.preventDefault();

            let question = $(this).data('question');
            let action = $(this).data('action');

            if (confirm(question)) {
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        alert("Type removed successfully!");
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
