<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // TAMBAHKAN INI:
    protected $fillable = [
        'order_id', 
        'transaction_id', 
        'payment_method', 
        'amount', 
        'change', 
        'status',
        'payment_proof'
    ];
}