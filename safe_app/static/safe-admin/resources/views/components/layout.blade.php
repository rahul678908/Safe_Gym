<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"
        integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Chart js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>Gym Admin Panel</title>

</head>
<body>

    <style>
    .sidebar a.active {
        background-color: black !important;
        color: white !important;
        padding: 15px;
    }
   </style>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <img src="{{ asset('images/safegym.png') }}" alt="safegym Logo" class="gym-logo" width="100px" height="100px">
        
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Home</a>
        <a href="{{ route('add_user') }}" class="{{ request()->routeIs('add_user') ? 'active' : '' }}">Add user</a>
        <a href="{{ route('income_report') }}" class="{{ request()->routeIs('income_report') ? 'active' : '' }}">Income Report</a>
        <a href="{{ route('expense') }}" class="{{ request()->routeIs('expense') ? 'active' : '' }}">Expense</a>
        <a href="{{ route('addfees') }}" class="{{ request()->routeIs('addfees') ? 'active' : '' }}">Fee Adding</a>
        <a href="{{ route('logout') }}" class="{{ request()->routeIs('logout') ? 'active' : '' }}">Logout</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header">
                                @if(session('user'))
                                    <div class="user-info">
                                        <i id="icon" class="fa-solid fa-circle-user fa-lg" style="font-size: 30px;"></i>
                                        <span id="s1">{{ session('user') ? session('user')->name : "Admin" }}</span>   
                                    </div>
                                @endif
                                

                                <div id="lo1" class="logout-container">
                                    <a class="logout-button" href="{{ route('logout') }}">
                                       Logout
                                    </a>
                                </div>

                                <span class="menu-icon" onclick="toggleSidebar()">&#9776;</span>
                            </div>
                        </div>

                        {{$slot}}

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Include jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</body>
</html>