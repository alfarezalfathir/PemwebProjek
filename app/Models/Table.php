<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number', // M-01
        'capacity',     // 4
        'location',     // indoor/outdoor
        'status'        // available/occupied
    ];
}