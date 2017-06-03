<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'title',
        'start',
        'url',
    ];
    protected $dates = ['start'];
}
