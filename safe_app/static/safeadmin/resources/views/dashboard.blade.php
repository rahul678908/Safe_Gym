<x-layout>

    <!-- Profile Info -->
    <div class="col-md-12">
        <div class="profile-info">
            <img src="{{ asset('images/user.png') }}" alt="">
            <div>
                <h1>Welcome to the Dashboard</h1>
                <p>Hello, {{ session('user')->name }}!</p>
                <p>Email: {{ session('user')->email }}</p>
            </div>
        </div>
    </div> 

     <!-- table contents -->
     <div class="col-md-12 mt-3">
        <div class="table_content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>User Information Table</h2>
                        <div class="table_data table-responsive mt-2">
                            <table id="customersTable_2" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Join Date</th>
                                        <!-- <th scope="col">Email</th> -->
                                        <th scope="col">Phone</th>
                                        <th scope="col">Package</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Send Message</th>
                                        <th scope="col">Msg Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Sort and filter customers: Overdue first, Warning next
                                        $filteredCustomers = $customers->map(function ($customer) {
                                            $dueDate = \Carbon\Carbon::parse($customer->due_date);
                                            $today = \Carbon\Carbon::now();

                                            // Calculate overdue and warning flags
                                            $customer->isOverdue = $dueDate->lt($today); // Overdue if due_date is past
                                            $customer->isWarning = !$customer->isOverdue && $dueDate->subDays(5)->lte($today); // Warning if within 5 days of due_date

                                            return $customer;
                                        })
                                        ->filter(function ($customer) {
                                            // Only include customers who are overdue or in the warning period
                                            return $customer->isOverdue || $customer->isWarning;
                                        })
                                        ->sortByDesc(function ($customer) {
                                            // Sort logic: Overdue (2), Warning (1)
                                            return $customer->isOverdue ? 2 : ($customer->isWarning ? 1 : 0);
                                        });
                                    @endphp

                                    @if ($filteredCustomers->isEmpty())
                                        <tr>
                                            <td colspan="12" class="text-center">No overdue or warning customers found.</td>
                                        </tr>
                                    @else
                                        @foreach ($filteredCustomers as $customer)
                                            <tr class="{{ $customer->isOverdue ? 'table-danger' : ($customer->isWarning ? 'table-warning' : '') }}">
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>
                                                    @if ($customer->photo)
                                                        <img src="{{ asset('photos/' . $customer->photo) }}" class="img-fluid" alt="Customer Image" width="100">
                                                    @else
                                                        <img src="{{ asset('images/user.png') }}" class="img-fluid" alt="Default Image" width="100">
                                                    @endif
                                                </td>
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->date }}</td>
                                                <td>{{ $customer->phone }}</td>
                                                <td>{{ $customer->package }}</td>
                                                <td>{{ $customer->amount }}</td>
                                                <td>{{ $customer->due_date }}</td>
                                                <td>
                                                    <button 
                                                        class="btn btn-success send-whatsapp" 
                                                        data-id="{{ $customer->id }}" 
                                                        data-phone="{{ $customer->phone }}" 
                                                        data-name="{{ $customer->name }}"
                                                        data-amount="{{ $customer->amount }}"
                                                        data-package="{{ $customer->package }}"
                                                        data-date="{{ $customer->due_date }}">
                                                        <i class="fa fa-comment" aria-hidden="true"></i>
                                                        Message
                                                    </button>
                                                </td>
                                                <td>{{ $customer->message_clicks }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- chart js -->
    <div class="col-md-12 mt-5 mb-5">
        <div class="row">

            <div class="col-md-4">
                <div class="card ">
                    <h3 class="card-header p-3">Customers Chart</h3>
                    <div class="card-body">
                        <canvas id="usersChart" height="120px"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <h3 class="card-header p-3">Income Chart</h3>
                    <div class="card-body">
                        <canvas id="incomeChart" height="120px"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card ">
                    <h3 class="card-header p-3">Expense Chart</h3>
                    <div class="card-body">
                        <canvas id="expenseChart" height="120px"></canvas>
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-6">
               <canvas id="incomeChart" width="400" height="200"></canvas>
            </div> -->

        </div>
    </div>


    <script>

       $(document).ready(function() {
            $('#customersTable_2').DataTable({
                "paging": true,           // Enable pagination
                "searching": true,        // Enable search functionality
                "ordering": true,         // Enable column sorting
                "info": true,             // Show table information
                "lengthChange": true,     // Enable changing the number of rows per page
                "language": {
                    "emptyTable": "No data available in table"
                }
            });
        });
  
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.send-whatsapp');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const phoneNumber = this.getAttribute('data-phone');
                    const name = this.getAttribute('data-name');
                    const amount = this.getAttribute('data-amount');
                    const packages = this.getAttribute('data-package'); // New attribute
                    const dueDate = this.getAttribute('data-date');

                    // Updated WhatsApp message
                    let message = `Hello ${name},\n\n`;
                    message += `Your subscription to the ${packages} plan was successfully added and will expire on ${dueDate}.\n`;
                    message += `Paid Amount: ₹${amount}\n\n`;
                    message += `Thank you,\nSafe Gym\n919947645909`;

                    const encodedMessage = encodeURIComponent(message);

                    // WhatsApp URL
                    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
                    window.open(whatsappUrl, '_blank'); // Redirect to WhatsApp

                    // Send AJAX request to update click count
                    fetch("{{ route('update.clicks') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}" // Include CSRF token
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(`Message clicks updated. Total clicks: ${data.clicks}`);
                            window.location.href = "{{route('dashboard')}}";
                        } else {
                            console.error('Failed to update clicks.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });

    </script>

    <script type="text/javascript">

        // Get data from backend
        var usersLabels = {{ Js::from($customerLabels) }};
        var usersData = {{ Js::from($customerData) }};

        // Prepare the chart data
        const chartData = {
            labels: usersLabels, // X-axis labels
            datasets: [{
                label: 'Customers Data', // Dataset label
                backgroundColor: 'rgba(255, 99, 132, 0.2)', // Background fill
                borderColor: 'rgb(255, 99, 132)', // Line color
                borderWidth: 1, // Border width
                data: usersData, // Data points
            }]
        };

        // Chart configuration
        const config = {
            type: 'line', // Chart type
            data: chartData, // Data object
            options: {
                responsive: true, // Make the chart responsive
                plugins: {
                    legend: {
                        position: 'top', // Legend position
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true // Start y-axis at zero
                    }
                }
            }
        };

        // Initialize Chart.js
        const usersChart = new Chart(
            document.getElementById('usersChart'), // Target canvas element
            config // Configuration object
        );

   </script>

   <script>
        const income = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(income, {
            type: 'bar', // Use 'line' for line chart
            data: {
                labels: @json($incomeLabels), // Month names
                datasets: [{
                    label: 'Total Income',
                    data: @json($incomeData), // Income values
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

   <script>
        const expense = document.getElementById('expenseChart').getContext('2d');
        const expenseData = new Chart(expense, {
            type: 'bar', // Use 'line' for line chart
            data: {
                labels: @json($expenseLabels), // Month names
                datasets: [{
                    label: 'Total Income',
                    data: @json($expenseData), // Income values
                    backgroundColor: 'rgba(48, 252, 3, 0.2)',
                    borderColor: 'rgba(48, 252, 3)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</x-layout>
       

