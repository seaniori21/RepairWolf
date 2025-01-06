<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('ipallowlist.history') || Auth::user()->can('ipallowlist.create') || Auth::user()->can('ipallowlist.view') || Auth::user()->can('ipallowlist.edit') || Auth::user()->can('ipallowlist.delete'))
                <a class="nav-item nav-link" href="{{ route('ipAllowListRecordsPage') }}" data-bread='records'><span>&nbsp;</span> Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->