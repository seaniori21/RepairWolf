<!-- breadcrumbs -->
<div class="breadcrumbs body-content pt-4">
    <div class="container">
        <div class="tabs-form">
            <nav class="nav nav-tabs">
                @if (Auth::user()->can('roles.create') || Auth::user()->can('roles.view') || Auth::user()->can('roles.edit') || Auth::user()->can('roles.delete'))
                <a class="nav-item nav-link" href="{{ route('adminRoles') }}" data-bread='roles'><span>&nbsp;</span> Roles</a>
                @endif

                @if (Auth::user()->can('permissions.create') || Auth::user()->can('permissions.view') || Auth::user()->can('permissions.edit') || Auth::user()->can('permissions.delete'))
                {{-- <a class="nav-item nav-link" href="{{ route('adminPermission') }}" data-bread='permissions'><span>&nbsp;</span> Permissions</a> --}}
                @endif
            </nav>
        </div>
    </div>
</div>
<!-- breadcrumbs -->