<x-layout>

   <!-- table contents -->
   <div class="col-md-12 mt-5">
        <div class="table_content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                               <div class="form_group mt-2">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" placeholder="start date" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form_group mt-2">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" placeholder="end date" required>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex" style="gap: 10px">
                                <div class="form_group mt-2">
                                    <button class="btn btn-success" style="margin-top: 32px;" onclick="monthly_income_report()">Search</button>
                                </div>
                                <div class="form_group mt-2">
                                    <a href="{{route('income_report')}}">
                                      <button class="btn btn-primary" style="margin-top: 32px;">Refresh</button>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form_group mt-2">
                                    <button class="btn btn-success" style="margin-top: 32px;" onclick="income_report_to_pdf()">Save As PDF</button>
                                </div>
                            </div>
                        </div>
            
                        <div class="table_data table-responsive mt-2" id="income_report">
                          <h2 class="pt-2 pb-2">Income Report Table</h2>
                           <table id="feesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                       <th scope="col">No</th>
                                       <th scope="col">Name</th>
                                       <th scope="col">Customer ID</th>
                                       <th scope="col">Month</th>
                                       <th scope="col">Package</th>
                                       <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                 <tbody>
                                    @if ($fees->isEmpty())
                                       <tr>
                                             <td colspan="6" class="text-center">No Income Report found.</td>
                                       </tr>
                                    @else
                                       @php
                                             $totalIncome = 0; // Initialize the total income variable
                                       @endphp
                                       @foreach ($fees as $key => $fee)
                                             @php
                                                $totalIncome += $fee->amount; // Add each fee amount to the total
                                             @endphp
                                             <tr> 
                                                <td scope="row">{{ $key + 1 }}</td>
                                                <td>{{ $fee->name }}</td>
                                                <td>{{ $fee->customerid }}</td>
                                                <td>{{ $fee->month }}</td>
                                                <td>{{ $fee->package }}</td>
                                                <td>{{ $fee->amount }}</td>
                                             </tr>
                                       @endforeach
                                    @endif
                                 </tbody>
                                 <tfoot id="table_footer">
                                    <tr>
                                          <td colspan="5" class="text-end"><strong>Total Income:</strong></td>
                                          <td><strong>{{ $totalIncome ?? 0 }}.00</strong></td>
                                    </tr>
                                 </tfoot>
                           </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>


<script>
    function monthly_income_report() {
        //alert("data");
        const start_date = $('#start_date').val();
        const end_date = $('#end_date').val();

        if (!start_date || !end_date) {
            alert("Please provide both start and end dates.");
            return;
        }

        $.ajax({
            url: "{{ route('income_report_monthly') }}",
            method: 'POST',
            data: { 
                start_date: start_date, 
                end_date: end_date, 
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            dataType: 'json',
            success: function (response) {
                console.log("Response:", response);

                if (response.success) {
                    const table = $('#feesTable').DataTable();
                    table.clear();
                    $('#table_footer').empty();

                    let totalAmt = 0;

                    response.data.forEach((row, index) => {
                        const fees = parseFloat(row.amount || 0);
                        const totalPrice = fees;

                        table.row.add([
                            index + 1,
                            row.name || 'N/A',
                            row.customerid || 'N/A',
                            row.month || 'N/A',
                            row.package || 'N/A',
                            `Rs/- ${totalPrice || 'N/A'}`
                        ]);

                        totalAmt += totalPrice;
                    });

                    table.draw();

                    $('#table_footer').html(`<tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Total Income:</td>
                        <td style="font-weight: bold;">Rs/- ${totalAmt}</td>
                    </tr>`);
                } else {
                    alert(response.message || "No data found.");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.log("Response Text:", xhr.responseText);
                alert("An error occurred while fetching the data. Please try again.");
            }
        });
    }



function income_report_to_pdf() {
    const element = document.getElementById('income_report');
    if (!element || element.innerHTML.trim() === "") {
        console.error("Error: Element 'income_report' is empty or does not exist.");
        alert("Invoice content is missing.");
        return;
    }

    setTimeout(() => {
        const pageWidth = 210; // A4 width in mm
        const marginX = 10; // Horizontal margin

        // Ensure jsPDF is loaded properly
        if (!window.jspdf) {
            console.error("Error: jsPDF library is not loaded.");
            alert("Failed to load jsPDF library.");
            return;
        }

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4'); // Create a new PDF document

        html2canvas(element, { scale: 2, useCORS: true }) // Capture the HTML content as a canvas
            .then(canvas => {
                const imgData = canvas.toDataURL('image/jpeg', 1.0);

                // Calculate image dimensions to fit in A4
                const imgWidth = pageWidth - marginX * 2;
                const imgHeight = (canvas.height / canvas.width) * imgWidth;

                pdf.addImage(imgData, 'JPEG', marginX, marginX, imgWidth, imgHeight);

                const pdfBlob = pdf.output('blob');

                // Save Invoice
                pdf.save('income_report.pdf');
            })
            .catch(error => {
                console.error('Error generating the PDF:', error);
                alert('Failed to generate the invoice PDF.');
            });
    }, 500); // Slight delay to ensure rendering
}


</script>


</x-layout>