<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Voucher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'code'
    ];

    /**
     * Store a new voucher
     *
     * @param int $userId from the id of users table
     * @return Voucher
     */
    public static function store(int $userId)
    {
        return self::create([
            'user_id' => $userId,
            'code' => strtoupper(Str::random(5))
        ]);
    }

    /**
     * Counts the number of vouchers a user has
     *
     * @param int $userId from the id of users table
     * @return int
     */
    public static function totalVouchers(int $userId)
    {
        return self::where('user_id', $userId)->count();
    }

    /**
     * Get the user that owns the voucher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
