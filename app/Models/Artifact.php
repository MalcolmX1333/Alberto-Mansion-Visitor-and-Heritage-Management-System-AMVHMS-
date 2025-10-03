<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artifact extends Model
{
    protected $fillable = [
        'control_no',
        'item_name',
        'quantity',
        'location',
        'status',
        'condition', // added condition to fillable
    ];
}
