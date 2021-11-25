<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['id', 'vcard', 'date', 'datetime', 'type', 'value', 'old_balance', 'new_balance', 'payment_type', 
        'payment_reference', 'pair_transaction', 'pair_vcard', 'category_id', 'description'];

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
        return $this->belongsTo(VCard::class, 'vcard', 'phone_number');
    }

    public function pairVCard()
    {
        return $this->belongsTo(VCard::class, 'pair_vcard', 'phone_number');
    }

    public function pairTransactions()
    {
        return $this->hasOne(Transaction::class, 'pair_transaction', 'id');
    }

    public function pairTransactions2()
    {
        return $this->belongsTo(Transaction::class, 'pair_transaction', 'id');
    }
}
