<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('invoice.create'))
                <a class="nav-item nav-link" href="{{ route('admin.invoice.create') }}" data-bread='create'><span>&nbsp;</span> Create</a>
                @endif
                @if (Auth::user()->can('invoice.download') || Auth::user()->can('invoice.edit') || Auth::user()->can('invoice.delete'))
                <a class="nav-item nav-link" href="{{ route('admin.invoice.records') }}" data-bread='records'><span>&nbsp;</span> Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->