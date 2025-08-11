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

     <!-- PDF js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Safe Gym Invoice</title>

    <style>
        .customer-container {
           width: 100%;
           height: 100dvh;
           display: flex;
           flex-direction: column;
        }

        .customer-data{
            box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;
            padding: 20px;
            border-radius: 14px;
            background-color: white;
        }

        .logo-section{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: start;
        }

        .customer_details_invoice{
            display: flex;
            flex-direction: column;
            align-items: start;
            justify-content: start;
        }
    </style>

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

        .red-color{
            color: red;
        }

        .logo_section_content{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 25px;
        }

        .gym_message{
            display: flex;
            flex-direction: column;
            align-items: start;
            justify-content: end;
            margin-top: 200px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="offset-md-2 col-md-8 col-sm-12">
                <div class="customer-container mt-2 mb-2">
                    <div class="logo-section">
                        <img src="{{asset('images/safegym.png')}}"  alt="safegym logo" style="width: 140px; height: 140px;">
                       <h1 class="red-color">Safe Gym </h1>
                    </div>
                    <div class="customer-data">
                        <div class="customer-header">
                            <h4>Customer Invoice</h4>
                        </div>
                        <hr>
                        <div class="customer-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="customer_details_invoice">
                                        <h5 class="mb-4">Customer Details</h5>
                                        <p>Customer Name : {{ $customer->name }}</p>
                                        <p>Customer ID : {{ $customer->customer_id }}</p>
                                        <p>Join Date : {{ $customer->date }}</p>
                                        <p>Due Date : {{ $customer->due_date }}</p>
                                        <p>Last Paid : {{ $customer->last_paid }}</p>
                                        <p>Last Month : {{ $customer->month }}</p>
                                        <p>Package : {{ $customer->package }}</p>
                                        <p>Amount : {{ $customer->amount }}</p>
                                        <p>Last Paid Month : {{ $customer->month }}</p>
                                        @php
                                            $currentMonth = date('F'); // Get the current month name (e.g., January, February)
                                        @endphp

                                        <p>Fee status: 
                                            @if($customer->month === $currentMonth)
                                                Paid
                                            @else
                                                Not Paid
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 text-center">
                                    <h5 class="mt-5">Download Your Invoice</h5>
                                    <button class="btn btn-primary mt-3" id="download-invoice"
                                        data-id="{{ $customer->customer_id }}"
                                        data-phone="{{ $customer->phone }}" 
                                        data-name="{{ $customer->name }}"
                                        data-amount="{{ $customer->amount }}"
                                        data-package="{{ $customer->package }}"
                                        data-date="{{ $customer->due_date }}" 
                                        data-month="{{ $customer->month }}">
                                        <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                         Download
                                    </button>
                                </div>
                            </div>
                        </div>
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
                                    <!-- <img src="../assets/img/wonder.jpg" alt="Wonder Tykes Logo" style="width: 80px; height: 80px;"> -->
                                    <h4><strong>Safe Gym, Kollenkode</strong></h4>
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

                            <div class="gym_message" id="gym_message">
                                <h4>Gpay Number: 8921296011</h4>
                                <h5>Best Regards...</h5>
                            </div>
                            <div class="gym_message" id="gym_message">
                                <h4>Contact Us</h4>
                                <p>Safe Gym, Kollenkode</p>
                                <p>Phone : +91 8921296011 </p>
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


    <script>
         // Invoice generation
        document.addEventListener('click', function (event) {
            alert('clicked');
            if (event.target.closest('#download-invoice1')) {
                const button = event.target.closest('#download-invoice1');

                // Extract data from button
                const customerId = button.getAttribute('data-id');
                const customerPhone = button.getAttribute('data-phone');
                const customerName = button.getAttribute('data-name');
                const amount = button.getAttribute('data-amount');
                const packageName = button.getAttribute('data-package');
                const dueDate = button.getAttribute('data-date');
                const dueMonth = button.getAttribute('data-month');

                // Define company details
                const companyName = "Safe Gym";
                const companyAddress = "Kollenkode";
                const companyPhone = "8921296011";

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

            // Get the current month name (e.g., "January", "February")
           const currentMonth = new Date().toLocaleString('default', { month: 'long' });
           // Determine the fee status
           const feeStatus = dueMonth === currentMonth ? 'Paid' : 'Not Paid';


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
                    <p><strong>Fee Status</strong> : ${feeStatus || 'N/A'}</p>
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
                                pdf.save(`Invoice_customer_Id_${customerId}.pdf`);
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
    </script>
    
    <script>
        document.addEventListener('click', function (event) {
            if (event.target.closest('#download-invoice')) {
                const button = event.target.closest('#download-invoice');

                // Extract data from button
                const customerId = button.getAttribute('data-id');
                const customerPhone = button.getAttribute('data-phone');
                const customerName = button.getAttribute('data-name');
                const amount = button.getAttribute('data-amount');
                const packageName = button.getAttribute('data-package');
                const dueDate = button.getAttribute('data-date');
                const dueMonth = button.getAttribute('data-month');

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

                // Get the current month name (e.g., "January", "February")
                const currentMonth = new Date().toLocaleString('default', { month: 'long' });
                // Determine the fee status
                const feeStatus = dueMonth === currentMonth ? 'Paid' : 'Not Paid';


                let companyDetails = `
                    Company: ${companyName}
                    Invoice No: ${customerId}/${formattedDate}
                    Invoice Date: ${formattedDate}
                    Address: ${companyAddress}
                    Phone: +91 ${companyPhone}
                `;

                let customerDetails = `
                    Customer Name: ${customerName}
                    Payment Status: ${feeStatus}
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
                        pdf.text(`Payment Status : ${feeStatus}`, 14, 70);

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
                        pdf.save(`Invoice_Customer_ID_${customerId}.pdf`);
                        $('#customer_invoice').modal('hide');
                    }, 500);
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <!-- invoice pdf Generation with out image pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</body>
</html>