<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/multiselect.css">

    <title>WH Returns</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/tabulator-tables@5.1.7/dist/css/tabulator.min.css" rel="stylesheet">
    @yield('styles')
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.1.7/dist/js/tabulator.min.js"></script>
</head>

<body>

    <main>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" @auth href="/returns" @endauth>
                    <x-application-logo width="110" height="35" class="d-inline-block align-text-top" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @auth
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            @can('show-menu')
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/returns">Home</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Dashboards
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="/elp-dashboard">El Paso Warehouse</a></li>
                                        <li><a class="dropdown-item" href="/jrz-dashboard">Juarez Call Center</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Reports
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="/returns/reports/general">Tracking Number</a></li>
                                        <li><a class="dropdown-item" href="/returns/reports/condition">Returns Condition</a>
                                        </li>
                                        <li><a class="dropdown-item" href="/report">Calipers General</a></li>
                                        <li><a class="dropdown-item" href="/report/detallado">Calipers Detallado</a></li>
                                    </ul>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a class="nav-link" href="/labels/print">Print Labels</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/part">Print Part</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/part/add">Print CV AXLE</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/checked">Receive Part</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/caliper/add">Add Caliper</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/target">Targets</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/part">Caliper New</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/checked">Caliper Receive</a>
                            </li>
                        </ul>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" style="color: white;"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            @yield('content')
        </div>

    </main>
    <script src="/js/app.js"></script>
    <script src="/js/fngeneral.js"></script>


    @yield('scripts')
</body>

</html>
