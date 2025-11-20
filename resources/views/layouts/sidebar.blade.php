<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="true">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ Route::is('home') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item has-treeview {{ Route::is('product.*') || Route::is('price.*') || Route::is('single.*') || Route::is('pricesingle.*') || Route::is('customer.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('product.*') || Route::is('price.*') || Route::is('single.*') || Route::is('pricesingle.*') || Route::is('customer.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        Master
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('product.index') }}" class="nav-link {{ Route::is('product.*') ? 'active' : '' }}">
                            <i class="fas fa-boxes nav-icon"></i>
                            <p>Product</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('price.index') }}" class="nav-link {{ Route::is('price.*') ? 'active' : '' }}">
                            <i class="fas fa-dollar nav-icon"></i>
                            <p>Price</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview {{ (Route::is('pricesingle.*') || Route::is('single.*')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cookie-bite"></i>
                            <p>
                                Single
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('single.index') }}" class="nav-link {{ Route::is('single.*') ? 'active' : '' }}">
                                    <i class="fas fa-cookie-bite nav-icon"></i>
                                    <p>Single Item</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pricesingle.index') }}" class="nav-link {{ Route::is('pricesingle.*') ? 'active' : '' }}">
                                    <i class="fas fa-dollar nav-icon"></i>
                                    <p>Price Single</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.index') }}" class="nav-link {{ Route::is('customer.*') ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            <p>Customer</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ (Route::is('invoice.*') || Route::is('tt.*')) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (Route::is('invoice.*') || Route::is('tt.*')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-invoice"></i>
                    <p>
                        Form
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('invoice.index') }}" class="nav-link {{ (Route::is('invoice.*') && !Route::is('invoice.create')) ? 'active' : '' }}">
                            <i class="fas fa-file-invoice nav-icon"></i>
                            <p>Invoice</p>
                        </a>
                        <a href="{{ route('invoice.create') }}" class="nav-link {{ Route::is('invoice.create') ? 'active' : '' }}">
                            <i class="fas fa-plus nav-icon"></i>
                            <p>Create Invoice</p>
                        </a>
                        <a href="{{ route('tt.index') }}" class="nav-link {{ (Route::is('tt.*')) ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar nav-icon"></i>
                            <p>Tanda Terima</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ (Route::is('gaji.*') || Route::is('pegawai.*')) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (Route::is('gaji.*') || Route::is('pegawai.*')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-sack-dollar"></i>
                    <p>
                        Gaji
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('gaji.index') }}" class="nav-link {{ (Route::is('gaji.*')) ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar nav-icon"></i>
                            <p>Gaji</p>
                        </a>
                        <a href="{{ route('pegawai.index') }}" class="nav-link {{ (Route::is('pegawai.*')) ? 'active' : '' }}">
                            <i class="fas fa-user-tag nav-icon"></i>
                            <p>Gaji Karyawan</p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>