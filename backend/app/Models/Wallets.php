<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallets extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'amount'];

    public function users() {
        return $this->hasOne(User::class);
    }
}
