<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('product.create'))
                <a class="nav-item nav-link" href="{{ route('productCreate') }}" data-bread='create'><span>&nbsp;</span> Create</a>
                @endif
                @if (Auth::user()->can('product.history') || Auth::user()->can('product.view') || Auth::user()->can('product.edit') || Auth::user()->can('product.delete'))
                <a class="nav-item nav-link" href="{{ route('productRecordsPage') }}" data-bread='records'><span>&nbsp;</span> Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->