<?php

namespace App\Models;

use App\DB\Core\StringField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    use HasFactory;
   
    public function saveableFields(): array
    {
        return [
            'name' => StringField::new(),
            'city_id' => StringField::new(),
        ];
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function ward()
    {
        return $this->hasMany(Ward::class);
    }
}
