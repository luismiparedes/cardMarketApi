<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardsCollection extends Model
{
    use HasFactory;

    public function card(){
        return $this->belongsTo(Card::class);
    }
    public function collection(){
        return $this->belongsTo(Collection::class);
    }
}

