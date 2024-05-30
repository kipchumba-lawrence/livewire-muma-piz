<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <h6 class="font-weight-bolder mb-0 text-capitalize">{{ str_replace('-', ' ', Route::currentRouteName()) }}
            </h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group input-group-outline">
                    {{-- <label class="form-label">Type here...</label>
                    <input type="text" class="form-control"> --}}
                </div>
            </div>
            <form method="POST" action="" class="d-none" id="logout-form">
                @csrf
            </form>
            <span class="text-sm mx-4">Welcome {{ auth()->user()->name }}</span>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item align-items-center">
                    {{-- Add anchor tag to logout --}}
                    <a href="{{ route('logout-platform') }}" class="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-user me-sm-2"></i>
                        {{-- <livewire:auth.logout /> --}}
                    </a>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
