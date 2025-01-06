<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }} | Receipt #{{ $data['no'] }}</title>
    <!-- favicon -->
    <link rel='icon' href='{{ asset('assets/images/logo/logo.png') }}' type='image/x-icon'>
    <!-- favicon -->

    <link rel="stylesheet" href="{{ asset('assets/receipt/pdf/style.css') }}" media="all" />    
  </head>
  <body style="position: relative;">
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
              <div><span>NAME</span> <text>{{ isset($data['customer']['first_name']) ? $data['customer']['first_name'].' '.$data['customer']['last_name'] : 1000 }}</text></div>
              
              @if(isset($data['customer']['email']) && isset($data['customer']['email']))
              <div><span>EMAIL</span> <text><a href="mailto:{{ isset($data['customer']['email']) ? $data['customer']['email'] : 'N/A' }}">{{ isset($data['customer']['email']) ? $data['customer']['email'] : 'N/A' }}</a></text></div>
              @endif

              @if(isset($data['customer']['mobile']))
              <div><span>MOBILE</span> <text><a href="tel:{{ isset($data['customer']['mobile']) ? $data['customer']['mobile'] : 'N/A' }}">{{ isset($data['customer']['mobile']) ? $data['customer']['mobile'] : 'N/A' }}</a></text></div>
              @endif

              @if(isset($data['customer']['address_line_1']))
              <div><span>ADDRESS</span><text>{{ isset($data['customer']['address_line_1']) ? $data['customer']['address_line_1'] : 'N/A' }}</text></div>
              
              @if(isset($data['customer']['address_line_2']))
              <div><span></span><text>{{ isset($data['customer']['address_line_2']) ? $data['customer']['address_line_2'] : 'N/A' }}</text></div>
              @endif
              @endif
            </div>
          </td>

          <td style="text-align: right; background: transparent;">
            <div id="project" class="clearfix" style="float: right;">
              <div><span>CASHIER</span> <text>{{ isset($data['cashier']['name']) ? $data['cashier']['name'] : 'N/A' }}</text></div>
              <div><span>SERVICE PERSON</span> <text>{{ isset($data['service_person']['name']) ? $data['service_person']['name'] : 'N/A' }}</text></div>
              <div><span>DATE</span> <text>{{ isset($data['order_date']) ? date('M d, Y', strtotime($data['order_date'])) : date('M d, Y') }}</text></div>
            </div>
          </td>
        </tr>
      </table>

      <table class="clearfix" style="margin-top: 130px">
        <tr style="vertical-align: top;">
          <div id="company" class="">
            <div><span>VEHICLE</span><text></text></div>
            <div style="xwhite-space: normal;">
              <span>LICENSE PLATE</span>
              <text style="margin-bottom: -4px; width: 130px;display: inline-block;">{{ isset($data['vehicle']['license_plate']) ? $data['vehicle']['license_plate'] : 'N/A' }}</text>

              <span style="width: 48px;">VIN</span>
              <text style="margin-bottom: -4px; width: 130px;display: inline-block;">{{ isset($data['vehicle']['vin']) ? $data['vehicle']['vin'] : 'N/A' }}</text>

              <span style="width: 75px;">YEAR</span>
              <text style="margin-bottom: -4px; width: 130px;display: inline-block;">{{ isset($data['vehicle']['year']) ? $data['vehicle']['year'] : 'N/A' }}</text>
            </div>
            <div style="white-space: normal;">
              <span>MAKE</span>
              <text style="margin-bottom: -4px; width: 130px;display: inline-block;">{{ isset($data['vehicle']['make']) ? $data['vehicle']['make'] : 'N/A' }}</text>

              <span style="width: 48px;">MODEL</span>
              <text style="margin-bottom: -4px; width: 130px;display: inline-block;">{{ isset($data['vehicle']['model']) ? $data['vehicle']['model'] : 'N/A' }}</text>
              
              <span style="width: 75px;">BODY TYPE</span>
              <text style="margin-bottom: -4px; width: 130px;display: inline-block;">{{ isset($data['vehicle']['body_type']) ? $data['vehicle']['body_type'] : 'N/A' }}</text>
            </div>
            <div style="white-space: normal;">
              <span>TRIM</span>
              <text style="margin-bottom: -4px; width: 130px;display: inline-block;">{{ isset($data['vehicle']['trim']) ? $data['vehicle']['trim'] : 'N/A' }}</text>

              <span style="width: 48px;">COLOR</span>
              <text style="margin-bottom: -4px; width: 130px;display: inline-block;">{{ isset($data['vehicle']['color']) ? $data['vehicle']['color'] : 'N/A' }}</text>
            </div>
          </div>
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
          @if (!empty($data['product_items']))
          @foreach ($data['product_items'] as $key => $value)
          <tr>
            <td class="service" style="padding: 10px 5px;vertical-align: middle;">{{ $value['quantity'] }}</td>
            <td class="service" style="padding: 10px 5px;">{{ $value['product']['identification_code'] }}</td>
            <td class="service" style="padding: 10px 5px;">{{ isset($value['product']['upc']) && $value['product']['upc'] ? $value['product']['upc'] : '--' }}</td>
            <td class="service" style="padding: 10px 5px;">{{ $value['product']['name'] }}</td>
            <td class="service" style="padding: 10px 5px;">{{ $value['product']['manufacturer'] }}</td>
            <td class="service" style="padding: 10px 5px;vertical-align: middle;">{{ ucwords($value['product']['type']) }}</td>

            <td style="padding: 10px 5px;text-align: right;">${{ $value['base_price'] ? number_format($value['base_price'],2) : 0.00 }}</td>
            <td style="padding: 10px 5px;text-align: right;">${{ number_format(($value['base_price'] ? $value['base_price'] : 0.00) * $value['quantity'], 2) }}</td>
          </tr>
          @endforeach
          @else
          <tr><td style="text-align: center;padding:10px 0;color: #717171;" colspan="10">No product added yet</td></tr>
          @endif


          @php
              $baseTaxAmount = $data['tax'] > 0 && $data['base_total'] > 0 ? ($data['tax'] / 100) * $data['base_total'] : 0;
          @endphp


          @if($data['tax'])
          @endif
          <tr style="border-top: 1px solid #c1ced9;">
            <td style="padding-top: 10px;" colspan="7">TAX</td>
            <td style="padding-top: 10px;" class="xtotal">{{ isset($data['tax']) ? $data['tax'] : 0.00 }}%</td>
          </tr>

          @if($data['convenience_fee'])
          @endif
          <tr>
            <td style="padding-top: 10px;" colspan="7">Convenience Fee</td>
            <td style="padding-top: 10px;" class="xtotal">${{ isset($data['convenience_fee']) ? number_format($data['convenience_fee'],2) : 0.00 }}</td>
          </tr>

          @if($data['discount'])
          <tr>
            <td style="padding-top: 10px;" colspan="7">Discount</td>
            <td style="padding-top: 10px;" class="xtotal">${{ isset($data['discount']) ? number_format($data['discount'],2) : 0.00 }}</td>
          </tr>
          @endif

          <tr>
            <td style="padding-top: 10px;" colspan="7">Total</td>
            <td style="padding-top: 10px;" class="xtotal">${{ isset($data['base_total']) ? number_format($data['base_total'] + $baseTaxAmount + $data['convenience_fee'], 2) : 0.00 }}</td>
          </tr>
          <tr>
            <td style="padding-top: 5px;" colspan="7">Paid Amount</td>
            <td style="padding-top: 5px;" class="xtotal">${{ isset($data['paid_amount']) ? number_format($data['paid_amount'],2) : 0.00 }}</td>
          </tr>
          <tr>
            <td style="padding-top: 5px;" colspan="7">Due Amount</td>
            <td style="padding-top: 5px;" class="xtotal">${{ number_format(($data['base_total'] + $baseTaxAmount + $data['convenience_fee'])-$data['paid_amount'], 2) }}</td>
          </tr>
          {{-- <tr>
            <td colspan="8" class="grand total">GRAND TOTAL</td>
            <td class="grand total">${{ $data->subtotal }}</td>
          </tr> --}}
        </tbody>
      </table>


      <div id="notices" style="margin-top: 50px;">
        {{-- <div>NOTICE:</div>
        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div> --}}
      </div>
    </main>

    {{-- <footer>Invoice was created on a computer and is valid without the signature and seal.</footer> --}}
  </body>
</html>