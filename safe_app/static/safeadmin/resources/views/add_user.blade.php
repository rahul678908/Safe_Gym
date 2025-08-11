<x-layout>
   
    <div class="col-md-12">
       <h2 class="mt-3 mb-3">Add User Details</h2>
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
            <form action="{{route('customers.store')}}" method="post" enctype="multipart/form-data">
               @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="photo_container">
                           <img id="imagePreview" src="{{ asset('images/user.png') }}" class="img-fluid" alt="USer Image">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Name <span style="color: red; font-size: 20px">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="gender" class="form-label">Select Gender <span style="color: red; font-size: 20px">*</span></label>
                                    <select id="gender" name="gender" class="form-select" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Special">Special</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="age" class="form-label">Age <span style="color: red; font-size: 20px">*</span></label>
                                    <input type="text" id="age" name="age" class="form-control" placeholder="Enter your age" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="text" id="weight" name="weight" class="form-control" placeholder="Enter your weight">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="height" class="form-label">Height</label>
                                    <input type="text" id="height" name="height" class="form-control" placeholder="Enter your height">
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="form_group mt-2">
                                    <label for="blood_group" class="form-label">Select Blood Group</label>
                                    <select id="blood_group" name="blood_group" class="form-select">
                                        <option value="" disabled selected>Select Blood Group</option>
                                        <option value="A+ve">A+ve</option>
                                        <option value="A-ve">A-ve</option>
                                        <option value="B+ve">B+ve</option>
                                        <option value="B-ve">B-ve</option>
                                        <option value="O+ve">O+ve</option>
                                        <option value="O-ve">O-ve</option>
                                        <option value="AB+ve">AB+ve</option>
                                        <option value="AB-ve">AB-ve</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="form_group mt-2">
                                    <label for="bp_sugar" class="form-label">BP</label>
                                    <select id="bp_sugar" name="bp_sugar" class="form-select">
                                        <option value="" disabled selected>Select Yes Or No</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="form_group mt-2">
                                    <label for="sugar" class="form-label">Sugar</label>
                                    <select id="sugar" name="sugar" class="form-select">
                                        <option value="" disabled selected>Select Yes Or No</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="problems" class="form-label">Other Problems</label>
                                    <input type="text" id="other_problems" name="other_problems" class="form-control" placeholder="Enter your other problems">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="join_date" class="form-label">Join Date <span style="color: red; font-size: 20px">*</span></label>
                                    <input type="date" id="date" name="date" class="form-control" placeholder="Enter Join Date" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="email" class="form-label">Email <span style="color: red; font-size: 20px">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="phone" class="form-label">Phone <span style="color: red; font-size: 20px">*</span></label>
                                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" maxlength="10" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form_group mt-2">
                                    <label for="phone" class="form-label">Address <span style="color: red; font-size: 20px">*</span></label>
                                    <textarea type="text" id="address" name="address" class="form-control" placeholder="Enter your address" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="package" class="form-label">Package <span style="color: red; font-size: 20px">*</span></label>
                                    <select id="package" name="package" class="form-select" required>
                                        <option value="" disabled selected>Select a package</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Cardio">Cardio</option>
                                        <option value="3 Month Normal">3 Month Normal</option>
                                        <option value="3 Month Cardio">3 Month Cardio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="amount" class="form-label">Amount <span style="font-size: 20px"></span></label>
                                    <input type="number" id="amount" name="amount" class="form-control" placeholder="Enter amount" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="advance" class="form-label">Advance <span style="font-size: 20px"></span></label>
                                    <input type="number" id="advance" name="advance" class="form-control" placeholder="Enter advance" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="photo" class="form-label">Upload Photo</label>
                                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-left">
                                    <button  type="submit" class="btn btn-success mt-3">Add User</button>
                               </div>
                            </div>
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
                        <h2>User Information Table</h2>
                        <div class="table_data table-responsive mt-2">
                            <table id="customersTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Customer ID</th>
                                        <th scope="col">Blood Group</th>
                                        <th scope="col">Join Date</th>
                                        <!-- <th scope="col">Email</th> -->
                                        <th scope="col">Phone</th>
                                        <th scope="col">Package</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Send Message</th>
                                        <th scope="col">Msg Count</th>
                                        <th scope="col">Edit</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($customers->isEmpty())
                                        <tr>
                                            <td colspan="12" class="text-center">No customers found.</td>
                                        </tr>
                                    @else
                                        @foreach ($customers as $key => $customer)
                                        @php
                                            $dueDate = \Carbon\Carbon::parse($customer->due_date);
                                            $today = \Carbon\Carbon::now();
                                            $isOverdue = $dueDate->lt($today); // Check if the due_date is overdue
                                            $isWarning = $dueDate->subDays(5)->lte($today) && !$isOverdue; // Check if today is within 5 days before due_date
                                        @endphp
                                            <tr class="{{ $isOverdue ? 'table-danger' : ($isWarning ? 'table-warning' : '') }}"> <!-- Add class 'table-danger' for red background -->
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>
                                                    @if ($customer->photo)
                                                        <img src="{{ asset('photos/' . $customer->photo) }}" class="img-fluid" alt="Customer Image" width="100">
                                                    @else
                                                        <img src="{{ asset('images/user.png') }}" class="img-fluid" alt="Default Image" width="65">
                                                    @endif
                                                </td>
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->customer_id }}</td>
                                                <td>{{ $customer->blood_group ?? '' }}</td>
                                                <td>{{ $customer->date }}</td>
                                                <td>{{ $customer->phone }}</td>
                                                <td>{{ $customer->package }}</td>
                                                <td>{{ $customer->amount }}</td>
                                                <td>{{ $customer->due_date ?  $customer->due_date  : ""}}</td>
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
                                                <td><a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a></td>
                                                <td>
                                                    <form action="{{ route('customer.delete', $customer->id) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('PATCH') <!-- PATCH is used for updates -->
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
        document.addEventListener('DOMContentLoaded', function () {
            const packageSelect = document.getElementById('package');
            const amountInput = document.getElementById('amount');
            const advanceInput = document.getElementById('advance');

            const packageAmounts = {
                "Normal": 700,
                "Cardio": 1000,
                "3 Month Normal": 2000,
                "3 Month Cardio": 3000
            };

            const advanceAmounts = {
                "Normal": 500,
                "Cardio": 500,
                "3 Month Normal": 0,
                "3 Month Cardio": 0
            };

            packageSelect.addEventListener('change', function () {
                const selectedPackage = packageSelect.value;
                amountInput.value = packageAmounts[selectedPackage] || '';
                advanceInput.value = advanceAmounts[selectedPackage] || '';
            });
        });
    </script>



    <script>

        $(document).ready(function() {
            $('#customersTable').DataTable({
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
                    const package = this.getAttribute('data-package'); // New attribute
                    const dueDate = this.getAttribute('data-date');

                    // Updated WhatsApp message
                    let message = `Hello ${name},\n\n`;
                    message += `Your subscription to the ${package} plan was successfully added and will expire on ${dueDate}.\n`;
                    message += `Paid Amount: ₹${amount}\n\n`;
                    message += `Thank you,\nSafe Gym\n918921296011`;

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
</x-layout>