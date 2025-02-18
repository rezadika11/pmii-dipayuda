<div class="sidebar" data-background-color="white">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="white">
        <a href="index.html" class="logo">
            <img
           src="{{ asset('assets/img/logo-pmii.png') }}"
            alt="navbar brand"
            class="navbar-brand"
            height="40"
            />
        </a>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
            <i class="gg-menu-left"></i>
            </button>
        </div>
        <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
        </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ request()->routeIs('dashboard*') ? 'active' : '' }} ">
                    <a href="{{ route('dashboard') }}">
                      <i class="bi bi-grid"></i>
                      <p>Dashboard</p>
                    </a>
                  </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('posts*')  || request()->routeIs('category*') || request()->routeIs('tags*') ? 'active submenu' : '' }} ">
                    <a data-bs-toggle="collapse" href="#base">
                        <i class="bi bi-pin-angle"></i>
                        <p>Posts</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('posts*') || request()->routeIs('category*') || request()->routeIs('tags*') ? 'show' : '' }}" id="base">
                        <ul class="nav nav-collapse">
                        <li class="{{ request()->routeIs('posts.index', 'posts.edit') ? 'active' : '' }}">
                            <a href="{{ route('posts.index') }}">
                            <span class="sub-item">Semua Pos</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('posts.create') ? 'active' : '' }}">
                            <a href="{{ route('posts.create') }}">
                            <span class="sub-item">Tambah Pos Baru</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('category*') ? 'active' : '' }}">
                            <a href="{{ route('category.index') }}">
                            <span class="sub-item">Kategori</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('tags*') ? 'active' : '' }}">
                            <a href="{{ route('tags.index') }}">
                            <span class="sub-item">Tag</span>
                            </a>
                        </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->routeIs('users*') ? 'active' : '' }} ">
                    <a href="{{ route('users.index') }}">
                      <i class="bi bi-person-gear"></i>
                      <p>User</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>