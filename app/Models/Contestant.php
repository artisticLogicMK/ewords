<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contestant extends Model
{
    // Make all colums fillable -> since all will be filled
    protected $guarded = [];

    // Use {slug} as default route key name instead
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function voteTransactions()
    {
        return $this->hasMany(VoteTransaction::class);
    }
}
