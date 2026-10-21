<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'slug',
        'price',
        'stock',
        'unit',
        'tablets_per_strip',
        'image',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'tablets_per_strip' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? Storage::url($this->image)
            : asset('images/medicine-placeholder.svg');
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('brand', 'like', "%{$term}%");
        });
    }

    public function scopeInCategory($query, $categoryId)
    {
        if (! $categoryId) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }
}
