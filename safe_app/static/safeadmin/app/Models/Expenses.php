<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Expenses extends Model
{
    use HasFactory, Notifiable;

    //customers table
    protected $table = 'tbl_expense';
    protected $primaryKey = 'id';
    protected $fillable = [
        'date',
        'purchase',
        'description',
        'amount',
    ];
}
