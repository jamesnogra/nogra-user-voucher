<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Voucher;

class VoucherController extends Controller
{
    const VOUCHER_COUNT_LIMIT = 10;

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
     * Deletes a voucher by code
     */
    public function delete(Request $request)
    {
        $validatedData = $request->validate(['voucher_code' => 'required']);
        
        // Validate the paring of user_id and voucher code
        $voucher = Voucher::where('user_id', $request->user_id)
            ->where('code', $request->voucher_code)
            ->first();
        if (!$voucher) {
            return response()->json([
                'error' => 'Voucher code not found for this user',
            ], Response::HTTP_NOT_FOUND);
        }
        
        // Delete this voucher
        $voucher->delete();
        return response()->json([
            'message' => 'Voucher has been deleted successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * Lists all the vouchers of a user
     */
    public function userVouchers(Request $request)
    {
        return Voucher::select('code as voucher_code')
            ->where('user_id', $request->user_id)
            ->get();
    }
}
