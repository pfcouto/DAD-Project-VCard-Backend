<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type', 'code');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function vcard()
    {
        return $this->belongsTo(VCard::class, 'vcard  ', 'phone_number');
    }

    public function vcardMirrored()
    {
        return $this->belongsTo(VCard::class, 'pair_vcard  ', 'phone_number');
    }

    public function transactionsPair()
    {
        return $this->hasOne(Transaction::class, 'pair_transaction', 'id');
    }

    public function transactionsPair2()
    {
        return $this->belongsTo(Transaction::class, 'pair_transaction', 'id');
    }
}
