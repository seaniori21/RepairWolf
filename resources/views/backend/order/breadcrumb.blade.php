<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('order.create'))
                <a class="nav-item nav-link" href="{{ route('admin.order.create') }}" data-bread='create'><span>&nbsp;</span> Add Order</a>
                @endif
                @if (Auth::user()->can('order.history') || Auth::user()->can('order.view') || Auth::user()->can('order.receipt') || Auth::user()->can('order.edit') || Auth::user()->can('order.delete'))
                <a class="nav-item nav-link" href="{{ route('admin.order.records') }}" data-bread='records'><span>&nbsp;</span> Order Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->