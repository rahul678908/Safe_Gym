<x-layout>

    <!-- table contents -->
    <div class="col-md-12 mt-5">
        <div class="table_content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>User Fees Details Table</h2>
                        <div class="table_data table-responsive mt-2">
                            <table id="customersTable_3" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Join Date</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Package</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Send Message</th>
                                        <th scope="col">Msg Count</th>
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
                                                $isOld = \Carbon\Carbon::parse($customer->date)->lt(\Carbon\Carbon::now()->subMonth()); // Check if the date is older than one month
                                            @endphp
                                            <tr class="{{ $isOld ? 'table-danger' : '' }}"> <!-- Add class 'table-danger' for red background -->
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>
                                                    @if ($customer->photo)
                                                        <img src="{{ asset('photos/' . $customer->photo) }}" class="img-fluid" alt="Customer Image" width="100">
                                                    @else
                                                        <img src="{{ asset('images/user.png') }}" class="img-fluid" alt="Default Image" width="100">
                                                    @endif
                                                </td>
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->date }}</td>
                                                <td>{{ $customer->email }}</td>
                                                <td>{{ $customer->phone }}</td>
                                                <td>{{ $customer->package }}</td>
                                                <td>{{ $customer->amount }}</td>
                                                <td>
                                                    <button 
                                                        class="btn btn-success send-whatsapp" 
                                                        data-id="{{ $customer->id }}" 
                                                        data-phone="{{ $customer->phone }}" 
                                                        data-name="{{ $customer->name }}"
                                                        data-amount="{{ $customer->amount }}">
                                                        <i class="fa fa-comment" aria-hidden="true"></i>
                                                        Message
                                                    </button>
                                                </td>
                                                <td>{{ $customer->message_clicks }}</td>
                                                <!-- <td><a href="#" class="btn btn-warning btn-sm">Edit</a></td>
                                                <td><a href="#" class="btn btn-danger btn-sm">Delete</a></td> -->
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

       $(document).ready(function() {
            $('#customersTable_3').DataTable({
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
                    let message = `Hello ${name}, this message from our Gym Admin`;
                        message += ` `;
                        message+= `Please pay your this month Gym Fee Amount Rs/- ${amount}.`;
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