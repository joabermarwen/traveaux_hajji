@extends($activeTemplate . 'layouts.master')
@section('panel')
    <div class="dashboard__content ">
        <form action="" class="float-end">
            <div class="mb-3 d-flex justify-content-end  table-form ">
                <div class="input-group table_data_search">
                    <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Search by transactions')">
                    <button class="input-group-text btn btn--base text-white">
                        <i class="las la-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <table class="table custom--table">
            <thead>
                <tr>
                    <th>@lang('Gateway | Transaction')</th>
                    <th class="text-center">@lang('Initiated')</th>
                    <th class="text-center">@lang('Amount')</th>
                    <th class="text-center">@lang('Conversion')</th>
                    <th class="text-center">@lang('Status')</th>
                    <th>@lang('Details')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $deposit)
                    <tr>
                        <td>
                            <div>
                                <span class="fw-bold"> <span class="text--primary">{{ trans_case($deposit->gateway?->name) }}</span>
                                </span>
                                <br>
                                <small> {{ $deposit->trx }} </small>
                            </div>
                        </td>

                        <td class="text-center">
                            <div>
                                <span class="d-block">{{ showDateTime($deposit->created_at) }}</span>
                                <span>{{ diffForHumans($deposit->created_at) }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div>

                                {{ showAmount($deposit->amount) }} + <span class="text--danger" title="@lang('charge')">{{ showAmount($deposit->charge) }} </span>
                                <br>
                                <strong title="@lang('Amount with charge')">
                                    {{ showAmount($deposit->amount + $deposit->charge) }}
                                </strong>
                            </div>
                        </td>
                        <td class="text-center">
                            <div>

                                1 {{ trans_case(gs("cur_text")) }} = {{ showAmount($deposit->rate, currencyFormat:false) }}
                                {{ trans_case($deposit->method_currency) }}
                                <br>
                                <strong>{{ showAmount($deposit->final_amount, currencyFormat:false) }}
                                    {{ trans_case($deposit->method_currency) }}</strong>
                            </div>

                        </td>
                        <td class="text-center">
                            @php echo $deposit->statusBadge @endphp
                        </td>
                        @php
                            $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                        @endphp

                        <td>
                            <a href="javascript:void(0)" class="btn btn--base btn--sm @if ($deposit->method_code >= 1000) detailBtn @else disabled @endif" @if ($deposit->method_code >= 1000) data-info="{{ $details }}" @endif @if ($deposit->status == App\Constants\Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                <i class="fa fa-desktop"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center">{{ trans_case($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($deposits->hasPages())
            <div class="card-footer">
                {{ $deposits->links() }}
            </div>
        @endif
    </div>


    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData mb-2 list-group-flush">
                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";

            let width = $(window).width()
            $('.detailBtn').on('click', function() {

                var modal = $('#detailModal');

                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
