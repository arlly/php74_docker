<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $table = 'informations';
    protected $guarded = ['deleteImage'];
}
