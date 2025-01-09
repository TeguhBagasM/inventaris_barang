<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
    <div class="sidenav-header">
      <a class="navbar-brand m-0" href="#" target="">
        <img src="{{ asset('assets/img/logo-smk.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Inventaris Barang</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Dashboard' ? 'active' : '' }}" href="/dashboard">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-tachometer-alt text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
  
        @if (auth()->user()->level == 'siswa')
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Peminjaman Barang' ? 'active' : '' }}" href="/peminjaman">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-desktop text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Peminjam Barang</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Data Peminjaman' ? 'active' : '' }}" href="/detailPeminjaman">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-list-alt text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Detail Peminjaman</span>
          </a>
        </li>
        @elseif (auth()->user()->level == 'guru')
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Peminjaman Asset' ? 'active' : '' }}" href="/peminjaman">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-desktop text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Peminjam Asset</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Permintaan Barang' ? 'active' : '' }}" href="/permintaan">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-ruler-combined text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Permintaan Barang</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Data Peminjaman' ? 'active' : '' }}" href="/detailPeminjaman">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-file-signature text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Data Peminjaman</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Data Permintaan' ? 'active' : '' }}" href="/detailPemintaan">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-file text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Data Permintaan</span>
          </a>
        </li>
        @elseif (auth()->user()->level == 'petugas 1')
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Fitur Kelola</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Kelola Asset' ? 'active' : '' }}" href="/barang">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-box text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Kelola Asset</span>
          </a>
        </li>
        @elseif (auth()->user()->level == 'petugas 2')
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Fitur Kelola</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Logs Peminjaman' ? 'active' : '' }}" href="/log-peminjaman">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-history text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Logs Peminjaman</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Logs Permintaan' ? 'active' : '' }}" href="/log-permintaan">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-history text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Logs Permintaan</span>
          </a>
        </li>
        @elseif (auth()->user()->level == 'petugas 3')
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Kelola BHP' ? 'active' : '' }}" href="/bhp">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-box-open text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Kelola BHP</span>
          </a>
        </li>
        @endif
  
        @if (auth()->user()->level != 'siswa' && auth()->user()->level != 'guru')
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Daftar Tugas' ? 'active' : '' }}" href="/todolist">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-list-check text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Daftar Tugas</span>
          </a>
        </li>
        @endif
  
        @if (auth()->user()->level == 'admin')
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Fitur Kelola</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Kelola Asset' ? 'active' : '' }}" href="/barang">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-box text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Kelola Asset</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Kelola BHP' ? 'active' : '' }}" href="/bhp">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-box-open text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Kelola BHP</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Kelola Kategori' ? 'active' : '' }}" href="/kategori">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-tags text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Kelola Kategori</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Logs Peminjaman' ? 'active' : '' }}" href="/log-peminjaman">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-history text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Logs Peminjaman</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Logs Permintaan' ? 'active' : '' }}" href="/log-permintaan">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-history text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Logs Permintaan</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Fitur Admin</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Kelola User' ? 'active' : '' }}" href="/user">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-user text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Kelola User</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Kelola Gedung' ? 'active' : '' }}" href="/gedung">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-building text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Kelola Gedung</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title == 'Kelola Ruangan' ? 'active' : '' }}" href="/ruang">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-door-open text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">Kelola Ruangan</span>
          </a>
        </li>
        @endif
      </ul>
    </div>
    <div class="sidenav-footer mx-3" style="margin-top: 25px">
      <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
        <div class="full-background" style="background-image: url('../assets/img/curved-images/white-curved.jpg')"></div>
        <div class="card-body text-start p-3 w-100">
          <div class="docs-info">
            <h6 class="text-white up mb-0 text-capitalize">Halo, {{ auth()->user()->level }}</h6>
            @php
            $level = auth()->user()->level;
            @endphp
  
            @if (auth()->user()->level == 'admin')
            <p class="text-xs font-weight-bold">Mulai kelola semua data dan pantau aktivitas mereka dengan mudah.</p>
            @elseif(auth()->user()->level == 'petugas 1')
            <p class="text-xs font-weight-bold">Mulai kelola data asset dengan mudah disini.</p>
            @elseif(auth()->user()->level == 'petugas 2')
            <p class="text-xs font-weight-bold">Mulai kelola peminjaman dan pengembalian barang dengan mudah disini.</p>
            @elseif(auth()->user()->level == 'petugas 3')
            <p class="text-xs font-weight-bold">Mulai kelola data barang habis pakai dengan mudah disini.</p>
            @elseif(auth()->user()->level == 'guru')
            <p class="text-xs font-weight-bold">Mulai ajukan peminjaman asset dan permintaan barang dengan mudah disini.</p>
            @else
            <p class="text-xs font-weight-bold">Mulai ajukan permintaan asset dengan mudah disini.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </aside>