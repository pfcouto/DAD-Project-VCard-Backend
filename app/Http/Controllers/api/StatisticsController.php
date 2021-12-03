<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\ResponseFactory;
use Response;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function sumbymonthyear()
    {
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select concat(year(date)," ",MONTHNAME(date))  as yearmonth , SUM(value) as total from transactions group by yearmonth');
        }
        else{
            $array = DB::select('select concat(year(date)," ",MONTHNAME(date))  as yearmonth , SUM(value) as total from transactions where vcard='.auth::user()->username.' group by yearmonth');
        }
        return response()->json($array);

    }

    public function countPaymentType()
    {
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select payment_type,count(payment_type) as count from transactions group by payment_type');
        }
        else{
            $array = DB::select('select payment_type,count(payment_type) as count from transactions where vcard='.auth::user()->username.' group by payment_type;');
        }
        return response()->json($array);

    }

    public function counters()
    {
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select sum(balance) as balance,count(blocked) as vcards, avg(balance) as average,(select count(*) from transactions) as transactionCount from vcards');
        }
        else{
            $array = DB::select('select sum(value)as value,count(*) as numTransactions,AVG(old_balance) as avgBalance ,max(value) as highestTransaction from transactions where vcard = '.auth::user()->username);
        }
        return response()->json($array);

    }


}
