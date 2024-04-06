<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\UserToken;

class VoucherController extends Controller
{
    const VOUCHER_COUNT_LIMIT = 10;

    /**
     * For testing only to see all vouchers
     */
    public function index()
    {
        return response()->json(Voucher::with('user')->get(), 201);
    }

    /**
     * Create voucher
     */
    public function store(Request $request)
    {
        // Verify the number of vouchers this user have
        $totalVouchers = Voucher::totalVouchers($request->user_id);
        if ($totalVouchers >= self::VOUCHER_COUNT_LIMIT) {
            return response()->json([
                'error' => 'Too many vouchers created, you have more than ' . self::VOUCHER_COUNT_LIMIT
            ], Response::HTTP_FORBIDDEN);
        }
        
        // Create a voucher
        $voucher = Voucher::store($request->user_id);

        return response()->json([
            'message' => 'Voucher created successfully',
            'voucher' => $voucher->code,
            'totalVouchers' => $totalVouchers + 1
        ], Response::HTTP_CREATED);
    }

    /**
     * Lists all the vouchers of a user
     */
    public function userVouchers(Request $request)
    {

    }
}
