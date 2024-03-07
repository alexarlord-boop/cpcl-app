<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $table = 'parsed_data';

    protected $fillable = [
        'section',
        'protocol',
        'type',
        'name',
        'description',
        'resource_location',
        'entityid',
        'dynamic_registration',
        'client_secret',
    ];
}
