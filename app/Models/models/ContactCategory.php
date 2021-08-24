<?php

namespace App\Models\models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactCategory extends Model
{
    use HasFactory;

    protected $table='category';

    protected $fillable = [
        'CategoryID',
        'CategoryName',
    ];

    protected $primaryKey = 'CategoryID';

    public $timestamps = false;
}
