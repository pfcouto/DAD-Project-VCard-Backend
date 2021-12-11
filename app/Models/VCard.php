<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VCard extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vcards';
    protected $primaryKey = 'phone_number';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'phone_number',
        'name',
        'email',
        'photo_url',
        'password',
        'confirmation_code',
        'blocked',
        'balance',
        'max_debit',
        'custom_options',
        'custom_data'
    ];

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

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'phone_number');
    }
}
