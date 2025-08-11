<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Expenses;


class ExpensesController extends Controller
{
    //Create a constructer for customer model
    protected $expenses;

    public function __construct(){
        $this->expenses = new Expenses();
    }  
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = $this->expenses->where('status', 1)->get();      
        return view('expense', compact('expenses' ? 'expenses' : ''));
    }

    public function index2()
    {
        $expenses = $this->expenses->where('status', 1)->get();      
        return view('expense_report', compact('expenses' ? 'expenses' : ''));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required',
            'purchase' => 'required',
            'description' => 'required',
            'amount' => 'numeric', 
        ]);

        expenses::create($validatedData);
        return redirect()->route('expense')->with('success', 'Purchase Added successfully.');
    }

    public function expense_report(Request $request)
    {
        // Validate the input dates
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Retrieve data from the Fees model based on the date range
            $expenses = Expenses::whereBetween('created_at', [$startDate, $endDate])->where('status', 1)->get();

            if ($expenses->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found for the given date range.',
                ]);
            }

            $data = $expenses->map(function ($expense) {
                return [
                    'id' => $expense->id ?? 'N/A',
                    'date' => $expense->date ?? 'N/A',
                    'purchase' => $expense->purchase ?? 'N/A', 
                    'description' => $expense->description ?? 'N/A',
                    'amount' => $expense->amount ?? 0, 
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            // Log the exception for debugging
            //\Log::error('Income Report Error: ' . $e->getMessage(), ['trace' => $e->getTrace()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the data. Please try again.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch the customer record by ID
        $expense = expenses::findOrFail($id);
    
        // Return the edit view with the customer data
        return view('edit_expense', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'purchase' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric'
        ]);

        // Fetch the customer record
        $expense = expenses::findOrFail($id);

        // Update the customer record
        $expense->update($validatedData);

        // Redirect back with a success message
        return redirect()->route('expense')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        // Find the customer by ID
        $expense = expenses::findOrFail($id);

        // Update the status to 0 (deleted)
        $expense->status = 0;
        $expense->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Expense Deleted Successfully.');
    }
}
