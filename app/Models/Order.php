<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

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

    /**
     * 支払い確定済みにする（決済ゲートウェイからの確定通知を受けた時点で呼ぶ想定）
     */
    public function markAsPaid(): void
    {
        $this->update(['status' => self::STATUS_PAID]);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
}
