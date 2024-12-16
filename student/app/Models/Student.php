<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address_line_1',
        'address_line_2', // Include if you have this field in your form
        'city',
        'state',
        'postal_code',
        'country',
    ];
}
