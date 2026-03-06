<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="e-HATi — Employee Health Information System">
    <meta name="author" content="KPPN Pangkalan Bun">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>e-HATi | {{ $title }}</title>

    <link rel="icon" href="{{ asset('sbadmin2/img/icon_e-hati_v3.svg') }}" type="image/svg+xml">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    {{-- FontAwesome --}}
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    {{-- SB Admin 2 base (needed for Bootstrap grid + utils) --}}
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">

    {{-- DataTables --}}
    <link href="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    {{-- e-HATi Layout (new) --}}
    <link href="{{ asset('css/ehati-layout.css') }}" rel="stylesheet">

    {{-- Page-specific CSS --}}
    <link href="{{ asset('css/pemeriksaan.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pegawai.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body id="page-top">

    {{-- Hidden SVG gradient defs (for progress rings etc.) --}}
    <svg style="position:absolute;width:0;height:0;overflow:hidden;" aria-hidden="true">
        <defs>
            <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%"   style="stop-color:#36b9cc;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#1cc88a;stop-opacity:1" />
            </linearGradient>
        </defs>
    </svg>

    {{-- ═══════════════════════════════════════════
          MOBILE OVERLAY (behind open sidebar)
    ═══════════════════════════════════════════ --}}
    <div class="ehl-overlay" id="ehlOverlay"></div>

    {{-- ═══════════════════════════════════════════
          SIDEBAR
          NOTE: headings & dividers are OUTSIDE <ul>
          to avoid invalid HTML + spacing bugs
    ═══════════════════════════════════════════ --}}
    <aside class="ehl-sidebar" id="ehlSidebar">

        {{-- Brand --}}
        <a class="ehl-brand" href="{{ route('dashboard') }}">
            {{-- Full logo — shown when expanded --}}
            <img class="ehl-brand-logo-full"
                  src="{{ asset('sbadmin2/img/logo_e-hati_v4.svg') }}"
                  alt="Logo e-HATi">
            {{-- Icon — shown when collapsed --}}
            <img class="ehl-brand-logo-icon"
                  src="{{ asset('sbadmin2/img/icon_e-hati_v3.svg') }}"
                  alt="e-HATi">
        </a>

        {{-- Divider --}}
        <hr class="ehl-nav-divider">

        {{-- ── Main nav group ── --}}
        <ul class="ehl-nav">
            <li class="ehl-nav-item {{ ($menuDashboard ?? '') === 'active' ? 'active' : '' }}">
                <a class="ehl-nav-link" href="{{ route('dashboard') }}">
                    <span class="ehl-nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="ehl-nav-label">Dashboard</span>
                </a>
            </li>
        </ul>

        {{-- Divider --}}
        <hr class="ehl-nav-divider">

        {{-- ── Manajemen heading (outside <ul>) ── --}}
        <div class="ehl-nav-heading">Manajemen</div>

        {{-- ── Manajemen nav group ── --}}
        <ul class="ehl-nav">
            <li class="ehl-nav-item {{ ($menuPegawai ?? '') === 'active' ? 'active' : '' }}">
                <a class="ehl-nav-link" href="{{ route('pegawai') }}">
                    <span class="ehl-nav-icon"><i class="fas fa-user"></i></span>
                    <span class="ehl-nav-label">Data Pegawai</span>
                </a>
            </li>

            <li class="ehl-nav-item {{ ($menuPemeriksaan ?? '') === 'active' ? 'active' : '' }}">
                <a class="ehl-nav-link" href="{{ route('pemeriksaan') }}">
                    <span class="ehl-nav-icon"><i class="fas fa-stethoscope"></i></span>
                    <span class="ehl-nav-label">Pemeriksaan</span>
                </a>
            </li>
        </ul>

        {{-- Push toggle to bottom --}}
        <div class="ehl-sidebar-spacer"></div>

        {{-- Desktop collapse button --}}
        <div class="ehl-sidebar-footer d-none d-md-flex">
            <button class="ehl-toggle-btn" id="ehlDesktopToggle" title="Toggle sidebar">
                <i class="fas fa-chevron-left ehl-toggle-icon" id="ehlToggleIcon"></i>
            </button>
        </div>

    </aside>
    {{-- End Sidebar --}}

    {{-- ═══════════════════════════════════════════
          BODY WRAP (shifts right of sidebar)
    ═══════════════════════════════════════════ --}}
    <div class="ehl-body-wrap" id="ehlBodyWrap">

        {{-- ── TOPBAR ── --}}
        <header class="ehl-topbar" id="ehlTopbar">

            {{-- Mobile hamburger --}}
            <button class="ehl-hamburger" id="ehlMobileToggle" aria-label="Open sidebar">
                <i class="fas fa-bars"></i>
            </button>

            {{-- Title --}}
            <div class="ehl-topbar-title-area">
                <span class="ehl-title-full">Employee Health Information</span>
                <span class="ehl-title-vline d-none d-md-block"></span>
                <span class="ehl-title-sub d-none d-md-block">KPPN Pangkalan Bun</span>
                <span class="ehl-title-short">e-HATi</span>
            </div>

            {{-- Right --}}
            <div class="ehl-topbar-actions">

                <div class="ehl-topbar-sep d-none d-sm-block"></div>

                {{-- Profile dropdown (Bootstrap 4 dropdown) --}}
                <div class="ehl-user-dropdown dropdown no-arrow">
                    <a class="ehl-profile-btn nav-link dropdown-toggle"
                        href="#"
                        id="ehlUserDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">

                        <img class="ehl-profile-avatar"
                              src="{{ asset('sbadmin2/img/user_kppn.png') }}"
                              alt="User">
                        <span class="ehl-profile-name d-none d-lg-inline">KPPN Pangkalan Bun</span>
                        <i class="fas fa-chevron-down ehl-profile-caret d-none d-sm-inline"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right ehl-dropdown-menu"
                            aria-labelledby="ehlUserDropdown">

                        <div class="ehl-dropdown-header">
                            <span class="ehl-dd-name">KPPN Pangkalan Bun</span>
                            <span class="ehl-dd-role">Administrator</span>
                        </div>

                        <a class="ehl-dropdown-item dropdown-item"
                            href="#"
                            data-toggle="modal"
                            data-target="#ehlLogoutModal">
                            <span class="ehl-dd-icon"><i class="fas fa-sign-out-alt"></i></span>
                            Logout
                        </a>
                    </div>
                </div>

            </div>
        </header>
        {{-- End Topbar --}}

        {{-- ── MAIN CONTENT ── --}}
        <main class="ehl-main" id="ehlContent">
            <div class="container-fluid p-0">
                @yield('content')
            </div>
        </main>

        {{-- ── FOOTER ── --}}
        <footer class="ehl-footer">
            <span class="ehl-footer-text">Copyright &copy; 2026</span>
            <span class="ehl-footer-dot"></span>
            <span class="ehl-footer-brand">KPPN Pangkalan Bun</span>
            <span class="ehl-footer-dot"></span>
            <span class="ehl-footer-text">e-HATi</span>
        </footer>

    </div>
    {{-- End Body Wrap --}}

    {{-- Scroll-to-top --}}
    <a class="ehl-scroll-top" href="#page-top" id="ehlScrollTop" aria-label="Scroll to top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- ═══════════════════════════════════════════
         LOGOUT MODAL
    ═══════════════════════════════════════════ --}}
    <div class="modal fade ehl-logout-modal"
          id="ehlLogoutModal"
          tabindex="-1"
          role="dialog"
          aria-labelledby="ehlLogoutLabel"
          aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="ehlLogoutLabel">
                        <i class="fas fa-sign-out-alt mr-2"></i>Konfirmasi Logout
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <i class="ehl-logout-icon fas fa-power-off"></i>
                    <p class="mb-0">Apakah Anda yakin ingin <strong>keluar</strong> dari sistem?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="ehl-btn-cancel" data-dismiss="modal">
                        Batal
                    </button>
                    <a href="#" class="ehl-btn-logout-confirm">
                        <i class="fas fa-sign-out-alt"></i> Ya, Logout
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
          SCRIPTS
    ═══════════════════════════════════════════ --}}
    <script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>

    <script src="{{ asset('sbadmin2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/js/demo/datatables-demo.js') }}"></script>

    <script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('js/dashboard-carousel.js') }}"></script>
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/bmi.js') }}"></script>
    <script src="{{ asset('js/bloodpressure.js') }}"></script>
    <script src="{{ asset('js/bloodsugar.js') }}"></script>
    <script src="{{ asset('js/cholesterol.js') }}"></script>
    <script src="{{ asset('js/uricacid.js') }}"></script>
    <script src="{{ asset('js/riwayat.js') }}"></script>
    <script src="{{ asset('js/pegawai.js') }}"></script>

    {{-- e-HATi Layout JS --}}
    <script>
    (function () {
        var sidebar     = document.getElementById('ehlSidebar');
        var bodyWrap    = document.getElementById('ehlBodyWrap');
        var topbar      = document.getElementById('ehlTopbar');
        var overlay     = document.getElementById('ehlOverlay');
        var mobileBtn   = document.getElementById('ehlMobileToggle');
        var desktopBtn  = document.getElementById('ehlDesktopToggle');
        var toggleIcon  = document.getElementById('ehlToggleIcon');
        var scrollBtn   = document.getElementById('ehlScrollTop');

        /* ── helpers ── */
        function applyCollapsed(collapsed) {
            var newLeft = collapsed ? 'var(--ehl-sidebar-col)' : 'var(--ehl-sidebar-w)';
            bodyWrap.style.marginLeft = newLeft;
            topbar.style.left        = newLeft;
            if (toggleIcon) toggleIcon.style.transform = collapsed ? 'rotate(180deg)' : '';
            /* CSS handles logo swap via .is-collapsed on sidebar */
        }

        /* ── Desktop collapse/expand ── */
        if (desktopBtn) {
            // Restore saved state on every page load
            if (localStorage.getItem('ehl_collapsed') === '1') {
                sidebar.classList.add('is-collapsed');
                applyCollapsed(true);
            }

            desktopBtn.addEventListener('click', function () {
                var collapsed = sidebar.classList.toggle('is-collapsed');
                applyCollapsed(collapsed);
                localStorage.setItem('ehl_collapsed', collapsed ? '1' : '0');
            });
        }

        /* ── Mobile open ── */
        function openSidebar() {
            sidebar.classList.add('is-open');
            overlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('is-open');
            overlay.classList.remove('is-active');
            document.body.style.overflow = '';
        }

        if (mobileBtn)  mobileBtn.addEventListener('click', openSidebar);
        if (overlay)    overlay.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSidebar();
        });

        /* ── Scroll-to-top ── */
        if (scrollBtn) {
            window.addEventListener('scroll', function () {
                scrollBtn.classList.toggle('is-visible', window.scrollY > 220);
            });
            scrollBtn.addEventListener('click', function (e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    })();
    </script>

    @stack('scripts')

    @session('success')
        <script>
            Swal.fire({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: '#36b9cc'
            });
        </script>
    @endsession

</body>
</html>
