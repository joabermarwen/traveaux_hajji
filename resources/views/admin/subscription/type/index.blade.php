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
                                    <th>{{ trans_case('Validity') }}</th>
                                    <th>{{ trans_case('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($types as $type)
                                    <tr>
                                        <td>{{ $type->id }}</td>
                                        <td>{{ $type->type }}</td>
                                        <td>{{ $type->validity }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary" data-bs-toggle="dropdown"><i class="las la-ellipsis-v"></i>
                                                @lang('Action')
                                            </button>
                                            <div class="dropdown-menu p-0">
                                                <a href="{{ route('admin.subscription.type.edit', $type->id) }}" class="dropdown-item">
                                                    <i class="las la-pen"></i> {{trans_case('Edit') }}
                                                </a>
                                                <a href="javascript:void(0);"
                                                    class="dropdown-item remove-type"
                                                    data-question="{{ trans_case('Are you sure to remove this type?') }}"
                                                    data-action="{{ route('admin.subscription.type.delete', $type->id) }}">
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
                @if ($types->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($types) @endphp
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="{{trans_case('Search here...')}}" />
    <a href="{{ route('admin.subscription.type.create') }}" class="btn btn-outline--primary h-45">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush

@push('script')
    <script>
        "use strict";
        (function ($) {
            $('.catDescription').on('click', function() {
                let details = $(this).data('cat_details');
                let modal   = $('.categoryModal');
                modal.find('.modal-body p').html(details)
                modal.modal('show');
            });
        })(jQuery);

        $(document).on('click', '.remove-type', function (e) {
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

@push('style')
    <style>
        .table {
            background-color: #fff;
            border-radius: 10px;
        }

        .table-responsive--sm.table-responsive {
            min-height: 200px;
        }

        .card {
            background-color: transparent;
            box-shadow: none;
        }
    </style>
@endpush
