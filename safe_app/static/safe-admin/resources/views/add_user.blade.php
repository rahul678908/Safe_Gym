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

        <style>
            .spinner-border {
                width: 3rem;
                height: 3rem;
            }
            .model_close_btn{
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
            
            .model_close_btn .close{
                border: none;
                background: none;
                font-size: 40px;
            }

        </style>


        <div id="loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.8); z-index: 9999; text-align: center;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Processing your request, please wait...</p>
            </div>
        </div>

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
                            <div class="col-md-12">
                                <div class="form_group mt-2">
                                    <label for="phone" class="form-label">Address <span style="color: red; font-size: 20px">*</span></label>
                                    <textarea type="text" id="address" name="address" class="form-control" placeholder="Enter your address" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="weight" class="form-label">Weight <span style="font-size: 20px"></span></label>
                                    <input type="text" id="weight" name="weight" class="form-control" placeholder="Enter your weight">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="height" class="form-label">Height <span style="font-size: 20px"></span></label>
                                    <input type="text" id="height" name="height" class="form-control" placeholder="Enter your height">
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="form_group mt-2">
                                    <label for="blood_group" class="form-label">Select Blood Group <span style="color: red; font-size: 20px">*</span></label>
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
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="package" class="form-label">Package <span style="color: red; font-size: 20px">*</span></label>
                                    <select id="package" name="package" class="form-select" required>
                                        <option value="" disabled selected>Select a package</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Cardio">Cardio</option>
                                        <option value="3 Month Normal">3 Month Normal</option>
                                        <option value="3 Month Cardio">3 Month Cardio</option>
                                        <option value="12 Month Normal">1 Year Normal</option>
                                        <option value="12 Month Cardio">1 Year Cardio</option>
                                        <option value="1 Month Trainer">Personal Trainer</option>
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
                                    <label for="photo" class="form-label">Upload Photo <span style="color: red; font-size: 20px">*</span></label>
                                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*" required>
                                </div>
                            </div>

                            <!-- Cropping Modal -->
                            <div id="cropModal" class="modal" tabindex="-1" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header model_close_btn">
                                            <h5 class="modal-title">Crop Image</h5>
                                            <button type="button" class="close" onclick="closeCropperModal()">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <img id="cropImage" style="width: 100%;" alt="Crop Image">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="cropSaveBtn">Crop & Save</button>
                                            <button type="button" class="btn btn-secondary" onclick="closeCropperModal()">Close</button>
                                        </div>
                                    </div>
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
                                        <th scope="col">Last Paid</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Break</th>
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
                                            $hasBreak = $customer->break_start && $customer->break_end;
                                        @endphp
                                            <tr class="{{ $isOverdue ? 'table-danger' : ($isWarning ? 'table-warning' : ($hasBreak ? 'table-secondary' : '')) }}"> <!-- Add class 'table-danger' for red background -->
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
                                                <td>{{  \Carbon\Carbon::parse($customer->date)->format('d-m-Y')  }}</td>
                                                <td>{{ $customer->phone }}</td>
                                                <td>{{ $customer->package }}</td>
                                                <td>{{ $customer->amount }}</td>
                                                <td>{{ $customer->last_paid ? \Carbon\Carbon::parse($customer->last_paid)->format('d-m-Y') : '' }}</td>
                                                <td>{{ $customer->due_date ? \Carbon\Carbon::parse($customer->due_date)->format('d-m-Y') : \Carbon\Carbon::parse($customer->date)->format('d-m-Y') }}</td>
                                                
                                                <td>
                                                    
                                                     @if($customer->break_start && $customer->break_end)

                                                        <button type="button" class="btn btn-secondary btn-sm">
                                                        Breaked
                                                        </button>
                                                     @else
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#exampleModal"
                                                        data-id="{{ $customer->id }}"
                                                        data-name="{{ $customer->name }}">
                                                        Break
                                                        </button>
                                                    @endif

                                                </td>
                                                
                                                <td>
                                                   <button 
                                                        class="btn btn-success {{ $isOverdue ? 'send-whatsapp-due' : 'send-whatsapp-warn' }}" 
                                                        data-id="{{ $customer->id }}" 
                                                        data-customerid="{{ $customer->customer_id }}" 
                                                        data-phone="{{ $customer->phone }}" 
                                                        data-name="{{ $customer->name }}"
                                                        data-amount="{{ $customer->amount }}"
                                                        data-package="{{ $customer->package }}"
                                                        data-date="{{ $customer->due_date ? $customer->due_date : $customer->date  }}">
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
    
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Customer Take Break</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form id="breakForm" action="{{ route('customer.setBreak') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" name="customer_id" id="modal_customer_id" required>
                    <div class="form-group mt-2">
                        <label for="customerid">Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" id="modal_customer_name" readonly required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="break_start">Break Start Date</label>
                        <input type="date" class="form-control" id="break_start" name="break_start" required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="break_end">Break End Date</label>
                        <input type="date" class="form-control" id="break_end" name="break_end" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Save Break</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>             
            </div>
            </div>
        </div>
    </div>
   <!-- Break Modal end-->

   <script>
        document.addEventListener("DOMContentLoaded", function () {
            var exampleModal = document.getElementById('exampleModal');

            exampleModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var customerId = button.getAttribute('data-id'); // Get customer ID from data attribute
                var customer_name = button.getAttribute('data-name'); // Get customer ID from data attribute

                console.log("Customer ID:", customerId); // Debugging: Check if the ID is retrieved

                // Set the customer ID in the hidden input field
                document.getElementById('modal_customer_id').value = customerId;
                document.getElementById('modal_customer_name').value = customer_name;
            });
        });
    </script>

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
                "Cardio": 1200,
                "3 Month Normal": 2000,
                "3 Month Cardio": 3000,
                "12 Month Normal": 6000,
                "12 Month Cardio": 10000,
                "1 Month Trainer": 6000,
            };

            const advanceAmounts = {
                "Normal": 300,
                "Cardio": 300,
                "3 Month Normal": 0,
                "3 Month Cardio": 0,
                "12 Month Normal": 0,
                "12 Month Cardio": 0,
                "1 Month Trainer": 0,
            };

            packageSelect.addEventListener('change', function () {
                const selectedPackage = packageSelect.value;
                amountInput.value = packageAmounts[selectedPackage] || '';
                advanceInput.value = advanceAmounts[selectedPackage] || '';
            });
        });
    </script>

    <!-- Cropping functions  start here -->
    <script>
        let cropper;
        let croppedCanvas;

        // When user selects an image
        document.getElementById('photo').addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('cropImage').src = e.target.result;
                    openCropperModal();
                };

                reader.readAsDataURL(file);
            }
        });

        function openCropperModal() {
            $('#cropModal').show(); // Show the modal
            const image = document.getElementById('cropImage');

            // Initialize Cropper.js
            cropper = new Cropper(image, {
                aspectRatio: 1, // Square crop
                viewMode: 2, // Restricts cropping to the image
                preview: '.photo_container img'
            });
        }

        function closeCropperModal() {
            $('#cropModal').hide(); // Hide the modal
            if (cropper) {
                cropper.destroy();
            }
        }

        document.getElementById('cropSaveBtn').addEventListener('click', cropImage);


        function cropImage() {
            if (cropper) {
                croppedCanvas = cropper.getCroppedCanvas({
                    width: 300, // Set the cropped image width
                    height: 300 // Set the cropped image height
                });

                // Show the cropped image preview
                document.getElementById('imagePreview').src = croppedCanvas.toDataURL();

                // Convert cropped image to Blob and append it to a hidden input field
                croppedCanvas.toBlob((blob) => {
                    const fileInput = document.getElementById('photo');
                    const file = new File([blob], "cropped-image.jpg", { type: "image/jpeg" });

                    // Create a new DataTransfer object and replace the file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                });

                closeCropperModal();
            }
        }
    </script>
    <!-- Cropping functions  end here -->

   <script>
        document.querySelector('form').addEventListener('submit', function () {
            // Show the loader
            document.getElementById('loader').style.display = 'block';

            // Disable the submit button to prevent multiple submissions
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
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

            
        // Due wahtsapp msg
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.send-whatsapp-due');
            
            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const customerid = this.getAttribute('data-customerid');
                    const phoneNumber = this.getAttribute('data-phone');
                    const name = this.getAttribute('data-name');
                    const amount = this.getAttribute('data-amount');
                    const package = this.getAttribute('data-package'); // New attribute
                    const due_Date = this.getAttribute('data-date');
                    
                    const dueDate = due_Date; // Example date in YYYY-MM-DD format

                    // Convert dueDate to a JavaScript Date object
                    const formattedDueDate = new Date(dueDate).toLocaleDateString("en-GB", {
                        day: "2-digit",
                        month: "2-digit",
                        year: "numeric"
                    });

                    // Updated WhatsApp message
                    let message = `Hello ${name},\n\n`;
                    message += `Customer ID : ${customerid}\n`;  
                    message += `Your fee due date of ${package} plan.\n`;  
                    message += `Date : ${formattedDueDate}.\n`;
                    message += `kindly settle it at the earliest.\n`;
                    message += `Paid Amount: ₹${amount}\n\n`;
                    message += `Gpay Number: +91 99476 45909\n\n`;
                    message += `Download your invoice from this link :\n https://safegym.in/safe-admin/customer/invoice/${customerid} \n\n\n`;
                    message += `Best Regards,\nSafe Gym,\nKollengode,\n Mobile: +91 99476 45909\n`;
                    message += `Have a nice day..!`;


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

        //  Warning whatsapp Msg
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.send-whatsapp-warn');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const customerid = this.getAttribute('data-customerid');
                    const phoneNumber = this.getAttribute('data-phone');
                    const name = this.getAttribute('data-name');
                    const amount = this.getAttribute('data-amount');
                    const package = this.getAttribute('data-package'); // New attribute
                    const due_Date = this.getAttribute('data-date');
                    
                    const dueDate = due_Date; // Example date in YYYY-MM-DD format

                    // Convert dueDate to a JavaScript Date object
                    const formattedDueDate = new Date(dueDate).toLocaleDateString("en-GB", {
                        day: "2-digit",
                        month: "2-digit",
                        year: "numeric"
                    });

                    // Updated WhatsApp message
                    let message = `Hello ${name},\n\n`;
                    message += `Customer ID : ${customerid}\n`;  
                    message += `Your fee is due on ${package} plan.\n\n`;  
                    message += `Date : ${formattedDueDate}\n\n`;
                    message += `kindly ensure payment before due date.\n\n`;
                    message += `Fee Amount: ₹${amount}\n\n`;
                    message += `Gpay Number: +91 99476 45909\n\n`;
                    message += `Download your invoice from this link :\n https://safegym.in/safe-admin/customer/invoice/${customerid} \n\n`;
                    message += `Best Regards,\nSafe Gym,\nKollengode,\n\n Mobile: +91 99476 45909\n`;
                    message += `Have a nice day..!`;

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