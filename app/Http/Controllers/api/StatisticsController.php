<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\ResponseFactory;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateYear($year)
    {
        $data = [
            'year' => $year
        ];
        $validator = Validator::make($data, [
            'year' => 'numeric|digits:4|min:2000|max:' . date('Y'),
        ]);
        if ($validator->fails()) {
            //return response()->json(['error'=>$validator->errors()], 401);
            return false;
        } else {
            return true;
        }
    }

    public function sumbymonthyear()
    {
        if (auth::user()->user_type == 'A') {
            $array = DB::select('select concat(year(date)," ",MONTHNAME(date))  as yearmonth , SUM(value) as total from transactions group by yearmonth');
        } else {
            $array = DB::select('select concat(year(date)," ",MONTHNAME(date))  as yearmonth , SUM(value) as total from transactions where vcard=' . auth::user()->username . ' group by yearmonth');
        }
        return response()->json($array);
    }

    public function sumbymonthyearFilterYear($year)
    {
        if ($this->validateYear($year) == true) {

            if (auth::user()->user_type == 'A') {
                $array = DB::select('select concat(year(date)," ",MONTHNAME(date))  as yearmonth , SUM(value) as total from transactions where YEAR(date)=' . $year . ' group by yearmonth');
            } else {
                $array = DB::select('select concat(year(date)," ",MONTHNAME(date))  as yearmonth , SUM(value) as total from transactions where vcard=' . auth::user()->username . ' and YEAR(date)=' . $year . ' group by yearmonth');
            }
            return response()->json($array);
        } else {
            return response()->json(['error' => 'Invalid year'], 401);
        }
    }

    public function countPaymentType()
    {
        if (auth::user()->user_type == 'A') {
            $array = DB::select('select payment_type,count(payment_type) as count from transactions group by payment_type');
        } else {
            $array = DB::select('select payment_type,count(payment_type) as count from transactions where vcard=' . auth::user()->username . ' group by payment_type;');
        }
        return response()->json($array);
    }

    public function countPaymentTypeFilterYear($year)
    {
        if ($this->validateYear($year) == true) {
            if (auth::user()->user_type == 'A') {
                $array = DB::select('select payment_type,count(payment_type) as count from transactions where year(date)=' . $year . ' group by payment_type');
            } else {
                $array = DB::select('select payment_type,count(payment_type) as count from transactions where vcard=' . auth::user()->username . ' and year(date)=' . $year . ' group by payment_type');
            }
            return response()->json($array);
        } else {
            return response()->json(['error' => 'Invalid year'], 401);
        }
    }

    public function counters()
    {
        if (auth::user()->user_type == 'A') {
            $array = DB::select('select sum(balance) as balance,count(blocked) as vcards, round(avg(balance),2) as average,(select count(*) from transactions) as transactionCount from vcards');
        } else {
            $array = DB::select('select sum(value)as value,count(*) as numTransactions,round(AVG(old_balance),2) as avgBalance ,max(value) as highestTransaction from transactions where vcard = ' . auth::user()->username);
        }
        return response()->json($array);
    }

    public function categories()
    {
        if (auth::user()->user_type == 'A') {
            $array = DB::select('select c.name, count(c.name) as count from transactions t inner join default_categories c on t.category_id = c.id group by c.name order by count desc limit 5');
        } else {
            $array = DB::select('select c.name, count(c.name) as count from transactions t inner join categories c on t.category_id = c.id where c.vcard = ' . auth::user()->username . ' group by c.name order by count desc limit 5');
        }
        return response()->json($array);
    }

    public function categoriesFilterYear($year)
    {

        if ($this->validateYear($year) == true) {
            if (auth::user()->user_type == 'A') {
                $array = DB::select('select c.name, count(c.name) as count from transactions t inner join default_categories c on t.category_id = c.id where year(t.date) = ' . $year . ' group by c.name order by count desc limit 5');
            } else {
                $array = DB::select('select c.name, count(c.name) as count from transactions t inner join categories c on t.category_id = c.id where c.vcard = ' . auth::user()->username . ' and year(t.date) = ' . $year . ' group by c.name order by count desc limit 5');
            }
            return response()->json($array);
        } else {
            return response()->json(['error' => 'Invalid year'], 401);
        }
    }

    public function years()
    {
        if (auth::user()->user_type == 'A') {
            $array = DB::select('select year(date) as year from transactions group by year');
        } else {
            $array = DB::select('select year(date) as year from transactions where vcard = ' . auth::user()->username . ' group by year');
        }
        return response()->json($array);
    }

    public function balanceOverTime(){
        if (auth::user()->user_type == 'A') {
            return response()->json(['error' => 'Invalid Request'], 401);
        } else {
            $array = DB::select('select round(avg(new_balance),2) as balance," " as date from transactions where vcard =' . auth::user()->username . ' group by month(date),year(date) order by date asc');
        }
        return response()->json($array);
    }
}
