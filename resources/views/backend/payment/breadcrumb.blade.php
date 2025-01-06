<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('paymentType.create'))
                <a class="nav-item nav-link" href="{{ route('paymentTypeRecordsPage') }}" data-bread='types'><span>&nbsp;</span> Types</a>
                @endif
                @if (Auth::user()->can('payment.create'))
                <a class="nav-item nav-link" href="{{ route('paymentCreate') }}" data-bread='create'><span>&nbsp;</span> Create</a>
                @endif
                @if (Auth::user()->can('payment.history') || Auth::user()->can('payment.view') || Auth::user()->can('payment.edit') || Auth::user()->can('payment.delete'))
                <a class="nav-item nav-link" href="{{ route('paymentRecordsPage') }}" data-bread='records'><span>&nbsp;</span> Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->