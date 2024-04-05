<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Store a new voucher.
     *
     * @param int $userId from the id of users table
     * @return Voucher
     */
    public static function store($userId)
    {
        return self::create([
            'user_id' => $userId,
            'code' => self::generateCode(),
        ]);
    }

    /**
     * Get the user that owns the voucher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a random code of length n
     *
     * @return string
     */
    private static function generateCode(int $length=5)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }
}
