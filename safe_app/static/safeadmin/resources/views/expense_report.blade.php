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
                                    <button class="btn btn-success" style="margin-top: 32px;" onclick="monthly_expense_report()">Search</button>
                                </div>
                                <div class="form_group mt-2">
                                    <a href="{{route('income_report')}}">
                                        <button class="btn btn-primary" style="margin-top: 32px;">Refresh</button>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="form_group mt-2">
                                    <button class="btn btn-success" style="margin-top: 32px;" onclick="expense_report_to_pdf()">Save As PDF</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table_data table-responsive mt-2" id="expense_report">
                            <h2 class="mt-3 mb-3">Expense Report Table</h2>
                            <table class="table table-bordered table-striped" id="ExpenseTable">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Purchase</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($expenses->isEmpty())
                                        <tr>
                                            <td colspan="12" class="text-center">No Expense Report found.</td>
                                        </tr>
                                    @else
                                        @php
                                            $totalExpense = 0; // Initialize the total income variable
                                        @endphp
                                        @foreach ($expenses as $key => $expense)
                                            @php
                                                $totalExpense += $expense->amount; // Add each fee amount to the total
                                            @endphp
                                            <tr> 
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>{{ $expense->date }}</td>
                                                <td>{{ $expense->purchase }}</td>
                                                <td>{{ $expense->description }}</td>
                                                <td>{{ $expense->amount }}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    <tfoot id="table_footer">
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Total Expense:</strong></td>
                                            <td><strong>{{ $totalExpense ?? 0 }}.00</strong></td>
                                        </tr>
                                    </tfoot>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>
   function monthly_expense_report() {
    //alert("data")
    // Collect start and end dates from the input fields
    const start_date = $('#start_date').val();
    const end_date = $('#end_date').val();

    if (!start_date || !end_date) {
        alert("Please provide both start and end dates.");
        return;
    }

    $.ajax({
        url: "{{ route('expense_report_monthly') }}",
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
                const table = $('#ExpenseTable').DataTable();
                table.clear();
                $('#table_footer').empty();

                let totalAmt = 0;

                response.data.forEach((row, index) => {
                    const expense = parseFloat(row.amount || 0);
                    const totalexpense = expense;

                    table.row.add([
                        index + 1,
                        row.date || 'N/A',
                        row.purchase || 'N/A',
                        row.description || 'N/A',
                        row.amount || 'N/A',
                        `Rs/- ${totalexpense || 'N/A'}`
                    ]);

                    totalAmt += totalexpense;
                });

                table.draw();

                $('#table_footer').html(`<tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Total Income:</td>
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


function expense_report_to_pdf() {
    const element = document.getElementById('expense_report');
    if (!element || element.innerHTML.trim() === "") {
        console.error("Error: Element 'expense_report' is empty or does not exist.");
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
                pdf.save('expense_report.pdf');
            })
            .catch(error => {
                console.error('Error generating the PDF:', error);
                alert('Failed to generate the invoice PDF.');
            });
    }, 500); // Slight delay to ensure rendering
}

</script>

</x-layout>