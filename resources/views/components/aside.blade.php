<aside id="sidebar" class="sidebar">
    <style>
    /* Hover color */
    .sidebar-nav .nav-link:hover {
        color: #AF1E23; /* Change text color on hover */
        background-color: rgba(175, 30, 35, 0.1); /* Optional: Add background color on hover */
    }
    .sidebar-nav .nav-link:hover i {
        color: #AF1E23; /* Change text color on hover */
    }

    /* Selected color */
    .sidebar-nav .nav-link.active {
        color: #AF1E23 !important; /* Force change text color for the selected item */
        background-color: rgba(175, 30, 35, 0.1); /* Optional: Add background color for the selected item */
    }

    .sidebar-nav .nav-link.active i {
        color: #AF1E23 !important; /* Force change icon color for the selected item */
    }

    </style>

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('dashboard*') ? 'active' : 'incorrect' }}" href="{{ route('admin.index') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        @foreach ($mainMenus as $menu)
            @if (!$menu->subMenus->isNotEmpty())
                @if (!Route::has($menu->route))
                    @if ($menu->title === 'Users' && !Auth::user()->isAdmin())
                        {{-- Skip rendering this item if the user is not an admin --}}
                        @continue
                    @endif
                    @if (Auth::user()->isEmployee() && in_array($menu->title, ['Employees', 'Department Report']))
                        {{-- Skip rendering these items for employees --}}
                        @continue
                    @endif
                    <li class="nav-item">
                        <a class="nav-link collapsed {{ request()->route()->getName() === $menu->route ? 'active' : 'incorrect' }}"
                            href="#">
                            <i class="{{ $menu->icon_class }}"></i>
                            <span>{{ $menu->title }}</span>
                        </a>
                    </li>
                @else
                    @if ($menu->title === 'Users' && !Auth::user()->isAdmin())
                        {{-- Skip rendering this item if the user is not an admin --}}
                        @continue
                    @endif
                    @if (Auth::user()->isEmployee() && in_array($menu->title, ['Employees', 'Department Report']))
                        {{-- Skip rendering these items for employees --}}
                        @continue
                    @endif
                    <li class="nav-item">
                        <a class="nav-link collapsed {{ request()->route()->getName() === $menu->route ? 'active' : 'incorrect' }}"
                            href="{{ isset($menu->route) ? route($menu->route) : '#' }}">
                            <i class="{{ $menu->icon_class }}"></i>
                            <span>{{ $menu->title }}</span>
                        </a>
                    </li>
                @endif
            @else
                @if ($menu->title === 'Users' && !Auth::user()->isAdmin())
                    {{-- Skip rendering this item if the user is not an admin --}}
                    @continue
                @endif
                @if (Auth::user()->isEmployee() && in_array($menu->title, ['Employees', 'Department Report']))
                    {{-- Skip rendering these items for employees --}}
                    @continue
                @endif
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#{{ $menu->route . '' . $menu->id }}"
                        data-bs-toggle="collapse" href="#">
                        <i class="{{ $menu->icon_class }}"></i><span>{{ $menu->title }}</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="{{ $menu->route . '' . $menu->id }}" class="nav-content collapse"
                        data-bs-parent="#sidebar-nav">
                        @foreach ($menu->subMenus as $submenu)
                            @if (Auth::user()->isEmployee() && in_array($submenu->title, ['Employees', 'Department Report']))
                                {{-- Skip rendering these items for employees --}}
                                @continue
                            @endif
                            <li>
                                <a href="{{ route($submenu->route) }}">
                                    <i class="{{ $submenu->icon_class }}"></i><span>{{ $submenu->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach

        <!-- Check if the user is an admin and if there is a menu item titled 'User' -->
        @if(Auth::user()->isAdmin())
            <li class="nav-heading">Pages</li>

            @foreach ($mainMenus as $menu)
                @if ($menu->title === 'User') <!-- Check for specific menu title -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route($menu->route) }}">
                            <i class="{{ $menu->icon_class }}"></i>
                            <span>{{ $menu->title }}</span>
                        </a>
                    </li>
                @endif
            @endforeach

            <!-- Other admin-specific menu items -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('users.create') }}">
                    <i class="bi bi-card-list"></i>
                    <span>Register</span>
                </a>
            </li><!-- End Register Page Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('menu.index') }}">
                    <i class="bi bi-card-list"></i>
                    <span>Main Menu Management</span>
                </a>
            </li>
        @endif

    </ul>

    </aside>
