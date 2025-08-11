<x-layout>
<div class="col-md-12">
       <h2 class="mt-3 mb-3">Edit Fees Details</h2>
       <div class="add_user_container mb-5">
            <form action="{{route('fees.update', $fees->id)}}" method="post">
               @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" name="name" value="{{ $fees->name }}" class="form-control" placeholder="Enter customer name" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Customer ID</label>
                                    <input type="text" id="customerid" name="customerid" value="{{ $fees->customerid }}" class="form-control" placeholder="Enter customer ID" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Join Date</label>
                                    <input type="date" id="date" name="date" class="form-control" value="{{ $fees->join_date ?? '' }}" placeholder="Enter Date" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Fees Pay Date</label>
                                    <input type="date" id="date" name="date" class="form-control" value="{{ $fees->date ?? '' }}" placeholder="Enter Date" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="package" class="form-label">Package</label>
                                    <input type="text" id="package" name="package" class="form-control" value="{{ $fees->package }}" placeholder="Enter Package" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" id="amount" name="amount" class="form-control" value="{{ $fees->amount }}" placeholder="Enter amount" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="join_date" class="form-label">Due Date</label>
                                    <input type="text" id="due_date" name="due_date" class="form-control" value="{{ $fees->due_date }}" placeholder="Due Date" readonly required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="month" class="form-label">Select Month</label>
                                    <select id="month" name="month" class="form-select" required>
                                        <option value="" disabled {{ !$fees->month ? 'selected' : '' }}>Select Month</option>
                                        <option value="January" {{ $fees->month == 'January' ? 'selected' : '' }}>January</option>
                                        <option value="February" {{ $fees->month == 'February' ? 'selected' : '' }}>February</option>
                                        <option value="March" {{ $fees->month == 'March' ? 'selected' : '' }}>March</option>
                                        <option value="April" {{ $fees->month == 'April' ? 'selected' : '' }}>April</option>
                                        <option value="May" {{ $fees->month == 'May' ? 'selected' : '' }}>May</option>
                                        <option value="June" {{ $fees->month == 'June' ? 'selected' : '' }}>June</option>
                                        <option value="July" {{ $fees->month == 'July' ? 'selected' : '' }}>July</option>
                                        <option value="August" {{ $fees->month == 'August' ? 'selected' : '' }}>August</option>
                                        <option value="September" {{ $fees->month == 'September' ? 'selected' : '' }}>September</option>
                                        <option value="October" {{ $fees->month == 'October' ? 'selected' : '' }}>October</option>
                                        <option value="November" {{ $fees->month == 'November' ? 'selected' : '' }}>November</option>
                                        <option value="December" {{ $fees->month == 'December' ? 'selected' : '' }}>December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-left">
                                    <button  type="submit" class="btn btn-success mt-3">Update Fees</button>
                                    <a href="{{ route('addfees') }}" class="btn btn-secondary mt-3">Cancel</a>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>