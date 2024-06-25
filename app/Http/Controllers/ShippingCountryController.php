<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ShippingCountry;
use Illuminate\Http\Request;

class ShippingCountryController extends Controller
{
    public function index()
    {
        $countries = ShippingCountry::all();
        return $countries;
    }


    public function store(Request $request)
    {
        $request->validate([
            'countries' => 'required|string',
        ]);

        $countries = explode(',', $request->input('countries'));
        foreach ($countries as $country) {
            ShippingCountry::create(['country_name' => trim($country)]);
        }

        return response()->json(['message'=>'Countries added successfully']);
    }
}
