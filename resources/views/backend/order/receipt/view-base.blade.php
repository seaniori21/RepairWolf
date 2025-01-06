<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }} | Receipt #{{ $data['no'] }}</title>
    <!-- favicon -->
    <link rel='icon' href='{{ asset('assets/images/logo/logo.png') }}' type='image/x-icon'>
    <!-- favicon -->

    <link rel="stylesheet" href="{{ asset('assets/receipt/pdf/style.css') }}" media="all" />
    <style>
      @media print {
        /* Hide the browser's default header and footer */
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;     /* this affects the margin in the printer settings */
        }

        body {
          margin: 1.6cm !important; /* this provides an extra margin that won't be printed */
        }

        /* You can also hide specific elements in the header and footer */
        @page :first {
            margin-top: 0; /* Override default top margin for the first page */
        }

        .fixed { display: none; } /* Hide table headers when printing */
      }

      .fixed {
          position: fixed;
          top: 10px;
          right: 10px;
          background-color: #eee;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          border-radius: 5px;
          overflow: hidden;
          display: xnone;
      }

      .fixed-content {
          display: flex;
          flex-direction: column;
          gap: 10px;
          padding: 10px;
      }

      .fixed-content a,
      .fixed-content button {
        text-align: center;
        text-decoration: none;
        background-color: #2ecc71;
        color: #fff;
        padding: 8px 15px;
        cursor: pointer;
        border: none;
        border-radius: 3px;
        outline: none;
      }

      .fixed-content a:hover,
      .fixed-content button:hover {
        opacity: 0.7;
      }

      .fixed-content a.primary {
        background-color: #0d84fc;
      }

      .fixed-content a.secondary {
        background-color: #8b8b8b;
      }
    </style>
  </head>
  <body style="margin: 40px 180px; position: relative;">
    <header class="clearfix">
      <div id="logo">
        {{-- <img src="{{ asset('assets/images/logo/logo.png') }}"> --}}
      </div>

      <div style="text-align: center;margin-bottom: 30px;display: flex;flex-direction: column;gap: 6px;">
        <h2 style="margin: 0;font-size: 1.5rem;">TowWolf</h2>
        <h4 style="margin: 0;font-size: 1.1rem;color: #686868;">Brooklyn, NY 11223</h4>
        <p style="margin: 0;font-size: 16px;color: #686868;">(917) 789-6758</p>
      </div>

      <h1>RECEIPT #{{ isset($data['no']) ? $data['no'] : 1000 }}</h1>

      <table>
        <tr style="vertical-align: top;">
          <td style="text-align: left; background: transparent;">
            <div id="company" class="clearfix">
              <div><span>CUSTOMER</span><text></text></div>
              <div><span>NAME</span> <text>{{ isset($data['customer']->first_name) ? $data['customer']->first_name.' '.$data['customer']->last_name : 1000 }}</text></div>
              
              @if(isset($data['customer']->email) && isset($data['customer']->email))
              <div><span>EMAIL</span> <text><a href="mailto:{{ isset($data['customer']->email) ? $data['customer']->email : 'N/A' }}">{{ isset($data['customer']->email) ? $data['customer']->email : 'N/A' }}</a></text></div>
              @endif

              @if(isset($data['customer']->mobile) && isset($data['customer']->mobile))
              <div><span>MOBILE</span> <text><a href="tel:{{ isset($data['customer']->mobile) ? $data['customer']->mobile : 'N/A' }}">{{ isset($data['customer']->mobile) ? $data['customer']->mobile : 'N/A' }}</a></text></div>
              @endif

              @if(isset($data['customer']->address_line_1))
              <div><span>ADDRESS</span><text>{{ isset($data['customer']->address_line_1) ? $data['customer']->address_line_1 : 'N/A' }}</text></div>
              
              @if(isset($data['customer']->address_line_1))
              <div><span></span><text>{{ isset($data['customer']->address_line_2) ? $data['customer']->address_line_2 : 'N/A' }}</text></div>
              @endif
              @endif
            </div>
          </td>

          <td style="text-align: right; background: transparent;">
            <div id="project" style="float: right;">
              <div><span>CASHIER</span> <text>{{ isset($data['cashier']->name) ? $data['cashier']->name : 'N/A' }}</text></div>
              <div><span>SERVICE PERSON</span> <text>{{ isset($data['servicePerson']->name) ? $data['servicePerson']->name : 'N/A' }}</text></div>
              <div><span>DATE</span> <text>{{ isset($data['order_date']) ? date('M d, Y', strtotime($data['order_date'])) : 'N/A' }}</text></div>
            </div>
          </td>
        </tr>  
      </table>

      <table>
        <tr style="vertical-align: top;">
          <td style="text-align: left; background: transparent;">
            <div id="company" class="">
              <div><span>VEHICLE</span><text></text></div>
              <div style="xwhite-space: normal;">
                <span>LICENSE PLATE</span>
                <text style="width: 130px;display: inline-block;">{{ isset($data['vehicle']->license_plate) ? $data['vehicle']->license_plate : 'N/A' }}</text>

                <span style="width: 48px;">VIN</span>
                <text style="width: 130px;display: inline-block;">{{ isset($data['vehicle']->vin) ? $data['vehicle']->vin : 'N/A' }}</text>

                <span style="width: 75px;">YEAR</span>
                <text style="width: 130px;display: inline-block;">{{ isset($data['vehicle']->year) ? $data['vehicle']->year : 'N/A' }}</text>
              </div>
              <div style="white-space: normal;">
                <span>MAKE</span>
                <text style="width: 130px;display: inline-block;">{{ isset($data['vehicle']->make) ? $data['vehicle']->make : 'N/A' }}</text>

                <span style="width: 48px;">MODEL</span>
                <text style="width: 130px;display: inline-block;">{{ isset($data['vehicle']->model) ? $data['vehicle']->model : 'N/A' }}</text>
                
                <span style="width: 75px;">BODY TYPE</span>
                <text style="width: 130px;display: inline-block;">{{ isset($data['vehicle']->body_type) ? $data['vehicle']->body_type : 'N/A' }}</text>
              </div>
              <div style="white-space: normal;">
                <span>TRIM</span>
                <text style="width: 130px;display: inline-block;">{{ isset($data['vehicle']->trim) ? $data['vehicle']->trim : 'N/A' }}</text>

                <span style="width: 48px;">COLOR</span>
                <text style="width: 130px;display: inline-block;">{{ isset($data['vehicle']->color) ? $data['vehicle']->color : 'N/A' }}</text>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </header>

    <main>
      <table>
        <thead>
          <tr>
            <th class="service" style="padding: 5px;">Qty</th>
            <th class="desc" style="padding: 5px;">Key</th>
            <th class="desc" style="padding: 5px;">UPC</th>
            <th class="desc" style="padding: 5px;">Name</th>
            <th class="desc" style="padding: 5px;">Manufacturer</th>
            <th class="desc" style="padding: 5px;">Type</th>
            <th style="padding: 5px;text-align: right">Price</th>
            <th style="padding: 5px;text-align: right;">Total</th>
          </tr>
        </thead>
        <tbody>
          @if (!empty($data->productItems))
          @foreach ($data->productItems as $key => $value)
          <tr>
            <td class="service" style="padding: 10px 5px;vertical-align: middle;">{{ $value->quantity }}</td>
            <td class="service" style="padding: 10px 5px;">{{ $value->product->identification_code }}</td>
            <td class="service" style="padding: 10px 5px;">{{ isset($value->product->upc) && $value->product->upc ? $value->product->upc : '--' }}</td>
            <td class="service" style="padding: 10px 5px;">{{ $value->product->name }}</td>
            <td class="service" style="padding: 10px 5px;">{{ $value->product->manufacturer }}</td>
            <td class="service" style="padding: 10px 5px;vertical-align: middle;">{{ ucwords($value->product->type) }}</td>

            <td style="padding: 10px 5px;text-align: right;">${{ $value->base_price ? number_format($value->base_price, 2) : 0.00 }}</td>
            <td style="padding: 10px 5px;text-align: right;">${{ number_format($value->base_price * $value->quantity, 2) }}</td>
          </tr>
          @endforeach
          @else
          <tr><td style="text-align: center;padding:10px 0;color: #717171;" colspan="10">No product added yet</td></tr>
          @endif


          @php
              $baseTaxAmount = $data->tax > 0 && $data->base_total > 0 ? ($data->tax / 100) * $data->base_total : 0;
          @endphp


          @if($data->tax)
          @endif
          <tr style="border-top: 1px solid #c1ced9;">
            <td style="padding-top: 10px;" colspan="7">TAX</td>
            <td style="padding-top: 10px;" class="xtotal">{{ isset($data->tax) ? $data->tax : 0.00 }}%</td>
          </tr>

          @if($data->convenience_fee)
          @endif
          <tr>
            <td style="padding-top: 10px;" colspan="7">Convenience Fee</td>
            <td style="padding-top: 10px;" class="xtotal">${{ isset($data->convenience_fee) ? number_format($data->convenience_fee, 2) : 0.00 }}</td>
          </tr>

          @if($data->discount)
          <tr>
            <td style="padding-top: 10px;" colspan="7">Discount</td>
            <td style="padding-top: 10px;" class="xtotal">${{ isset($data->discount) ? number_format($data->discount, 2) : 0.00 }}</td>
          </tr>
          @endif


          <tr>
            <td style="padding-top: 10px;" colspan="7">Total</td>
            <td style="padding-top: 10px;" class="xtotal">${{ isset($data->base_total) ? number_format($data->base_total + $baseTaxAmount + $data->convenience_fee, 2) : 0.00 }}</td>
          </tr>

          <tr>
            <td style="padding-top: 5px;" colspan="7">Paid Amount</td>
            <td style="padding-top: 5px;" class="xtotal">${{ isset($data->paid_amount) ? number_format($data->paid_amount, 2) : 0.00 }}</td>
          </tr>
          <tr>
            <td style="padding-top: 5px;" colspan="7">Due Amount</td>
            <td style="padding-top: 5px;" class="xtotal">${{ number_format(($data->base_total + $baseTaxAmount + $data->convenience_fee)-$data->paid_amount, 2) }}</td>
          </tr>
          {{-- <tr>
            <td colspan="8" class="grand total">GRAND TOTAL</td>
            <td class="grand total">${{ $data->subtotal }}</td>
          </tr> --}}
        </tbody>
      </table>


      <div id="notices" style="margin-top: 80px;">
        {{-- <div>NOTICE:</div>
        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div> --}}
      </div>
    </main>

    {{-- <footer>Invoice was created on a computer and is valid without the signature and seal.</footer> --}}

    <div class="fixed">
        <div class="fixed-content">
            <button type="button" onclick="printDocument()">Print</button>
            <a href="{{ route('admin.order.receipt.base.download', ['data' => $data->id]) }}" class="primary">Download PDF</a>
            <a href="{{ route('admin.order.records') }}" class="secondary">Go Back</a>
        </div>
    </div>

    <script>function printDocument() { window.print(); } </script>
  </body>
</html>