<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishingCompany extends Model
{
    // protected $table = 'pbcompanies';
    
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'website',
        'phone_number',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
