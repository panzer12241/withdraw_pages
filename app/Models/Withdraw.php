<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdraw extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'customer_data' => 'json',
        'user_data' => 'json',
        'bank_data' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'withdraw_time' => 'datetime',
        'bank_time' => 'datetime',
    ];

    protected $guarded = ['id'];

    public function line_config()
    {
        return $this->hasOne(Prefix::class, 'name', 'prefix');
    }

    public function to_prefix()
    {
        return $this->hasOne(Prefix::class, 'name', 'prefix');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user_game()
    {
        return $this->hasMany(UserGame::class, 'customer_id', 'customer_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

}
