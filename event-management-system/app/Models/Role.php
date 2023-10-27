<?php

namespace App\Models;

use App\DB\Core\StringField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

       protected $fillable = [
        'name'
    ];

    
    public function saveableFields(): array
    {
        return [
            'name' => StringField::new()
        ];
    }
}
