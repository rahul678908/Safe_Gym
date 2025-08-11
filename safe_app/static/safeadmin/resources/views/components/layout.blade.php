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


    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>Gym Admin Panel</title>

</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="{{route('dashboard')}}">Home</a>
        <a href="{{route('add_user')}}">Add user</a>
        <a href="{{route('fees_due_details')}}">Fee Details</a>
       
        <!-- <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                Report
            </a>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="background-color: #000000; color: #ffffff; z-index: 999;">
                <li><a class="dropdown-item" href="/expense_report">Expense Report</a></li>
                <li><a class="dropdown-item" href="/income_report">Income Report</a></li>
            </ul>
        </div> -->
        
        <a class="dropdown-item" href="{{route('expense_report')}}">Expense Report</a>
        <a class="dropdown-item" href="{{route('income_report')}}">Income Report</a>

        <a href="{{route('expense')}}">Expense</a>
        <a href="{{route('addfees')}}">Fee Adding</a>
        <a id="l2" href="/">Logout</a>
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
                                        <i id="icon" class="fa-solid fa-circle-user fa-lg"></i>
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

    <script src="{{ asset('javascript/chart.js') }}"></script>
    
    <!-- Include jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</body>
</html>