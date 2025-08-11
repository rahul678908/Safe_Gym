<x-layout>
<div class="col-md-12">
       <h2 class="mt-3 mb-3">Customer Fees Add</h2>
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
            <form action="{{route('fees.newfees')}}" method="post" enctype="multipart/form-data">
               @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" name="name" value="{{ $customers->name ?? '' }}" class="form-control" placeholder="Enter customer name" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Customer ID</label>
                                    <input type="text" id="customerid" name="customerid" value="{{ $customers->customer_id ?? '' }}" class="form-control" placeholder="Enter customer ID" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Join Date</label>
                                    <input type="date" id="join_date" name="join_date" class="form-control" value="{{ $customers->date ?? '' }}" placeholder="Enter Date" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="join_date" class="form-label">Due Date</label>
                                    <input type="date" id="due_date" name="due_date" class="form-control" value="{{ $customers->due_date ? $customers->due_date : $customers->date }}" placeholder="Due Date" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Last Paid</label>
                                    <input type="date" id="last_paid" name="last_paid" class="form-control" value="{{ $customers->last_paid ?? '' }}" placeholder="Enter Date" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="last_month" class="form-label">Last Month</label>
                                    <input type="text" id="last_month" name="last_month" class="form-control" value="{{ $customers->month ?? '' }}" placeholder="Last Month" readonly required>
                                </div>
                            </div>
                            <!--<div class="col-md-4">-->
                            <!--    <div class="form_group mt-2">-->
                            <!--        <label for="package" class="form-label">Package</label>-->
                            <!--        <select id="package" name="package" class="form-select" required>-->
                            <!--            <option value="" disabled {{ empty($fees->package) ? 'selected' : '' }} selected>Select a package</option>-->
                            <!--            <option value="Normal" {{ $customers->package == 'Normal' ? 'selected' : '' }}>Normal</option>-->
                            <!--            <option value="Cardio" {{ $customers->package == 'Cardio' ? 'selected' : '' }}>Cardio</option>-->
                            <!--            <option value="3 Month Normal" {{ $customers->package == '3 Month Normal' ? 'selected' : '' }}>3 Month Normal</option>-->
                            <!--            <option value="3 Month Cardio" {{ $customers->package == '3 Month Cardio' ? 'selected' : '' }}>3 Month Cardio</option>-->
                            <!--        </select>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="package" class="form-label">Package</label>
                                    <input type="text" id="package" name="package" class="form-control" value="{{$customers->package}}" placeholder="Package" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input 
                                        type="number" 
                                        id="amount" 
                                        name="amount" 
                                        class="form-control" 
                                        value="{{ $customers->due_date == null ? $customers->total_amount : $customers->amount }}"
                                        placeholder="Enter amount"
                                        readonly
                                    >
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="month" class="form-label">Select Month</label>
                                    <select id="month" name="month" class="form-select" required>
                                        <option value="null">Select Month</option>
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
                            <div class="col-md-12">
                                <div class="text-left">
                                    <button  type="submit" class="btn btn-success mt-3">Update Fees</button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Cancel</a>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>