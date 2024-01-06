<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
            <img src="{{ asset('assets') }}/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bold text-white">Muma Pix</span>
        </a>
    </div>

    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            {{-- <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Laravel examples
                </h6>
            </li> --}}

            @if (auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'dashboard' ? ' active bg-gradient-primary' : '' }} "
                        href="{{ route('dashboard') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'pipeline-overview' ? ' active bg-gradient-primary' : '' }} "
                        href="{{ route('pipeline-overview') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">Pipeline Management</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'user-management' ? ' active bg-gradient-primary' : '' }} "
                        href="{{ route('user-management') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i style="font-size: 1.2rem;" class="fas fa-user-circle ps-2 pe-2 text-center"></i>
                        </div>
                        <span class="nav-link-text ms-1">User Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'completed-pipelines' ? ' active bg-gradient-primary' : '' }} "
                        href="{{ route('completed-pipelines') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i style="font-size: 1.2rem;" class="fas fa-user-circle ps-2 pe-2 text-center"></i>
                        </div>
                        <span class="nav-link-text ms-1">Completed Pipeline</span>
                    </a>
                </li>
            @elseif (auth()->user()->role == 'photo')
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'photo-dashboard' ? ' active bg-gradient-primary' : '' }} "
                        href="{{ route('photo-dashboard') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'complete-shoots' ? ' active bg-gradient-primary' : '' }} "
                        href="{{ route('complete-shoots') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Complete Shoots</span>
                    </a>
                </li>
            @elseif (auth()->user()->role == 'editor')
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'editor-dashboard' ? ' active bg-gradient-primary' : '' }} "
                        href="{{ route('editor-dashboard') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'complete-edits' ? ' active bg-gradient-primary' : '' }} "
                        href="{{ route('complete-edits') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Complete Edits</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
