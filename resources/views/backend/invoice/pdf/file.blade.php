<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Green Leaf Building Services Ltd Invoice</title>
    <link rel="stylesheet" href="{{ asset('assets/invoice/pdf/style.css') }}" media="all" />
  </head>
  <body>
    @php
      $currency = $currency === 'BDT' ? $currency.' ' : $currency;
    @endphp

    <header class="clearfix">
      <div id="logo">
        <img src="{{ asset('assets/images/logo/logo.png') }}">
      </div>

      <div style="text-align: center;margin-bottom: 30px;display: flex;flex-direction: column;gap: 6px;">
        <h2 style="margin: 0;font-size: 1.5rem;">Green Leaf Building Services LTD</h2>
        <h4 style="margin: 0;font-size: 1.1rem;color: #686868;">Loft conversion &amp; Extension specialist In East London</h4>
        <p style="margin: 0;font-size: 16px;color: #686868;">We build your home with Trust</p>
      </div>

      {{-- <h1>INVOICE {{ $no }}</h1> --}}
      <h1>INVOICE #{{ $no }}</h1>

      <div id="company" class="clearfix">
        <div>{{ $from_name }}</div>
        <div>{{ $from_address }}</div>
        <div>{{ $from_phone }}</div>
        <div><a href="mailto:{{ $from_email }}">{{ $from_email }}</a></div>
      </div>

      <div id="project">
        <div><span>NAME</span> <text>{{ $to_name }}</text></div>
        <div><span>ADDRESS</span> <text>{{ $to_address }}</text></div>
        <div><span>EMAIL</span> <text><a href="mailto:{{ $to_email }}">{{ $to_email }}</a></text></div>
        <div><span>PHONE</span> <text>{{ $to_phone }}</text></div>
        <div><span>DATE</span> <text>{{ date('d M Y', strtotime($invoice_date)) }}</text></div>
        <div><span>DUE DATE</span> <text>{{ date('d M Y', strtotime($invoice_due_date)) }}</text></div>
      </div>

    </header>

    <main>
      <table>
        <thead>
          <tr>
            <th style="padding-left: 10px" class="service">ITEM&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th style="padding-left: 10px" class="desc">DESCRIPTION</th>
            <th style="text-align: right;padding-right: 0;">PRICE</th>
            <th style="text-align: right;padding-right: 0;">QTY</th>
            <th style="text-align: right;padding-right: 0;">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          @if(count($items))
          @foreach($items as $key => $value)
          <tr>
            <td class="service">{{ $value['item'] }}</td>
            <td class="desc">{{ $value['description'] }}</td>
            <td class="unit">{{ $currency.$value['price'] }}</td>
            <td class="qty">{{ $value['quantity'] ? $value['quantity'] : '--' }}</td>
            <td class="total" style="text-align: right;">{{ $currency.$value['total'] }}</td>
          </tr>
          @endforeach
          @else
          <tr>
            <td colspan="4" class="service">No item added</td>
          </tr>
          @endif

          <tr>
            <td colspan="4">SubTotal</td>
            <td class="total">{{ $subtotal ? $currency.$subtotal : $currency.'0.00' }}</td>
          </tr>
          <tr>
            <td colspan="4">TAX</td>
            <td class="total">{{ $tax ? $currency.$tax : 0 }}</td>
          </tr>
          <tr>
            <td colspan="4">Discount</td>
            <td class="total">{{ $discount ? $currency.$discount : 0 }}</td>
          </tr>
          <tr>
            <td colspan="4" class="grand total">Grand Total</td>
            <td class="grand total">{{ $grand_total ? $currency.$grand_total : 0 }}</td>
          </tr>
        </tbody>
      </table>
      <div id="notices">
        <div>NOTE:</div>
        <div class="notice">{{ $note }}</div>
      </div>
    </main>

    {{-- <footer> Invoice was created on a computer and is valid without the signature and seal. </footer> --}}
  </body>
</html>