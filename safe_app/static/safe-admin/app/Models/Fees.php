<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fees extends Model
{
    // Addfees table
    protected $table = 'tbl_addfees';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'customerid',
        'month',
        'join_date',
        'date',
        'package',
        'amount',
        'due_date'
    ];
}
