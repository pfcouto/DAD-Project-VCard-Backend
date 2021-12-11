<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    public function index($transaction){

        //dd($transaction);
    $transactionRow = Transaction::query()->where('id', $transaction)->get();

    $vcardRow = DB::select('select email,name,phone_number from vcards where phone_number = (select vcard from transactions where id=' . $transaction . ')');

    $pairVCardRow = DB::select('select v.name,v.email,v.phone_number from transactions t inner join vcards v on t.pair_vcard = v.phone_number where t.pair_vcard is not null and t.id = ' . $transaction);
    
    $merged = array(
        'transaction' => $transactionRow[0],
        'vcard' => $vcardRow[0],
        'pair_vcard' => $pairVCardRow[0] ?? null
    );

    $pdf = PDF::loadView('pdf.index',compact('merged'));

    $pdf->setPaper('a4' , 'portrait');

    return $pdf->output();
    }
}
