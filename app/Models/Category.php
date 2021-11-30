<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;

    use SoftDeletes;
    // public $timestamps = false;

    protected $table = 'categories';

    protected $fillable = ['id', 'vcard', 'type', 'name'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id', 'id');
    }

    public function vcard()
    {
        return $this->belongsTo(VCard::class, 'vcard', 'phone_number');
    }
}
