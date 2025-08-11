<x-layout>

    <div class="col-md-12">
        <div class="add_user_container mt-5 mb-5">
            <h2 class="mb-4">Edit Expense Details</h2>
            <form action="{{route('expense.update', $expense->id)}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="date">Select a Date:</label>
                                    <input type="date" class="form-control" value="{{ $expense->date }}" id="date" name="date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="date">Purchase</label>
                                    <input type="text" class="form-control" value="{{ $expense->purchase }}"  id="purchase" name="purchase" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <label for="date">Description</label>
                                    <textarea rows="3" class="form-control" value="" cols="12" name="description" id="description">
                                        {{$expense->description}}
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="date">Amount</label>
                                    <input type="text" class="form-control" value="{{ $expense->amount }}"  id="amount" name="amount" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success">Update Expence</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-layout>