<x-layout>
    
    <!--<style>-->
    <!--     .customer_image img{-->
    <!--        width: 100px !important;-->
    <!--        height: 110px !important;-->
    <!--        object-fit: cover;-->
    <!--    } -->
    <!--</style>-->

    <!-- Profile Info -->
    <div class="col-md-12">
        <div class="profile-info">
            <img src="{{ asset('images/safegym.png') }}" alt="">
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
                                        <th scope="col">Customer ID</th>
                                        <th scope="col">Join Date</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Package</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Last Paid</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Add Fees</th>
                                        <th scope="col">Send Message</th>
                                        <th scope="col">Send Invoice</th>
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
                                            $customer->isWarning = !$customer->isOverdue && $dueDate->copy()->subDays(5)->lte($today); // Warning if within 5 days of due_date

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
                                                <td class="customer_image">
                                                    @if ($customer->photo)
                                                        <img src="{{ asset('photos/' . $customer->photo) }}" class="img-fluid" alt="Customer Image" width="100px" height="100px">
                                                    @else
                                                        <img src="{{ asset('images/user.png') }}" class="img-fluid" alt="Default Image" width="100px" height="100px">
                                                    @endif
                                                </td>
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->customer_id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($customer->date)->format('d-m-Y') }}</td>
                                                <td>{{ $customer->phone }}</td>
                                                <td>{{ $customer->package }}</td>
                                                <td>{{ $customer->amount }}</td>
                                                <td>{{ $customer->last_paid ? \Carbon\Carbon::parse($customer->last_paid)->format('d-m-Y') : '' }}</td>
                                                <td>{{ $customer->due_date ? \Carbon\Carbon::parse($customer->due_date)->format('d-m-Y') : \Carbon\Carbon::parse($customer->date)->format('d-m-Y') }}</td>
                                                <!--<td>{{ $customer->due_date }}</td>-->
                                                <td>
                                                    <button class="btn btn-primary"
                                                        onclick="window.location.href='{{ route('customer.fees', ['id' => $customer->id]) }}'">
                                                        Fees Add
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-success {{ $customer->isOverdue ? 'send-whatsapp' : 'send-whatsapp-warn' }}"
                                                        data-id="{{ $customer->id }}"
                                                        data-customerid="{{ $customer->customer_id }}"
                                                        data-phone="{{ $customer->phone }}"
                                                        data-name="{{ $customer->name }}"
                                                        data-amount="{{ $customer->amount }}"
                                                        data-package="{{ $customer->package }}"
                                                        data-date="{{ $customer->due_date ? $customer->due_date : $customer->date  }}">
                                                        <i class="fa fa-comment" aria-hidden="true"></i> Message
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-warning send-invoice-pdfa {{ $customer->isOverdue ? 'send-invoice-due' : 'send-invoice-warn' }}"
                                                        data-id="{{ $customer->id }}"
                                                        data-customerid="{{ $customer->customer_id }}"
                                                        data-phone="{{ $customer->phone }}"
                                                        data-name="{{ $customer->name }}"
                                                        data-amount="{{ $customer->amount }}"
                                                        data-package="{{ $customer->package }}"
                                                        data-date="{{ $customer->due_date }}">
                                                        <i class="fa fa-cloud-download" aria-hidden="true"></i> Invoice
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

        </div>
    </div>


    <div class="modal fade" id="customer_invoice" tabindex="-1" aria-labelledby="customerInvoiceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerInvoiceLabel">Customer Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="customer_invoice_content table-responsive" id="invoice_table_contents-data">
                        <div class="customer_invoice_content_row table-responsive" id="invoice_table_contents">
                            <div class="invoice_header">
                                <div class="logo_section_content">
                                    <img src="{{asset('images/safegym.png')}}" alt="Safe Gym Logo" style="width: 100px; height: 100px;">
                                    <h4><strong>Safe Gym, Kollengode</strong></h4>
                                </div>
                                <div class="invoice_text_content">
                                    <h2>INVOICE</h2>
                                </div>
                            </div>
                            <hr>
                            <div class="invoice_contents">
                                <div class="company_details" id="company_details"></div>
                                <div class="customer_details" id="customer_details"></div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer ID</th>
                                        <th>Customer</th>
                                        <th>Package</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="invoice_data">
                                </tbody>
                                <tfoot id="footer_data">
                                </tfoot>
                            </table>

                            <div class="purchase_message" id="purchase_message">
                                <h5>Gpay Number: +91 99476 45909</h5>
                                <h4>Best Regards.</h4>
                            </div>
                            <div class="purchase_message" id="purchase_message">
                                <h4>Contact Us</h4>
                                <p>Safe Gym, Kollengode</p>
                                <p>Phone : +91 99476 45909 </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



<style>

hr{
    border: solid 2px #000;
}

.customer_invoice_content_row{
    width: 210mm;
    height: 297mm;
    padding: 20px;
}  

#invoice_table_contents {
    width: 100%; /* Set appropriate width */
    display: block; /* Ensure it's visible */
    overflow: visible; /* Prevent clipping */
}

.invoice_contents{
    display: flex;
    flex-direction: row;
    align-items: start;
    justify-content: space-between;
    margin-bottom: 20px;
}


.invoice_header {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.logo_section_content{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 25px;
}

.purchase_message{
    display: flex;
    flex-direction: column;
    align-items: start;
    justify-content: end;
    margin-top: 200px;
}



</style>

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
  

        // Due whatsapp message
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.send-whatsapp');
            

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
                    const countryCode = "91"; // India Country Code (No `+`)
                    const whatsappUrl = `https://wa.me/${countryCode}${phoneNumber}?text=${encodedMessage}`;

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

        // Warning whatsapp message
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
                            window.location.href = "{{route('dashboard')}}";
                        } else {
                            console.error('Failed to update clicks.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });

        // Customer Invoice generation
        document.addEventListener('click', function (event) {
            if (event.target.closest('.send-invoice-due1')) {
                const button = event.target.closest('.send-invoice-due1');

                // Extract data from button
                const customerId = button.getAttribute('data-id');
                const customerPhone = button.getAttribute('data-phone');
                const customerName = button.getAttribute('data-name');
                const amount = button.getAttribute('data-amount');
                const packageName = button.getAttribute('data-package');
                const dueDate = button.getAttribute('data-date');

                // Define company details
                const companyName = "Safe Gym";
                const companyAddress = "Kollengode";
                const companyPhone = "99476 45909";

                let num = 0; // Counter for rows
                $('#invoice_data').empty(); // Clear existing data
                $('#company_details').empty(); // Clear existing data
                $('#customer_details').empty(); // Clear existing data
                $('#footer_data').empty(); // Clear existing data


            // Format date and time
            const now = new Date(); // Create a new Date object

            // Getting individual components
            const year = now.getFullYear();
            const month = now.getMonth() + 1; // Months are zero-based, so add 1
            const day = now.getDate();

            let hours = now.getHours(); // Use `let` here for reassignment
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();

            // Format the date and time
            const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const ampm = hours >= 12 ? 'PM' : 'AM';

            // Convert hours to 12-hour format
            hours = hours % 12; // Convert 13-23 to 1-11
            hours = hours ? hours : 12; // If hour is 0, set it to 12

            // Format time with leading zeros for minutes and seconds
            const formattedTime12Hour = `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} ${ampm}`;

                let companyDetails = `
                    <h5>Company Details :</h5>
                    <p><strong>Invoice NO</strong> : ${customerId || 'N/A'}/${formattedDate}</p>
                    <p><strong>Invoice Date</strong> : ${formattedDate} ${formattedTime12Hour}</p>
                    <p><strong>Company Email</strong> : safegym@gmail.com</p>
                    <p><strong>Phone</strong> : +91 ${companyPhone}</p>
                `;

                let customerDetails = `
                    <h5>Customer Details :</h5>
                    <h4><strong>Customer Name</strong> : ${customerName || 'N/A'}</h4>
                `;

                let invoiceRow = `
                    <tr>
                        <td>1</td>
                        <td>${customerId || 'N/A'}</td>
                        <td>${customerName || 'N/A'}</td>
                        <td>${packageName || 'N/A'}</td>
                        <td>${dueDate || 'N/A'}</td>
                        <td>₹${amount || 'N/A'}</td>
                    </tr>
                `;

                let footerRow = `
                    <tr>
                        <td colspan="5" style="text-align: right;"><strong>Total Amount</strong></td>
                        <td><strong>₹${amount || '0'}</strong></td>
                    </tr>
                `;

                // Append details to the modal
                $('#company_details').append(companyDetails);
                $('#customer_details').append(customerDetails);
                $('#invoice_data').append(invoiceRow);
                $('#footer_data').append(footerRow);

                // Show the modal
                $('#customer_invoice').modal('show'); // Display the modal

                // Generate PDF after modal is shown
                $('#customer_invoice').on('shown.bs.modal', function () {
                    setTimeout(() => {
                        const { jsPDF } = window.jspdf;
                        const pdf = new jsPDF('p', 'mm', 'a4'); // Create a new PDF document
                        const invoiceTable = document.getElementById('invoice_table_contents');

                        // Ensure the element is visible and has content
                        if (!invoiceTable || invoiceTable.offsetWidth === 0 || invoiceTable.offsetHeight === 0) {
                            console.error('Invoice content is not properly rendered.');
                            alert('Error: Invoice content is missing or not visible.');
                            return;
                        }

                        html2canvas(invoiceTable, { scale: 2, useCORS: true })
                            .then(canvas => {
                                const imgData = canvas.toDataURL('image/jpeg', 1.0);

                                const pageWidth = 210; // A4 width in mm
                                const marginX = 10; // Horizontal margin
                                const imgWidth = pageWidth - marginX * 2;
                                const imgHeight = (canvas.height / canvas.width) * imgWidth;

                                pdf.addImage(imgData, 'JPEG', marginX, marginX, imgWidth, imgHeight);

                                // Save the PDF
                                pdf.save(`Invoice_${customerId}.pdf`);
                                $('#customer_invoice').modal('hide');
                            })
                            .catch(error => {
                                console.error('Error generating the PDF:', error);
                                alert('Failed to generate the invoice PDF.');
                            });
                    }, 500); // Slight delay to ensure rendering
                });
            }
        });
        
        
        // Invoice generation without image pdf start send-invoice-due and send-invoice-warn
        
        // Customer Invoice generation due invoice start
        
        document.addEventListener('click', function (event) {
            if (event.target.closest('.send-invoice-due')) {
                const button = event.target.closest('.send-invoice-due');

                // Extract data from button
                const customerId = button.getAttribute('data-id');
                const customerPhone = button.getAttribute('data-phone');
                const customerName = button.getAttribute('data-name');
                const amount = button.getAttribute('data-amount');
                const packageName = button.getAttribute('data-package');
                const dueDate = button.getAttribute('data-date');

                // Define company details
                const companyName = "Safe Gym Invoice";
                const companyAddress = "Kollengode";
                const companyPhone = "99476 45909";
                //const logoUrl = "puplic/images/safegym.png";

                // Format Date & Time
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0'); 
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                const year = now.getFullYear();

                const formattedDate = `${day}-${month}-${year}`; // DD-MM-YYYY format


                let companyDetails = `
                    Company: ${companyName}
                    Invoice No: ${customerId}/${formattedDate}
                    Invoice Date: ${formattedDate}
                    Address: ${companyAddress}
                    Phone: +91 ${companyPhone}
                `;

                let customerDetails = `
                    Customer Name: ${customerName}
                    Payment Status: Due
                `;

                let invoiceData = [
                    ['#', 'Customer ID', 'Customer Name', 'Package', 'Due Date', 'Amount'],
                    ['1', customerId || 'N/A', customerName || 'N/A', packageName || 'N/A', dueDate || 'N/A', `${amount || 'N/A'}`]
                ];

                // Show modal
                $('#customer_invoice').modal('show');

                // Generate PDF after modal is shown
                $('#customer_invoice').on('shown.bs.modal', function () {
                    setTimeout(() => {
                        const { jsPDF } = window.jspdf;
                        const pdf = new jsPDF('p', 'mm', 'a4');

                        // Add company details
                        pdf.setFont('helvetica', 'bold');
                        pdf.setFontSize(14);
                        pdf.text(companyName, 14, 20);
                        // pdf.headers(companyDetails, 14, 25);
                        pdf.setFontSize(11);
                        pdf.setFont('helvetica', 'normal');
                        pdf.text(`Invoice No : ${customerId}/${formattedDate}`, 14, 30);
                        pdf.text(`Invoice Date : ${formattedDate}`, 14, 35);
                        pdf.text(`Address : ${companyAddress}`, 14, 40);
                        pdf.text(`Phone : +91 ${companyPhone}`, 14, 45);


                        // Add customer details
                        pdf.setFont('helvetica', 'bold');
                        pdf.setFontSize(14);
                        pdf.text(`Customer Name : ${customerName}`, 14, 60);
                        pdf.setFontSize(11);
                        pdf.text(`Payment Status : Due`, 14, 70);

                        // Generate Table
                        pdf.autoTable({
                            startY: 80,
                            head: [invoiceData[0]],
                            body: [invoiceData[1]],
                            theme: 'striped',
                            styles: { fontSize: 10 },
                            columnStyles: { 5: { halign: 'right' } } // Align amount to the right
                        });

                        // Add Total Amount
                        pdf.text(`Total Amount: ${amount || '0'}`, 14, pdf.lastAutoTable.finalY + 20);
                        pdf.text(`Google Pay Number: +91 99476 45909`, 14, pdf.lastAutoTable.finalY + 50);
                        pdf.setFontSize(16);
                        pdf.text(`Best Regards`, 14, pdf.lastAutoTable.finalY + 80);
                        pdf.setFontSize(14);
                        pdf.text(`Contact Us`, 14, pdf.lastAutoTable.finalY + 90);
                        pdf.setFontSize(12);
                        pdf.setFont('helvetica', 'normal');
                        pdf.text(`Safe Gym, Kollengode`, 14, pdf.lastAutoTable.finalY + 100);
                        pdf.text(`Phone : +91 99476 45909`, 14, pdf.lastAutoTable.finalY + 105);

                        // Save PDF
                        pdf.save(`Invoice_${customerId}.pdf`);
                        $('#customer_invoice').modal('hide');
                    }, 500);
                });
            }
        });
        
        // Customer Invoice generation due invoice end
        
        // Invoice Generation Warning invoice start
        
        document.addEventListener('click', function (event) {
            if (event.target.closest('.send-invoice-warn')) {
                const button = event.target.closest('.send-invoice-warn');

                // Extract data from button
                const customerId = button.getAttribute('data-id');
                const customerPhone = button.getAttribute('data-phone');
                const customerName = button.getAttribute('data-name');
                const amount = button.getAttribute('data-amount');
                const packageName = button.getAttribute('data-package');
                const dueDate = button.getAttribute('data-date');

                // Define company details
                const companyName = "Safe Gym Invoice";
                const companyAddress = "Kollengode";
                const companyPhone = "99476 45909";
                //const logoUrl = "puplic/images/safegym.png";

                // Format Date & Time
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0'); 
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                const year = now.getFullYear();

                const formattedDate = `${day}-${month}-${year}`; // DD-MM-YYYY format


                let companyDetails = `
                    Company: ${companyName}
                    Invoice No: ${customerId}/${formattedDate}
                    Invoice Date: ${formattedDate}
                    Address: ${companyAddress}
                    Phone: +91 ${companyPhone}
                `;

                let customerDetails = `
                    Customer Name: ${customerName}
                    Payment Status: Due
                `;

                let invoiceData = [
                    ['#', 'Customer ID', 'Customer Name', 'Package', 'Due Date', 'Amount'],
                    ['1', customerId || 'N/A', customerName || 'N/A', packageName || 'N/A', dueDate || 'N/A', `${amount || 'N/A'}`]
                ];

                // Show modal
                $('#customer_invoice').modal('show');

                // Generate PDF after modal is shown
                $('#customer_invoice').on('shown.bs.modal', function () {
                    setTimeout(() => {
                        const { jsPDF } = window.jspdf;
                        const pdf = new jsPDF('p', 'mm', 'a4');

                        // Add company details
                        pdf.setFont('helvetica', 'bold');
                        pdf.setFontSize(14);
                        pdf.text(companyName, 14, 20);
                        // pdf.headers(companyDetails, 14, 25);
                        pdf.setFontSize(11);
                        pdf.setFont('helvetica', 'normal');
                        pdf.text(`Invoice No : ${customerId}/${formattedDate}`, 14, 30);
                        pdf.text(`Invoice Date : ${formattedDate}`, 14, 35);
                        pdf.text(`Address : ${companyAddress}`, 14, 40);
                        pdf.text(`Phone : +91 ${companyPhone}`, 14, 45);


                        // Add customer details
                        pdf.setFont('helvetica', 'bold');
                        pdf.setFontSize(14);
                        pdf.text(`Customer Name : ${customerName}`, 14, 60);
                        pdf.setFontSize(11);
                        pdf.text(`Payment Status : Warning`, 14, 70);

                        // Generate Table
                        pdf.autoTable({
                            startY: 80,
                            head: [invoiceData[0]],
                            body: [invoiceData[1]],
                            theme: 'striped',
                            styles: { fontSize: 10 },
                            columnStyles: { 5: { halign: 'right' } } // Align amount to the right
                        });

                        // Add Total Amount
                        pdf.text(`Total Amount: ${amount || '0'}`, 14, pdf.lastAutoTable.finalY + 20);
                        pdf.text(`Google Pay Number: +91 99476 45909`, 14, pdf.lastAutoTable.finalY + 50);
                        pdf.setFontSize(16);
                        pdf.text(`Best Regards`, 14, pdf.lastAutoTable.finalY + 80);
                        pdf.setFontSize(14);
                        pdf.text(`Contact Us`, 14, pdf.lastAutoTable.finalY + 90);
                        pdf.setFontSize(12);
                        pdf.setFont('helvetica', 'normal');
                        pdf.text(`Safe Gym, Kollengode`, 14, pdf.lastAutoTable.finalY + 100);
                        pdf.text(`Phone : +91 99476 45909`, 14, pdf.lastAutoTable.finalY + 105);

                        // Save PDF
                        pdf.save(`Invoice_${customerId}.pdf`);
                        $('#customer_invoice').modal('hide');
                    }, 500);
                });
            }
        });
        
        // Invoice Generation Warning invoice start
        
        // Invoice generation without image pdf end send-invoice-due and send-invoice-warn

    </script>

    <!-- Chart js Scripts start here -->

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
                    label: 'Total Expense',
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

    <!-- Chart js Scripts end here -->

</x-layout>
       

