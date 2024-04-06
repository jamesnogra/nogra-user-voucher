<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserToken extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token',
        'expiry'
    ];

    /**
     * Store a new user token.
     *
     * @param int $userId from the id of users table
     * @return UserToken
     */
    public static function store(int $userId)
    {
        return self::create([
            'user_id' => $userId,
            'token' => Str::random(32),
            'expiry' => Carbon::now()->addHour()
        ]);
    }

    /**
     * Validate a token
     * 
     * @param int $token from the token of user_tokens table
     * @return UserToken
     */
    public static function validateToken(string $token)
    {
        return self::where('token', $token)
            ->where('expiry', '>', Carbon::now())
            ->first();
    }
}
