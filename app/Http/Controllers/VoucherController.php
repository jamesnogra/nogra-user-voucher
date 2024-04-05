<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    /**
     * For testing only to see all vouchers
     */
    public function index()
    {
        return response()->json(Voucher::with('user')->get(), 201);
    }
}
