<?php

namespace App\Http\Controllers;

use App\Models\Fees;
use App\Models\Customers;

use Illuminate\Http\Request;

class FeesController extends Controller
{
    //Create a constructer for Fees model
    protected $fees;
    protected $customers;

    public function __construct(){
        $this->fees = new Fees(); 
        $this->customers = new Customers(); 
    } 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fees = $this->fees->where('status', 1)->get();   
        $customers = $this->customers->where('status', 1)->get();   
        return view('addfees', compact('fees' ? 'fees' : ''), compact('customers' ? 'customers' : ''));
    }

    public function index2()
    {
        $fees = $this->fees->where('status', 1)->get();      
        return view('income_report', compact('fees' ? 'fees' : ''));
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
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required',
            'customerid' => 'required', // Matches the customer_id column
            'amount' => 'required|numeric',
            'join_date' => 'required|date',
            'package' => 'required',
            'month' => 'required'
        ]);

        // Set the current date
        $validatedData['date'] = \Carbon\Carbon::now()->toDateString();

        // Calculate due_date based on package
        $packageDurations = [
            "Normal" => 1,
            "Cardio" => 1,
            "3 Month Normal" => 3,
            "3 Month Cardio" => 3
        ];

        $selectedPackage = $request->package;
        $packageDuration = $packageDurations[$selectedPackage] ?? 0;

        if ($packageDuration > 0) {
            // Use join_date as fallback for due_date if due_date is missing
            $validatedData['due_date'] = \Carbon\Carbon::parse($request->due_date ?? $request->join_date)
                ->addMonths($packageDuration)
                ->toDateString();
        } else {
            $validatedData['due_date'] = null; // No due date for unrecognized packages
        }

        // Update the customers table first
        $customer = Customers::where('customer_id', $validatedData['customerid'])->first();

        if ($customer) {
            $customer->update([
                'due_date' => $validatedData['due_date'],
                'month' => $validatedData['month']
            ]);

            // Only after customer update, add a new record to the fees table
            fees::create($validatedData);

            return redirect()->route('addfees')->with('success', 'Customer Fees Added and Due Date Updated successfully.');
        } else {
            // If no matching customer exists, handle it
            return redirect()->back()->with('error', 'Customer not found in the database.');
        }
    }




    public function income_report(Request $request)
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
            $fees = Fees::whereBetween('created_at', [$startDate, $endDate])->where('status', 1)->get();

            if ($fees->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found for the given date range.',
                ]);
            }

            $data = $fees->map(function ($fee) {
                return [
                    'id' => $fee->id ?? 'N/A',
                    'name' => $fee->name ?? 'N/A',
                    'customerid' => $fee->customerid ?? 'N/A', // Corrected to match assumed naming convention
                    'month' => $fee->month ?? 'N/A',
                    'package' => $fee->package ?? 'N/A',
                    'amount' => $fee->amount ?? 0, // Default to 0 if amount is null
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

    



    public function searchCustomers(Request $request)
    {
        $query = $request->input('query');

        // Search customers by name or customer_id
        $customers = Customers::where('name', 'LIKE', "%{$query}%")
            ->orWhere('customer_id', 'LIKE', "%{$query}%")
            ->get(); 

        return response()->json($customers);
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
        $fees = fees::findOrFail($id);
    
        // Return the edit view with the customer data
        return view('edit_fees', compact('fees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'customerid' => 'required',
            'date' => 'required|date',
            'package' => 'required',
            'amount' => 'required|numeric'
        ]);

        // Fetch the customer record
        $fees = fees::findOrFail($id);

        // Update the customer record
        $fees->update($validatedData);

        // Redirect back with a success message
        return redirect()->route('addfees')->with('success', 'Fees updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        // Find the customer by ID
        $fees = fees::findOrFail($id);

        // Update the status to 0 (deleted)
        $fees->status = 0;
        $fees->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Fees Deleted Successfully.');
    }
}
