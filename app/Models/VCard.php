<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VCard extends Model
{
    use HasFactory;

    protected $table = "vcards";

    protected $primaryKey = 'phone_number';
    protected $keyType = 'string';

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vcard', 'phone_number');
    }

    public function transactionsMirrored()
    {
        return $this->hasMany(Transaction::class, 'pair_vcard', 'phone_number');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'vcard', 'phone_number');
    }
}
