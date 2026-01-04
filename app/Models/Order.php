<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // TAMBAHKAN INI:
    protected $fillable = [
        'user_id', 
        'table_id', 
        'invoice_code', 
        'total_price', 
        'status', 
        'note'
    ];

    // Relasi (Pastikan ini ada biar nanti gampang panggil data)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}