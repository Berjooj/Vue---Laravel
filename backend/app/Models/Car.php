<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = "cars";

    protected $fillable = [
        'id',
        'brand',
        'year',
        'plate',
        'user_id'
    ];

    public function reservation()
    {
        return $this->hasOne(User::class);
    }
}
