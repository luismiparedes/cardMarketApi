<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    public function index(){
        return $this->hasMany(CardsCollection::class);
    }
    public function sale(){
        return $this->hasMany(Sale::class);
    }
}
