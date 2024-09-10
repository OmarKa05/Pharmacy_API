<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'scientific_composition',
        'price',
        'company_id',
        'shelf',
        'quantity',
        'expiry_date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function cartItems()
    {
    return $this->hasMany(CartItem::class);
    }
};
