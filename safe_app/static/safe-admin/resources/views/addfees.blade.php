<x-layout>
   
    <div class="col-md-12">
       <h2 class="mt-3 mb-3">Add User Fees Details</h2>
       @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
       @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
       <div class="add_user_container mb-5">
       <form action="{{ route('add_customer_fees.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_group mt-2">
                            <label for="searchdata" class="form-label">Search Customer</label>
                            <input type="text" id="searchdata" name="searchdata" class="form-control" placeholder="Enter Name or Customer ID">
                            <div id="search-results" class="mt-2"></div> <!-- For displaying search results -->
                        </div>
                    </div>
                    <!-- image addition new update start 10/02/2025 -->
                     <div class="col-md-3">
                        <img src="" alt="customer image" id="imageShow" width="100px" height="120px" class="img-fluid mt-3">
                     </div>
                     <div class="col-md-9"></div>
                    <!-- image addition new update end 10/02/2025 -->
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Customer Name" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="customerid" class="form-label">Customer ID</label>
                            <input type="text" id="customerid" name="customerid" class="form-control" placeholder="Customer ID" readonly required>
                        </div>
                    </div>
                    <!--new update 13-02-2025-->
                    
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Customer ID" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" readonly></textarea>
                        </div>
                    </div>
                    
                    <!--new update 13-02-2025-->
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="join_date" class="form-label">Join Date</label>
                            <input type="date" id="join_date" name="join_date" class="form-control" placeholder="Join Date" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="join_date" class="form-label">Due Date</label>
                            <input type="date" id="due_date" name="due_date" class="form-control" placeholder="Due Date" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="last_paid" class="form-label">Last Paid Date</label>
                            <input type="date" id="last_paid" name="last_paid" class="form-control" placeholder="Last paid Date" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="last_month" class="form-label">Last Paid Month</label>
                            <input type="text" id="last_month" name="last_month" class="form-control" placeholder="Last Month" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" id="amount" name="amount" class="form-control" placeholder="Amount" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="package" class="form-label">Package</label>
                            <input type="text" id="cus_package" name="package" class="form-control" placeholder="Package" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group mt-2">
                            <label for="package" class="form-label">Select Current Month</label>
                            <select id="month" name="month" class="form-select" required>
                                <option value="" disabled selected>Select Month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                    </div>
                    <!--new update month number 13/12/2025 -->
                    <div class="col-md-4">
                       <div id="month_number"></div>
                    </div>
                    <!--new update month number  13/12/2025 -->
                    <div class="col-md-12">
                        <div class="text-left">
                            <button type="submit" class="btn btn-success mt-3">Add Fees</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- table contents -->
    <div class="col-md-12">
        <div class="table_content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="pt-2 pb-2">User Fees Information Table</h2>
                        <div class="table_data table-responsive mt-2">
                            <table id="feesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                       <th scope="col">No</th>
                                       <th scope="col">Name</th>
                                       <th scope="col">Customer ID</th>
                                       <th scope="col">Join Date</th>
                                       <th scope="col">Paid Month</th>
                                       <th scope="col">last Paid</th>
                                       <th scope="col">Package</th>
                                       <th scope="col">Amount</th>
                                       <th scope="col">Due Date</th>
                                       <th scope="col">Edit</th>
                                       <th scope="col">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($fees->isEmpty())
                                        <tr>
                                            <td colspan="12" class="text-center">No Fees Report found.</td>
                                        </tr>
                                    @else
                                        @foreach ($fees as $key => $fee)
                                            <tr> 
                                                <td scope="row">{{ $key + 1 }}</td>
                                                <td>{{ $fee->name }}</td>
                                                <td>{{ $fee->customerid }}</td>
                                                <td>{{ date('d-m-Y', strtotime($fee->join_date)) }}</td>
                                                <td>{{ $fee->month }}</td>
                                                <td>{{ date('d-m-Y', strtotime($fee->date)) }}</td>
                                                <td>{{ $fee->package }}</td>
                                                <td>{{ $fee->amount }}</td>
                                                <td>{{ date('d-m-Y', strtotime($fee->due_date)) }}</td>
                                                <td><a href="{{ route('fees.edit', $fee->id) }}" class="btn btn-warning btn-sm">Edit</a></td>

                                                <td>
                                                    <form action="{{ route('fees.delete', $fee->id) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('PATCH') 
                                                        <button type="button" class="btn btn-danger btn-sm delete-button">Delete</button>
                                                    </form>
                                                </td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    if (confirm('Are you sure you want to delete this customer?')) {
                        this.closest('form').submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    </script>


    <script>

        $(document).ready(function() {
            $('#feesTable').DataTable({
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


        document.getElementById('photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

            
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.send-whatsapp');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const phoneNumber = this.getAttribute('data-phone');
                    const name = this.getAttribute('data-name');
                    const amount = this.getAttribute('data-amount');
                    let message = `Hello ${name}, this message from our Gym Admin`;
                        message += ` `;
                        message+= `Please pay your this month Gym Fee Amount ${amount}.`;
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
                            window.location.href = "{{route('add_user')}}";
                        } else {
                            console.error('Failed to update clicks.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });


    </script>

    <script>
        document.getElementById('searchdata').addEventListener('input', function () {
            const query = this.value;

            if (query.length >= 2) { // Trigger search after 2 characters
                fetch(`{{ route('search.customers') }}?query=${query}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const resultsContainer = document.getElementById('search-results');
                        resultsContainer.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(customer => {
                                const button = document.createElement('button');
                                button.type = 'button';
                                button.classList.add('btn', 'btn-link', 'd-block', 'text-left');
                                button.innerText = `${customer.name} (ID: ${customer.customer_id})`;
                                button.onclick = () => {
                                    
                                    // Define package durations
                                    const packageDurations = {
                                        "Normal": 1,
                                        "Cardio": 1,
                                        "3 Month Normal": 3,
                                        "3 Month Cardio": 3,
                                        "12 Month Normal": 12,
                                        "12 Month Cardio": 12
                                    };

                                    // Function to calculate due date
                                    function calculateDueDate(startDate, selectedPackage) {
                                        let packageDuration = packageDurations[selectedPackage] || 0;
                                        
                                        if (packageDuration > 0 && startDate) {
                                            let dueDate = new Date(startDate);
                                            dueDate.setMonth(dueDate.getMonth() + packageDuration);
                                            return dueDate.toISOString().split('T')[0]; // Format as YYYY-MM-DD
                                        }

                                        return null;
                                    }

                                    // Example usage:
                                    let customerPackage = customer.package; // Replace with dynamic customer package
                                    let customerDate = customer.date; // Replace with dynamic customer date

                                    let dummyDueDate = calculateDueDate(customerDate, customerPackage);
                                    console.log(dummyDueDate); // Expected output: "2024-05-01"
                                    
                                    // Populate form fields
                                    document.getElementById('name').value = customer.name;
                                    document.getElementById('customerid').value = customer.customer_id;
                                    document.getElementById('amount').value = customer.due_date==null ? customer.total_amount : customer.amount;
                                    document.getElementById('cus_package').value = customer.package;
                                    document.getElementById('join_date').value = customer.date;
                                    document.getElementById('last_paid').value = customer.last_paid;
                                    document.getElementById('due_date').value = customer.due_date ? customer.due_date : customer.date;
                                    document.getElementById('last_month').value = customer.month;
                                    
                                    // new update start 10/02/2025
                                    var baseUrl = "{{ asset('photos/') }}/";
                                    var baseUrl2 = "{{ asset('images/') }}/";
                                    document.getElementById('imageShow').src = customer.photo ? baseUrl + customer.photo : baseUrl2 + 'user.png';
                                    // new update end 10/02/2025
                                    
                                    // new update start 13/02/2025
                                    document.getElementById('phone').value = customer.phone;
                                    document.getElementById('address').value = customer.address;
                                    // new update start 13/02/2025

                                    // Clear search results
                                    resultsContainer.innerHTML = '';
                                };
                                resultsContainer.appendChild(button);
                            });
                        } else {
                            resultsContainer.innerHTML = '<p>No customers found.</p>';
                        }
                    })
                    .catch(error => console.error('Error fetching customer data:', error));
            }
        });
    </script>

</x-layout>