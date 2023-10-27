<?php

namespace App\Models;

use App\DB\Core\StringField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrTicket extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['ad_hoc_id', 'qr_code'];
    public function saveableFields(): array
    {
        return [
            'ad_hoc_id' => StringField::new(),
            'qr_code' => StringField::new(),
        ];
    }
    public function ad_hoc()
    {
        return $this->belongsTo(AdHoc::class);
    }
}
