<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class Customers extends Model
{
    use HasFactory, Notifiable;

    //customers table
    protected $table = 'customers';
    protected $primaryKey = 'id';
     protected $fillable = [
        'name',
        'customer_id',
        'gender',
        'age',
        'weight',
        'height',
        'blood_group',
        'bp_sugar',
        'sugar',
        'other_problems',
        'date',
        'email',
        'phone',
        'address',
        'package',
        'amount',
        'advance',
        'total_amount',
        'due_date',
        'month',
        'photo',
    ];


}
