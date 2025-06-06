@php
    $menus = [
        [
            'name' => 'Dashboard',
            'route' => '/dashboard',
            'routeName' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt'
        ],
        [
            'name' => 'Tahun Ajaran',
            'route' => '/periode',
            'routeName' => 'periode',
            'icon' => 'fas fa-calendar-alt'
        ],
        [
            'name' => 'Mata Pelajaran',
            'route' => '/mapel',
            'routeName' => 'mapel',
            'icon' => 'fas fa-book-open'
        ],
        [
            'name' => 'Jurusan',
            'route' => '/jurusan',
            'routeName' => 'jurusan',
            'icon' => 'fas fa-university'
        ],
        [
            'name' => 'Kelas Jurusan',
            'route' => '/kelas_jurusan',
            'routeName' => 'kelas_jurusan',
            'icon' => 'fas fa-chalkboard'
        ],
        [
            'name' => 'Data Siswa',
            'route' => '/siswa',
            'routeName' => 'siswa',
            'icon' => 'fas fa-users'
        ],
        [
            'name' => 'Data Guru',
            'route' => '/guru',
            'routeName' => 'guru',
            'icon' => 'fas fa-user-tie'
        ],
        [
            'name' => 'KBM',
            'route' => '/kbm',
            'routeName' => 'kbm',
            'icon' => 'fas fa-chalkboard-teacher'
        ],
        [
            'name' => 'Laporan Presensi',
            'route' => '/presensi',
            'routeName' => 'presensi',
            'icon' => 'fas fa-clipboard-check'
        ],
        [
            'name' => 'Ganti Password',
            'route' => '/ganti_password',
            'routeName' => 'ganti_password',
            'icon' => 'fas fa-key'
        ],
        [
            'name' => 'Keluar',
            'route' => '/logout',   
            'routeName' => '',
            'icon' => 'fas fa-sign-out-alt'
        ],
       
    ];

    $menuGuru = [
        [
            'name' => 'Dashboard',
            'route' => '/guru/dashboard',
            'routeName' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt'
        ],
        [
            'name' => 'Presensi',
            'route' => '/guru/kbm',
            'routeName' => 'kbm',
            'icon' => 'fas fa-clipboard-check'
        ],
        [
            'name' => 'Laporan Presensi',
            'route' => '/guru/presensi',
            'routeName' => 'presensi',
            'icon' => 'fas fa-clipboard'
        ],
        [
            'name' => 'Ganti Password',
            'route' => '/ganti_password',
            'routeName' => 'ganti_password',
            'icon' => 'fas fa-key'
        ],
        [
            'name' => 'Keluar',
            'route' => '/logout',   
            'routeName' => '',
            'icon' => 'fas fa-sign-out-alt'
        ],
    ];

    $menuSiswa = [
        [
            'name' => 'Dashboard',
            'route' => '/siswa/dashboard',
            'routeName' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt'
        ],
        [
            'name' => 'Presensi',
            'route' => '/siswa/presensi',
            'routeName' => 'presensi',
            'icon' => 'fas fa-clipboard-check'
        ],
        [
            'name' => 'Ganti Password',
            'route' => '/ganti_password',
            'routeName' => 'ganti_password',
            'icon' => 'fas fa-key'
        ],
        [
            'name' => 'Keluar',
            'route' => '/logout',   
            'routeName' => '',
            'icon' => 'fas fa-sign-out-alt'
        ],
    ];
    
@endphp
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        
            @if(Auth::user()->role == 'siswa')
             @foreach ($menuSiswa as $menu)
                <li class="nav-item">
                    <a href="{{ $menu['route'] }}" class="nav-link {{ request()->is(ltrim($menu['route'], '/') . '*') ? 'active' : '' }}">
                        <i class="nav-icon {{ $menu['icon'] }}"></i>
                        <p>
                            {{ $menu['name'] }}
                        </p>
                    </a>
                </li>
            @endforeach
            @elseif (Auth::user()->role == 'guru')
             @foreach ($menuGuru as $menu)
                <li class="nav-item">
                    <a href="{{ $menu['route'] }}" class="nav-link {{ request()->is(ltrim($menu['route'], '/') . '*') ? 'active' : '' }}">
                        <i class="nav-icon {{ $menu['icon'] }}"></i>
                        <p>
                            {{ $menu['name'] }}
                        </p>
                    </a>
                </li>
            @endforeach
            @else
             @foreach ($menus as $menu)
                <li class="nav-item">
                    <a href="{{ $menu['route'] }}" class="nav-link {{ request()->is(ltrim($menu['route'], '/') . '*') ? 'active' : '' }}">
                        <i class="nav-icon {{ $menu['icon'] }}"></i>
                        <p>
                            {{ $menu['name'] }}
                        </p>
                    </a>
                </li>
            @endforeach
            @endif

           
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>