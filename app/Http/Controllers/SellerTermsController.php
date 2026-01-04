<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerTermsController extends Controller
{
    public function index()
    {
        return view('seller.sellerTerms');
    }
}