<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.invoice.invoice_pdf') }}</title>
    <link href="{{ asset('css/invoices/invoice-pdf.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body>
<table class="main-table">
    <tr>
        <td class="app-logo">
            <img src="{{ $settings['logo'] }}" width="100px">
        </td>
        <td class="text-right invoice-number">
            <h2 class="text-uppercase">{{ __('messages.invoices') }}</h2>
            <p>{{ __('messages.invoice.invoice_prefix') }}{{ $invoice->invoice_number }}</p>
        </td>
    </tr>
    <tr>
        <td class="invoice-customer-detail">
            <p class="font-weight-bold m-0">{{ html_entity_decode($invoice->customer->company_name) }}</p>
            {{-- //qr code starts here --}}
                            <div class="row">
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-5">
                                            @php
                                                use Salla\ZATCA\GenerateQrCode;
                                                use Salla\ZATCA\Tags\Seller;
                                                use Salla\ZATCA\Tags\TaxNumber;
                                                use Salla\ZATCA\Tags\InvoiceDate;
                                                use Salla\ZATCA\Tags\InvoiceTotalAmount;
                                                use Salla\ZATCA\Tags\InvoiceTaxAmount;

                                                $qr_seller_trn = 311204277500003;
                                                $qr_tax_amount = $invoice->salesTaxes->sum('amount');
                                                $qr_invoice_amount = !empty($invoice->sub_total)
                                                    ? $invoice->sub_total
                                                    : null;

                                                $qr_code = null;
                                                $qr_seller_name = 'SMiTS ENGiNEERiNG';
                                                $qr_invoice_date = !empty($invoice->invoice_date)
                                                    ? date('Y-m-d', strtotime($invoice->invoice_date))
                                                    : null;

                                        //&& $qr_tax_amount this wass added to below condition .but what if tax amount is zero which is false ,

                                                if (
                                                    $qr_seller_name &&
                                                    $qr_seller_trn &&
                                                    $qr_invoice_date &&
                                                    $qr_invoice_amount

                                                ) {
                                                    $qr_code = GenerateQrCode::fromArray([
                                                        new Seller($qr_seller_name),
                                                        new TaxNumber($qr_seller_trn),
                                                        new InvoiceDate($qr_invoice_date),
                                                        new InvoiceTotalAmount(round($qr_invoice_amount)),
                                                        new InvoiceTaxAmount($qr_tax_amount),
                                                    ])->render();
                                                }
                                            @endphp
                                            @if ($qr_code)
                                                <img class="mt-1" style="width: 200px;" src="{{ $qr_code }}"
                                                    alt="QR Code" />
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- q4 code ends here --}}
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td>
                        @foreach($invoice->invoiceAddresses as $address)
                            <div class="invoice-addresses text-right mb-2">
                                <p class="font-weight-bold m-0">{{ ($address->type == 1) ? __('messages.invoice.bill_to') : __('messages.invoice.ship_to') }}
                                    :</p>
                                <p class="m-0">{{ html_entity_decode($address->street) }}</p>
                                <p class="m-0">{{ $address->city }}, {{ $address->state }}</p>
                                <p class="m-0">{{ $address->country }}</p>
                                <p class="m-0">{{ $address->zip_code }}</p>
                            </div>
                        @endforeach
                        <div class="text-right">
                            <table class="invoice-date-table">
                                <tr>
                                    <td class="text-right font-weight-bold"
                                        style="width: 60%">{{ __('messages.invoice.invoice_date').': ' }}</td>
                                    <td>{{ !empty($invoice->invoice_date) ? Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('jS M, Y') : __('messages.common.n/a') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right font-weight-bold"
                                        style="width: 60%">{{ __('messages.invoice.due_date').': ' }}</td>
                                    <td>{{ !empty($invoice->due_date) ? Carbon\Carbon::parse($invoice->due_date)->translatedFormat('jS M, Y') : __('messages.common.n/a') }}</td>
                                </tr>
                                @if(isset($invoice->sales_agent_id))
                                    <tr>
                                        <td class="text-right font-weight-bold"
                                            style="width: 60%">{{ __('messages.invoice.sale_agent').': ' }}</td>
                                        <td>{{ isset($invoice->sales_agent_id) ? ($invoice->user->full_name) : __('messages.common.n/a') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" class="table table-bordered invoice-sales-items mt-2">
                <thead class="thead-light">
                <tr>
                    <th scope="col" class="item-index">#</th>
                    <th scope="col" class="item-col">{{ __('messages.invoice.item') }}</th>
                    <th scope="col" class="text-right item-qty">{{ __('messages.invoice.qty') }}</th>
                    <th scope="col" class="text-right rate">{{ __('messages.products.rate') }}(<span
                                class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>)
                    </th>
                    <th scope="col" class="item-taxes">{{ __('messages.invoice.taxes') }}</th>
                    <th scope="col" class="text-right total-amount">{{ __('messages.invoice.amount') }}(<span
                                class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>)
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->salesItems as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <p class="m-0">{{ html_entity_decode($item->item) }}</p>
                            <p class="text-muted m-0">
                                <small>{{ html_entity_decode($item->description) }}</small></p>
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">
                            <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                            {{ number_format($item->rate, 2) }}
                        </td>
                        <td>
                            @forelse($item->taxes as $tax)
                                <p><span class="badge badge-secondary font-weight-normal">{{ $tax->tax_rate }}%</span>
                                </p>
                            @empty
                                <p>{{ __('messages.common.n/a') }}</p>
                            @endforelse
                        </td>
                        <td class="text-right">
                            <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                            {{ number_format($item->total, 2) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td class="float-right">
            <table class="table text-right invoice-footer-table mt-2">
                <tbody>
                <tr>
                    <td>
                        {{ __('messages.invoice.sub_total').':' }}
                    </td>
                    <td>
                        <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                        {{ !empty($invoice->sub_total) ? number_format($invoice->sub_total, 2) : __('messages.common.n/a') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('messages.invoice.discount').':' }}
                    </td>
                    <td>
                        {{ formatNumber($invoice->discount) }}{{ isset($invoice->discount_symbol) && $invoice->discount_symbol == 1 ? '%' : '' }}
                    </td>
                </tr>
                @foreach($invoice->salesTaxes as $commonTax)
                    <tr>
                        <td>{{ __('messages.products.tax') }} {{ $commonTax->tax }}%</td>
                        <td>
                            <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                            {{ number_format($commonTax->amount, 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>{{ __('messages.invoice.adjustment').':' }}</td>
                    <td>
                        <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                        {{ number_format($invoice->adjustment) }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('messages.invoice.total').':' }}</td>
                    <td>
                        <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                        {{ number_format($invoice->total_amount, 2) }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('messages.invoice.total_paid').':' }}</td>
                    <td>
                        <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                        {{ number_format($totalPaid, 2) }}
                    </td>
                </tr>
                <tr class="{{ empty($totalPaid) ? 'text-color' : '' }}">
                    <td>{{ __('messages.invoice.amount_due').':' }}</td>
                    <td>
                        <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                        {{ number_format($invoice->total_amount - $totalPaid, 2) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    @if(count($invoice->payments) > 0)
        <tr>
            <td colspan="2">
                <h4>{{ __('messages.invoice.transactions').':' }}</h4>
                <table class="table table-bordered invoice-sales-items payments-table">
                    <thead class="thead-light">
                    <tr>
                        <td scope="col">{{ __('messages.payment.note') }}</td>
                        <td scope="col">{{ __('messages.payment.payment_mode') }}</td>
                        <td scope="col">{{ __('messages.payment.date') }}</td>
                        <td scope="col" class="text-right">{{ __('messages.payment.amount') }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoice->payments as $payment)
                        <tr>
                            <td>{!! !empty($payment->note) ? html_entity_decode($payment->note) : __('messages.common.n/a') !!}</td>
                            <td>{{ $payment->paymentMode->name }}</td>
                            <td>{{ Carbon\Carbon::parse($payment->payment_date)->translatedFormat('jS M, Y') }}</td>
                            <td class="text-right">
                                <span class="pdf-css">&#{{ getCurrencyIcon($invoice->currency) }}</span>
                                {{ number_format($payment->amount_received, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="text-muted text-center">{{ __('messages.invoice.no_payments_available').'...' }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    @endif
    <tr>
        <td colspan="2">
            <h4 class="mt-2">{{ __('messages.invoice.terms_conditions').':' }}</h4>
            {!! !empty($invoice->term_conditions) ? html_entity_decode($invoice->term_conditions) :  __('messages.common.n/a')  !!}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p class="mt-2">{{ __('messages.invoice.authorized_signature').' _________________' }}</p>
        </td>
    </tr>
</table>
</body>
</html>
