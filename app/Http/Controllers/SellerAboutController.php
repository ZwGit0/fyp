<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerAboutController extends Controller
{
    public function index()
    {
        return view('auth.seller.sellerAbout');
    }
}
