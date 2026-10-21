<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'shipping_city',
        'delivery_option',
        'delivery_fee',
        'payment_method',
        'subtotal',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning text-dark',
            'processing' => 'bg-info text-dark',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function customerStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => ucfirst((string) $this->status),
        };
    }
}
