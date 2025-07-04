<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_weight',
    ];

    protected $casts = [
        'target_weight' => 'decimal:1',
    ];

    /**
     * この体重目標を所有するユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}