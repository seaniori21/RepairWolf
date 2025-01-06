<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('account.create'))
                <a class="nav-item nav-link" href="{{ route('adminCreateAccount') }}" data-bread='create_account'><span>&nbsp;</span> Create</a>
                @endif
                
                @if (Auth::user()->can('account.history') || Auth::user()->can('account.view') || Auth::user()->can('account.edit') || Auth::user()->can('account.delete'))
                <a class="nav-item nav-link" href="{{ route('adminRecordsPage') }}" data-bread='all_account'><span>&nbsp;</span> Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->