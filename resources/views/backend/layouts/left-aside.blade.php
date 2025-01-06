<aside class="column aside">
    <div class="brand">
        <span title="Company name" style="position: relative;">
            TW Auto Shop
            <img style="position: absolute;bottom: 3px;right: 0px;height: 22px;width: 111px;" src="{{ asset('assets/images/logo/logo_slogan.png') }}" width="10" xheight="10" class="xmb-3 shadow-lg" height="100" alt="">
        </span>

        <a href="javascript:void(0)" id="aside-close">
            <i class="icon ion-ios-close io-36"></i>
        </a>
    </div>

    <!-- aside nav start -->
    {{-- @can('primary') --}}
    <nav class="aside-nav">
        <h6>Navigation</h6>
        <ul>
            <!-- dashboard -->
            <li id="dashboard">
                <a href="{{ route('admin.home') }}">
                    <i class="icon ion-ios-speedometer"></i>
                    <span title="Dashboard">Dashboard</span>
                </a>
            </li>
        </ul>

        <!-- order -->
        @if(Auth::user()->can('order.history') || Auth::user()->can('order.receipt') || Auth::user()->can('order.create') || Auth::user()->can('order.view') || Auth::user()->can('order.edit') || Auth::user()->can('order.delete'))
        <h6>Order</h6>
        <ul>
            @if(Auth::user()->can('order.create'))
            <li id="orderCreate">
                <a href="{{ route('admin.order.create') }}">
                    <i class="icon ion-android-add-circle"></i>
                    <span title="Add Order">Add Order</span>
                </a>
            </li>
            @endif

            @if(Auth::user()->can('order.history') || Auth::user()->can('order.receipt') || Auth::user()->can('order.view') || Auth::user()->can('order.edit') || Auth::user()->can('order.delete'))
            <li id="orderRecords">
                <a href="{{ route('admin.order.records') }}">
                    <i class="icon ion-android-document"></i>
                    <span title="Order Records">Order Records</span>
                </a>
            </li>
            @endif
        </ul>
        @endif
        <!-- order -->

        <!-- data -->
        @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.create') || Auth::user()->can('customer.view') || Auth::user()->can('customer.edit') || Auth::user()->can('customer.delete') || Auth::user()->can('vehicle.history') || Auth::user()->can('vehicle.create') || Auth::user()->can('vehicle.view') || Auth::user()->can('vehicle.edit') || Auth::user()->can('vehicle.delete') || Auth::user()->can('product.history') || Auth::user()->can('product.create') || Auth::user()->can('product.view') || Auth::user()->can('product.edit') || Auth::user()->can('product.delete') || Auth::user()->can('paymentType.history') || Auth::user()->can('paymentType.create') || Auth::user()->can('paymentType.view') || Auth::user()->can('paymentType.edit') || Auth::user()->can('paymentType.delete') || Auth::user()->can('payment.history') || Auth::user()->can('payment.create') || Auth::user()->can('payment.view') || Auth::user()->can('payment.edit') || Auth::user()->can('payment.delete') )
        <h6>Data</h6>
        <ul>
            @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.create') || Auth::user()->can('customer.view') || Auth::user()->can('customer.edit') || Auth::user()->can('customer.delete') )
            <li id="customer" class="dropdown {{ $menu == 'customer' ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="icon ion-ios-paper"></i>
                    <span title="Customer">Customer</span>
                    <span class="float-end">
                        <i class="icon ion-ios-arrow-forward right"></i>
                        <i class="icon ion-ios-arrow-down down"></i>
                    </span>
                </a>
                <ul>
                    @if (Auth::user()->can('customer.create'))
                    <li id="customer-create"><a href="{{ route('customerCreate') }}" title="Create Customer">Create</a></li>
                    @endif

                    @if (Auth::user()->can('customer.history') || Auth::user()->can('customer.view') || Auth::user()->can('customer.edit') || Auth::user()->can('customer.delete'))
                    <li id="customer-records"><a href="{{ route('customerRecordsPage') }}" title="All Customers">Records</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if (Auth::user()->can('vehicle.history') || Auth::user()->can('vehicle.create') || Auth::user()->can('vehicle.view') || Auth::user()->can('vehicle.edit') || Auth::user()->can('vehicle.delete') )
            <li id="vehicle" class="dropdown {{ $menu == 'vehicle' ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="icon ion-ios-paper"></i>
                    <span title="Vehicle">Customer Vehicle</span>
                    <span class="float-end">
                        <i class="icon ion-ios-arrow-forward right"></i>
                        <i class="icon ion-ios-arrow-down down"></i>
                    </span>
                </a>
                <ul>
                    @if (Auth::user()->can('vehicle.create'))
                    <li id="vehicle-create"><a href="{{ route('vehicleCreate') }}" title="Create Vehicle">Create</a></li>
                    @endif

                    @if (Auth::user()->can('vehicle.history') || Auth::user()->can('vehicle.view') || Auth::user()->can('vehicle.edit') || Auth::user()->can('vehicle.delete'))
                    <li id="vehicle-records"><a href="{{ route('vehicleRecordsPage') }}" title="All Vehicles">Records</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if (Auth::user()->can('product.history') || Auth::user()->can('product.create') || Auth::user()->can('product.view') || Auth::user()->can('product.edit') || Auth::user()->can('product.delete') )
            <li id="product" class="dropdown {{ $menu == 'product' ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="icon ion-ios-paper"></i>
                    <span title="Product">Product</span>
                    <span class="float-end">
                        <i class="icon ion-ios-arrow-forward right"></i>
                        <i class="icon ion-ios-arrow-down down"></i>
                    </span>
                </a>
                <ul>
                    @if (Auth::user()->can('product.create'))
                    <li id="product-create"><a href="{{ route('productCreate') }}" title="Create Product">Create</a></li>
                    @endif

                    @if (Auth::user()->can('product.history') || Auth::user()->can('product.view') || Auth::user()->can('product.edit') || Auth::user()->can('product.delete'))
                    <li id="product-records"><a href="{{ route('productRecordsPage') }}" title="All Products">Records</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if (Auth::user()->can('paymentType.history') || Auth::user()->can('paymentType.create') || Auth::user()->can('paymentType.view') || Auth::user()->can('paymentType.edit') || Auth::user()->can('paymentType.delete') || Auth::user()->can('payment.history') || Auth::user()->can('payment.create') || Auth::user()->can('payment.view') || Auth::user()->can('payment.edit') || Auth::user()->can('payment.delete') )
            <li id="payment" class="dropdown {{ $menu == 'payment' ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="icon ion-ios-paper"></i>
                    <span title="Payment">Payment</span>
                    <span class="float-end">
                        <i class="icon ion-ios-arrow-forward right"></i>
                        <i class="icon ion-ios-arrow-down down"></i>
                    </span>
                </a>
                <ul>
                    @if (Auth::user()->can('paymentType.history') || Auth::user()->can('paymentType.create') || Auth::user()->can('paymentType.view') || Auth::user()->can('paymentType.edit') || Auth::user()->can('paymentType.delete'))
                    <li id="payment-types"><a href="{{ route('paymentTypeRecordsPage') }}" title="Payment Types">Payment Types</a></li>
                    @endif

                    @if (Auth::user()->can('payment.create'))
                    <li id="payment-create"><a href="{{ route('paymentCreate') }}" title="Create Payment">Create Payment</a></li>
                    @endif

                    @if (Auth::user()->can('payment.history') || Auth::user()->can('payment.view') || Auth::user()->can('payment.edit') || Auth::user()->can('payment.delete'))
                    <li id="payment-records"><a href="{{ route('paymentRecordsPage') }}" title="All Payments">Payment Records</a></li>
                    @endif                    
                </ul>
            </li>
            @endif
        </ul>
        @endif
        <!-- data -->

        <!-- stystem -->
        @if(Auth::user()->can('notification.create') || Auth::user()->can('notification.view') || Auth::user()->can('notification.delete') || Auth::user()->can('ipallowlist.history') || Auth::user()->can('ipallowlist.create') || Auth::user()->can('ipallowlist.view') || Auth::user()->can('ipallowlist.edit') || Auth::user()->can('ipallowlist.delete') || Auth::user()->can('account.history') || Auth::user()->can('account.create') || Auth::user()->can('account.view') || Auth::user()->can('account.edit') || Auth::user()->can('account.delete') || Auth::user()->can('roles.create') || Auth::user()->can('roles.view') || Auth::user()->can('roles.edit') || Auth::user()->can('roles.delete') || Auth::user()->can('permissions.create') || Auth::user()->can('permissions.view') || Auth::user()->can('permissions.edit') || Auth::user()->can('permissions.delete'))
        <h6>System</h6>

        <ul>
            @if (Auth::user()->can('ipallowlist.history') || Auth::user()->can('ipallowlist.create') || Auth::user()->can('ipallowlist.view') || Auth::user()->can('ipallowlist.edit') || Auth::user()->can('ipallowlist.delete') )
            <li id="IpAllowList" class="d-none">
                <a href="{{ route('ipAllowListRecordsPage') }}">
                    <i class="icon ion-locked"></i>
                    <span title="IP Allow List">IP Allow List</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->can('notification.create') || Auth::user()->can('notification.view') || Auth::user()->can('notification.delete') )
            <li id="notification" class="dropdown {{ $menu == 'notification' ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="icon ion-ios-bell"></i>
                    <span title="Notification">Notification</span>
                    <span class="float-end">
                        <i class="icon ion-ios-arrow-forward right"></i>
                        <i class="icon ion-ios-arrow-down down"></i>
                    </span>
                </a>
                <ul>
                    @if (Auth::user()->can('notification.create'))
                    <li id="notification-create"><a href="{{ route('notificationCreate') }}" title="Send Notification">Send</a></li>
                    @endif

                    @if (Auth::user()->can('notification.history') || Auth::user()->can('notification.view') || Auth::user()->can('notification.edit') || Auth::user()->can('notification.delete'))
                    <li id="notification-records"><a href="{{ route('notificationRecordsPage') }}" title="All Notification">Records</a></li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Accounts -->
            @if (Auth::user()->can('account.history') || Auth::user()->can('account.create') || Auth::user()->can('account.view') || Auth::user()->can('account.edit') || Auth::user()->can('account.delete') )
            <li id="mngadmin" class="dropdown {{ $menu == 'mngadmin' ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="icon ion-android-person"></i>
                    <span title="Accounts">Accounts</span>
                    <span class="float-end">
                        <i class="icon ion-ios-arrow-forward right"></i>
                        <i class="icon ion-ios-arrow-down down"></i>
                    </span>
                </a>
                <ul>
                    @if (Auth::user()->can('account.create'))
                    <li id="mngadmin-create"><a href="{{ route('adminCreateAccount') }}" title="Create New Admin">Create</a></li>
                    @endif

                    @if (Auth::user()->can('account.history') || Auth::user()->can('account.view') || Auth::user()->can('account.edit') || Auth::user()->can('account.delete'))
                    <li id="mngadmin-records"><a href="{{ route('adminRecordsPage') }}" title="All Admin">Records</a></li>
                    @endif
                </ul>
            </li>
            @endif
            <!-- Accounts -->

            <!-- Authorization -->
            @if (Auth::user()->can('roles.create') || Auth::user()->can('roles.view') || Auth::user()->can('roles.edit') || Auth::user()->can('roles.delete') || Auth::user()->can('permissions.create') || Auth::user()->can('permissions.view') || Auth::user()->can('permissions.edit') || Auth::user()->can('permissions.delete') )
            <li id="authorization" class="dropdown {{ $menu == 'authorization' ? 'active' : '' }}">
                <a href="javascript:void(0)">
                    <i class="icon ion-android-lock"></i>
                    <span title="Authorization">Authorization</span>
                    <span class="float-end">
                        <i class="icon ion-ios-arrow-forward right"></i>
                        <i class="icon ion-ios-arrow-down down"></i>
                    </span>
                </a>
                <ul>
                    @if (Auth::user()->can('roles.create') || Auth::user()->can('roles.view') || Auth::user()->can('roles.edit') || Auth::user()->can('roles.delete'))
                    <li id="authorization-roles"><a href="{{ route('adminRoles') }}" title="All Admin">Roles</a></li>
                    @endif

                    @if (Auth::user()->can('permissions.create') || Auth::user()->can('permissions.view') || Auth::user()->can('permissions.edit') || Auth::user()->can('permissions.delete'))
                    {{-- <li id="authorization-permissions"><a href="{{ route('adminPermission') }}" title="Permissions">Permissions</a></li> --}}
                    @endif
                </ul>
            </li>
            @endif
            <!-- Authorization -->
        </ul>
        @endif
        <!-- stystem -->

        @if (Auth::user()->can('profile.view') || Auth::user()->can('profile.edit-info') || Auth::user()->can('profile.edit-password') || Auth::user()->can('profile.edit-image') )
        <h6>Your Stuff</h6>
        @endif

        <ul>
            <!-- profile -->
            @if (Auth::user()->can('profile.view') || Auth::user()->can('profile.edit-info') || Auth::user()->can('profile.edit-password') || Auth::user()->can('profile.edit-image'))
            <li id="profile">
                <a href="{{ route('adminProfile') }}">
                    <i class="icon ion-ios-contact"></i>
                    <span title="Profile">Profile</span>
                    {{-- <span class="badge float-end">30%</span> --}}
                </a>
            </li>
            @endif
        </ul>
    </nav>
    {{-- @endcan --}}
</aside>