<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FeesController;
use App\Http\Middleware\AuthCheck;


// Welcome Route
Route::get('/', function () {
    return view('index');
})->name('index');

// Login form route
Route::get('/login', function () {
    return view('login');
})->name('login.form');

// Login action
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Dashboard (protected route)
Route::get('/add_user', [CustomerController::class, 'index'])->name('add_user');

// Dashboard (protected route)
Route::get('/dashboard', [CustomerController::class, 'index2'])->name('dashboard');

//Add User Details
Route::resource('/customers', CustomerController::class);

//Whatsapp Clicks Update Route
Route::post('/update-clicks', [CustomerController::class, 'updateClicks'])->name('update.clicks');

//Edit Customer Data
Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');

//Update Customer Data
Route::post('/customer/{id}/update', [CustomerController::class, 'update'])->name('customer.update');

//Customer Delete
Route::patch('/customer/{id}/delete', [CustomerController::class, 'delete'])->name('customer.delete');


//Fees due details Protected Route
Route::get('/fees_due_details',[CustomerController::class, 'index3'])->name('fees_due_details');

//Expense details Protected Route
Route::get('/expense', [ExpensesController::class, 'index'])->name('expense');

//Add Expense Details
Route::resource('/add_expense', ExpensesController::class);

//Edit Expense Data
Route::get('/expense/{id}/edit', [ExpensesController::class, 'edit'])->name('expense.edit');

//Update Expense Data
Route::post('/expense/{id}/update', [ExpensesController::class, 'update'])->name('expense.update');

//Delete Expense
Route::patch('/expense/{id}/delete', [ExpensesController::class, 'delete'])->name('expense.delete');

//Expense report details Protected Route
Route::get('/expense_report', [ExpensesController::class, 'index2'])->name('expense_report');

//get monthly Expense report
Route::post('/expense_report_monthly', [ExpensesController::class, 'expense_report'])->name('expense_report_monthly');

//Income report details Protected Route
Route::get('/income_report', [FeesController::class, 'index2'])->name('income_report');

//get monthly Income report
Route::post('/income_report_monthly', [FeesController::class, 'income_report'])->name('income_report_monthly');

//Add Expense Details
Route::resource('/add_customer_fees', FeesController::class);

//Addfees details Protected Route
Route::get('/addfees', [FeesController::class, 'index'])->name('addfees');

//Search Customers
Route::get('/search-customers', [FeesController::class, 'searchCustomers'])->name('search.customers');

//Edit fees Data
Route::get('/fees/{id}/edit', [FeesController::class, 'edit'])->name('fees.edit');

//Update fees Data
Route::post('/fees/{id}/update', [FeesController::class, 'update'])->name('fees.update');

//Delete fees
Route::patch('/fees/{id}/delete', [FeesController::class, 'delete'])->name('fees.delete');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


