<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id', 'id');
    }

    public function vcard()
    {
        return $this->belongsTo(VCard::class, 'vcard', 'phone_number');
    }
}
