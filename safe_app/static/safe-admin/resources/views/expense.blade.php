<x-layout>

<div class="col-md-12">

       @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
        <div class="expence_report_btn">
            <a href="{{route('expense_report')}}" class="btn mt-3">Expense Report</a>
        </div>
       <div class="add_user_container mb-5">
            <h2 class="mb-4">Expense Details</h2>
            <form action="{{route('add_expense.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="date">Select a Date:</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="date">Purchase</label>
                                    <input type="text" class="form-control" placeholder="Purchase" id="purchase" name="purchase" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <label for="date">Description</label>
                                    <textarea rows="3" class="form-control" cols="12" placeholder="Description" name="description" id="description"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="date">Amount</label>
                                    <input type="text" class="form-control" placeholder="Amount" id="amount" name="amount" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success">Save Expence</button>
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
                        <h2>Expense Information Table</h2>
                        <div class="table_data table-responsive mt-2">
                            <table class="table table-bordered table-striped" id="ExpenseTable">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Purchase</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Edit</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($expenses->isEmpty())
                                        <tr>
                                            <td colspan="12" class="text-center">No Expense data found.</td>
                                        </tr>
                                    @else
                                        @foreach ($expenses as $key => $expense)
                                           
                                            <tr> 
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>{{ $expense->date }}</td>
                                                <td>{{ $expense->purchase }}</td>
                                                <td>{{ $expense->description }}</td>
                                                <td>{{ $expense->amount }}</td>
                                                <td><a href="{{ route('expense.edit', $expense->id) }}" class="btn btn-warning btn-sm">Edit</a></td>
                                                <td>
                                                    <form action="{{ route('expense.delete', $expense->id) }}" method="POST" class="delete-form">
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
        $(document).ready(function() {
            $('#ExpenseTable').DataTable({
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
    </script>

</x-layout>