<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('notification.create'))
                <a class="nav-item nav-link" href="{{ route('notificationCreate') }}" data-bread='create'><span>&nbsp;</span> Create</a>
                @endif
                @if (Auth::user()->can('notification.view') || Auth::user()->can('notification.delete'))
                <a class="nav-item nav-link" href="{{ route('notificationRecordsPage') }}" data-bread='records'><span>&nbsp;</span> Records</a>
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->