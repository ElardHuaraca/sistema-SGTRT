<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExchangeRates;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == null) {
            Auth::logout();
            abort(403, 'No tiene permisos para acceder a esta página');
        }
        $exchangeRates = ExchangeRates::first();
        return view('home', ['exchangeRates' => $exchangeRates]);
    }

    public function updateTChange(Request $request)
    {
        $exchangeRates = ExchangeRates::first();
        $exchangeRates->value = $request->value;
        $exchangeRates->save();
        return response()->json(['value' => $exchangeRates->value], 200);
    }

    public static function getExchangeRates()
    {
        $exchangeRates = null;
        try {
            $exchangeRates = ExchangeRates::first();
        } catch (\Throwable $th) {
            return new ExchangeRates(['value' => 00.00]);
        }
        return $exchangeRates;
    }
}
