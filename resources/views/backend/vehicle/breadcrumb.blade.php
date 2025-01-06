<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('vehicle.create'))
                <a class="nav-item nav-link" href="{{ route('vehicleCreate') }}" data-bread='create'><span>&nbsp;</span> Create</a>
                @endif
                @if (Auth::user()->can('vehicle.history') || Auth::user()->can('vehicle.view') || Auth::user()->can('vehicle.edit') || Auth::user()->can('vehicle.delete'))
                <a class="nav-item nav-link" href="{{ route('vehicleRecordsPage') }}" data-bread='records'><span>&nbsp;</span> Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->