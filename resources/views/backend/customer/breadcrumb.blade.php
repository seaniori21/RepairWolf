<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('customer.create'))
                <a class="nav-item nav-link" href="{{ route('customerCreate') }}" data-bread='create'><span>&nbsp;</span> Create</a>
                @endif
                @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.view') || Auth::user()->can('customer.edit') || Auth::user()->can('customer.delete'))
                <a class="nav-item nav-link" href="{{ route('customerRecordsPage') }}" data-bread='records'><span>&nbsp;</span> Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->