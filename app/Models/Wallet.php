<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

}
