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

    public function sumbymonthyearFilterYear($year)
    {
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select concat(year(date)," ",MONTHNAME(date))  as yearmonth , SUM(value) as total from transactions where YEAR(date)='.$year.' group by yearmonth');
        }
        else{
            $array = DB::select('select concat(year(date)," ",MONTHNAME(date))  as yearmonth , SUM(value) as total from transactions where vcard='.auth::user()->username.' and YEAR(date)='.$year.' group by yearmonth');
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

    public function countPaymentTypeFilterYear($year)
    {
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select payment_type,count(payment_type) as count from transactions where year(date)='.$year.' group by payment_type');
        }
        else{
            $array = DB::select('select payment_type,count(payment_type) as count from transactions where vcard='.auth::user()->username.' and year(date)='.$year.' group by payment_type');
        }
        return response()->json($array);

    }

    public function counters()
    {
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select sum(balance) as balance,count(blocked) as vcards, round(avg(balance),2) as average,(select count(*) from transactions) as transactionCount from vcards');
        }
        else{
            $array = DB::select('select sum(value)as value,count(*) as numTransactions,round(AVG(old_balance),2) as avgBalance ,max(value) as highestTransaction from transactions where vcard = '.auth::user()->username);
        }
        return response()->json($array);

    }

    public function categories(){
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select c.name, count(c.name) as count from transactions t inner join categories c on t.category_id = c.id group by c.name order by count desc limit 5');
        }
        else{
            $array = DB::select('select c.name, count(c.name) as count from transactions t inner join categories c on t.category_id = c.id where c.vcard = '.auth::user()->username .' group by c.name order by count desc limit 5');
        }
        return response()->json($array);
    }

    public function categoriesFilterYear($year){
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select c.name, count(c.name) as count from transactions t inner join categories c on t.category_id = c.id where year(t.date) = '.$year.' group by c.name order by count desc limit 5');
        }
        else{
            $array = DB::select('select c.name, count(c.name) as count from transactions t inner join categories c on t.category_id = c.id where c.vcard = '.auth::user()->username .' and year(t.date) = '.$year.' group by c.name order by count desc limit 5');
        }
        return response()->json($array);
    }

    public function getYears(){
        if(auth::user()->user_type == 'A'){
            $array = DB::select('select year(date) as year from transactions group by year');
        }
        else{
            $array = DB::select('select year(date) as year from transactions where vcard = '.auth::user()->username.' group by year');
        }
        return response()->json($array);
    }


}
