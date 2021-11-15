<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    protected $table = 'payment_types';
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_type', 'code');
    }
}
