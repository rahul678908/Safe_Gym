<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customers;
use App\Models\Expenses;
use App\Models\Fees;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Mail\CustomerRegistered;
use Illuminate\Support\Facades\Mail;


class CustomerController extends Controller
{   
    //Create a constructer for customer model
    protected $customers;
   

    public function __construct(){
    
        $this->customers = new Customers();
    }  


    public function index()
    {
        // Fetch customer IDs from tbl_addfees
        $excludedCustomerIds = Fees::pluck('customerid');

        // Fetch only customers with status = 1 and not in excluded IDs
        $customers = $this->customers
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('add_user', compact('customers'));
    }

    public function index2()
    {
        // Fetch customer IDs from tbl_addfees
        $excludedCustomerIds = Fees::pluck('customerid');

        // Fetch only customers with status = 1 and not in excluded IDs
        $customers = $this->customers
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc') // Replace 'created_at' with the column you want to sort by
                    ->get();

            
            
        // Users Chart
        $customersChart = Customers::select(
            FacadesDB::raw("COUNT(*) as count"),
            FacadesDB::raw("MONTHNAME(created_at) as month_name"),
            FacadesDB::raw("MONTH(created_at) as month_number")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month_number', 'month_name')
        ->orderBy('month_number')
        ->pluck('count', 'month_name');
    
        $customerLabels = $customersChart->keys();
        $customerData = $customersChart->values(); 

        // Income Chart
        $incomeChart = Fees::select(
            FacadesDB::raw("SUM(amount) as total_income"),
            FacadesDB::raw("MONTHNAME(created_at) as month_name"),
            FacadesDB::raw("MONTH(created_at) as month_number")
        )
        ->where('status', 1) // Filter only status = 1
        ->whereYear('created_at', date('Y')) // Filter by current year
        ->groupBy('month_number', 'month_name') // Group by month
        ->orderBy('month_number') // Order by month number
        ->pluck('total_income', 'month_name');

        // Prepare chart data
        $incomeLabels = $incomeChart->keys(); // Get month names
        $incomeData = $incomeChart->values(); // Get income values

        // Expense Chart
        $expenseChart = Expenses::select(
            FacadesDB::raw("SUM(amount) as total_expense"),
            FacadesDB::raw("MONTHNAME(created_at) as month_name"),
            FacadesDB::raw("MONTH(created_at) as month_number")
        )
        ->where('status', 1) // Filter only status = 1
        ->whereYear('created_at', date('Y')) // Filter by current year
        ->groupBy('month_number', 'month_name') // Group by month
        ->orderBy('month_number') // Order by month number
        ->pluck('total_expense', 'month_name');

        // Prepare chart data
        $expenseLabels = $expenseChart->keys(); // Get month names
        $expenseData = $expenseChart->values(); // Get income values

               
        return view('dashboard', compact('customers',  'customerLabels', 'customerData','incomeLabels','incomeData', 'expenseLabels', 'expenseData'));
    }

    public function index3()
    {
        // Fetch customer IDs from tbl_addfees
        $excludedCustomerIds = Fees::pluck('customerid');

        // Fetch only customers with status = 1 and not in excluded IDs
        $customers = $this->customers
            ->where('status', 1)
            ->whereNotIn('customer_id', $excludedCustomerIds)
            ->get();
                 
        return view('fees_due_details', compact('customers' ? 'customers' : ''));
    }


    public function chartData(){
        // Users Chart
        $customers = Customers::select(
            FacadesDB::classraw("COUNT(*) as count"),
            FacadesDB::raw("MONTHNAME(created_at) as month_name"),
            FacadesDB::raw("MONTH(created_at) as month_number")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month_number', 'month_name')
        ->orderBy('month_number')
        ->pluck('count', 'month_name');
    
        $usersLabels = $customers->keys();
        $usersData = $customers->values();
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
            'name' => 'required',
            'gender' => 'required',
            'age' => 'required|numeric',
            'blood_group' => 'required',
            'weight'=> 'nullable|string',
            'height'=> 'nullable|string',
            'bp_sugar'=> 'nullable|string',
            'sugar'=> 'nullable|string',
            'other_problems'=> 'nullable|string',
            'date' => 'required|date',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'package' => 'required',
            'amount' => 'nullable|numeric',
            'advance' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Check if the customer is already registered
        $existingCustomer = customers::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if ($existingCustomer) {
            return redirect()->route('add_user')->with('error', 'Customer is already registered.');
        }

        // Generate a unique customer ID in the format A1, A2, A3, etc.
        // $lastCustomer = customers::orderBy('id', 'desc')->first();
        // $lastCustomerId = $lastCustomer ? intval(substr($lastCustomer->customer_id, 1)) + 1 : 1;
        // $validatedData['customer_id'] = 'A' . $lastCustomerId;
        $lastCustomer = Customers::orderBy('id', 'desc')->first();

        if (!$lastCustomer) {
            // If no customers exist, start with ID 1
            $validatedData['customer_id'] = '1';
        } else {
            $lastCustomerId = $lastCustomer->customer_id;

            if (is_numeric($lastCustomerId)) {
                // If the last customer ID is numeric and less than 1000
                if ($lastCustomerId < 1000) {
                    $validatedData['customer_id'] = $lastCustomerId + 1;
                } else {
                    // If the last numeric ID is 1000, start with 'A1'
                    $validatedData['customer_id'] = 'A1';
                }
            } else {
                // Handle alphanumeric IDs
                preg_match('/([A-Z]*)(\d+)/', $lastCustomerId, $matches);
                $prefix = $matches[1]; // Alphabetic prefix (e.g., A, B)
                $number = intval($matches[2]); // Numeric part (e.g., 1, 2, ...)

                if ($number < 1000) {
                    // Increment the numeric part
                    $validatedData['customer_id'] = $prefix . ($number + 1);
                } else {
                    // Move to the next alphabetic prefix
                    $nextPrefix = $prefix ? chr(ord($prefix) + 1) : 'A';
                    $validatedData['customer_id'] = $nextPrefix . '1';
                }
            }
        }


        //Total Amount At Registrations
        $validatedData['total_amount'] = $validatedData['amount'] + $validatedData['advance'];

        // Calculate due_date based on package
        $packageDurations = [
            "Normal" => 1,
            "Cardio" => 1,
            "3 Month Normal" => 3,
            "3 Month Cardio" => 3,
            "12 Month Normal" => 12,
            "12 Month Cardio" => 12
        ];

        $selectedPackage = $request->package;
        $packageDuration = $packageDurations[$selectedPackage] ?? 0;

        // if ($packageDuration > 0) {
        //     $validatedData['due_date'] = \Carbon\Carbon::parse($request->date)->addMonths($packageDuration)->toDateString();
        // } else {
        //     $validatedData['due_date'] = null;
        // }

        // Check if the file was uploaded successfully
        if ($request->hasFile('photo')) {
            $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(base_path('photos'), $fileName);
            $validatedData['photo'] = $fileName;
        }

        // Save the validated data to the database
        $customer = customers::create($validatedData);

        // Send email notification
        //Mail::to($customer->email)->send(new CustomerRegistered($customer));

        return redirect()->route('add_user')->with('success', 'Customer added successfully.');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'age' => 'required|numeric',
            'blood_group' => 'required',
            'weight'=> 'nullable|string',
            'height'=> 'nullable|string',
            'bp_sugar'=> 'nullable|string',
            'sugar'=> 'nullable|string',
            'other_problems'=> 'nullable|string',
            'date' => 'required|date',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'package' => 'required',
            'amount' => 'nullable|numeric',
            'advance' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Check if the customer is already registered
        $existingCustomer = Customers::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if ($existingCustomer) {
            return redirect()->route('registration')->with('error', 'Customer added successfully, and email sent.');
        }

        // Generate a unique customer ID
        // $lastCustomer = Customers::orderBy('id', 'desc')->first();
        // $lastCustomerId = $lastCustomer ? intval(substr($lastCustomer->customer_id, 1)) + 1 : 1;
        // $validatedData['customer_id'] = $lastCustomerId;
        $lastCustomer = Customers::orderBy('id', 'desc')->first();

        if (!$lastCustomer) {
            // If no customers exist, start with ID 1
            $validatedData['customer_id'] = '1';
        } else {
            $lastCustomerId = $lastCustomer->customer_id;

            if (is_numeric($lastCustomerId)) {
                // If the last customer ID is numeric and less than 1000
                if ($lastCustomerId < 1000) {
                    $validatedData['customer_id'] = $lastCustomerId + 1;
                } else {
                    // If the last numeric ID is 1000, start with 'A1'
                    $validatedData['customer_id'] = 'A1';
                }
            } else {
                // Handle alphanumeric IDs
                preg_match('/([A-Z]*)(\d+)/', $lastCustomerId, $matches);
                $prefix = $matches[1]; // Alphabetic prefix (e.g., A, B)
                $number = intval($matches[2]); // Numeric part (e.g., 1, 2, ...)

                if ($number < 1000) {
                    // Increment the numeric part
                    $validatedData['customer_id'] = $prefix . ($number + 1);
                } else {
                    // Move to the next alphabetic prefix
                    $nextPrefix = $prefix ? chr(ord($prefix) + 1) : 'A';
                    $validatedData['customer_id'] = $nextPrefix . '1';
                }
            }
        }


        // Total Amount at Registration
        $validatedData['total_amount'] = ($validatedData['amount'] ?? 0) + ($validatedData['advance'] ?? 0);

        // Calculate due_date based on package
        $packageDurations = [
            "Normal" => 1,
            "Cardio" => 1,
            "3 Month Normal" => 3,
            "3 Month Cardio" => 3,
            "12 Month Normal" => 12,
            "12 Month Cardio" => 12
        ];

        $selectedPackage = $request->package;
        $packageDuration = $packageDurations[$selectedPackage] ?? 0;

        // if ($packageDuration > 0) {
        //     $validatedData['due_date'] = \Carbon\Carbon::parse($request->date)->addMonths($packageDuration)->toDateString();
        // } else {
        //     $validatedData['due_date'] = null;
        // }

        // Handle file upload
        if ($request->hasFile('photo')) {
            $fileName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(base_path('photos'), $fileName);
            $validatedData['photo'] = $fileName;
        }

        // Save the customer
        $customer = Customers::create($validatedData);

        // Send email notification
        //Mail::to($customer->email)->send(new CustomerRegistered($customer));

        return redirect()->route('registration')->with('success', 'Customer added successfully, and email sent.');
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
    public function edit($id){

        // Fetch the customer record by ID
        $customer = customers::findOrFail($id);
    
        // Return the edit view with the customer data
        return view('edit_user', compact('customer'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'age' => 'required|numeric',
            'weight'=> 'nullable|string',
            'height'=> 'nullable|string',
            'blood_group' => 'nullable|string',
            'bp_sugar'=> 'nullable|string',
            'sugar'=> 'nullable|string',
            'other_problems'=> 'nullable|string',
            'date' => 'required|date',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'package' => 'required',
            'amount' => 'nullable|numeric',
            'advance' => 'nullable|numeric',
            'photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Validate photo upload

        ]);

        // Fetch the customer record
        $customer = customers::findOrFail($id);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            
            if ($customer->photo && file_exists(base_path('photos/' . $customer->photo))) {
                unlink(base_path('photos/' . $customer->photo));
            }

            // Save the new photo
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(base_path('photos'), $fileName);
            $validatedData['photo'] = $fileName;
        }

        // Update the customer record
        $customer->update($validatedData);

        // Redirect back with a success message
        return redirect()->route('add_user')->with('success', 'Customer updated successfully.');
    }
    
    public function setBreak(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'break_start' => 'required|date',
            'break_end' => 'required|date|after_or_equal:break_start',
        ]);

        $customer = Customers::find($validated['customer_id']);
        $customer->break_start = $validated['break_start'];
        $customer->break_end = $validated['break_end'];
        $customer->save();

        return redirect()->back()->with('success', 'Break dates saved successfully.');
    }

    public function delete($id)
    {
        // Find the customer by ID
        $customer = customers::findOrFail($id);

        // Update the status to 0 (deleted)
        $customer->status = 0;
        $customer->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Customer status updated to deleted.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //Whatsapp Message Clicks Update
    public function updateClicks(Request $request)
    {
        $customer = Customers::findOrFail($request->id); // Find customer by ID
        $customer->increment('message_clicks'); // Increment click count
        return response()->json(['success' => true, 'clicks' => $customer->message_clicks]);
    }

}
