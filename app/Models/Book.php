<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'language',
        'year',
        'number_pages',
        'publishing_company_id'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function pbcompany()
    {
        return $this->belongsTo(PublishingCompany::class, 'publishing_company_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function categories1()
    {
        return $this->hasMany(Category::class);
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
