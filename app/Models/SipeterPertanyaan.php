<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SipeterPertanyaan extends Model
{
    protected $table = 'sipeter_pertanyaans';

    protected $fillable = [
        'pertanyaan',
    ];
}