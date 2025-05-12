<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Field;
use App\Models\Banner;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{
    public function index()
    {
        $field = Field::where('status', 1)->orderBy('id', 'ASC')->paginate(5);
        $banner = Banner::where('status', 1)->orderBy('id', 'ASC')->paginate(1);
        $transactionData = Transaction::get();
        return view('frontend.main.pages.front.index', compact('field', 'banner', 'transactionData'));
    }
}
