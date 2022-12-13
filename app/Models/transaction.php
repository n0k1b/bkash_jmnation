<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    //use HasFactory;
    protected $guarded = [];

    protected $appends = ['agent_profit'];
    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function getAgentProfitAttribute()
    {
        return $this->amount * 0.004;
    }

}
