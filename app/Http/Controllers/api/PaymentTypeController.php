<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentTypeResource;

class PaymentTypeController extends Controller
{
    public function index()
    {
        return PaymentTypeResource::collection(PaymentType::all());
    }
}
