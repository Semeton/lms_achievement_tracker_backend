<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'achievement_type',
        'user_id'
    ];

    /**
     * Get the user that own the achievement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}