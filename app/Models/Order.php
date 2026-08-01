<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'payment_method',
        'shipping_name',
        'shipping_zip',
        'shipping_address',
        'shipping_tel',
    ];

    /**
     * 注文したユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 注文明細
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
