<nav class="main-nav d-flex justify-content-between">
    <ul class="float-left ps-0">
        <li class="user-dropdown">
            <a href="Javascript:void(0)" id="aside-toggle">
                <i class="icon ion-android-menu io-23"></i>
            </a>
        </li>

        <li class="user-dropdown ms-2">
            <a href="Javascript:void(0)" class="full-screen" title="Enter Fullscreen Mode" id="full-screen-btn">
                <i class="icon ion-android-expand io-21"></i>
            </a>
        </li>
    </ul>

    <ul class="float-end">
        <!-- color -->
        <li class="user-dropdown ms-2">
            <a href="Javascript:void(0)" class="settings">
                <i class="icon ion-ios-settings io-21"></i>
            </a>
        </li>
        <!-- color -->

        <!-- profile -->
        <li class="user-dropdown ms-2">
            <a href="Javascript:void(0)" class="menu-button" style="line-height: 28px;">
                <img class="p-1" src="{{ asset(Auth::user()->image? '/upload/crop-files/'.Auth::user()->image:'assets/images/avatars/user-new.webp') }}" style="object-fit: cover;" id="top_nav_profile_image" alt="">
            </a>

            <ul class="sub-menu ps-0">
                <li class="head">
                    @if (Auth::user()->can('profile.view') || Auth::user()->can('profile.edit-info') || Auth::user()->can('profile.edit-password') || Auth::user()->can('profile.edit-image'))
                    <a href="{{ route('adminProfile') }}">
                        <h6>{{ Auth::user()->name }}</h6>
                        <small>{{ Auth::user()->email }}</small>
                    </a>
                    @else
                    <a href="Javascript:void(0)">
                        <h6>{{ Auth::user()->name }}</h6>
                        <small>{{ Auth::user()->email }}</small>
                    </a>
                    @endif
                </li>

                @if (Auth::user()->can('profile.view') || Auth::user()->can('profile.edit-info') || Auth::user()->can('profile.edit-password') || Auth::user()->can('profile.edit-image'))
                <li>
                    <a href="{{ route('adminProfile') }}">
                        <span>Profile</span>
                    </a>
                </li>
                @endif
                
                <li>
                    <a href="javascript:void(0)" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">@csrf</form>
                </li>

            </ul>
        </li>
        <!-- profile -->
    </ul>
</nav>