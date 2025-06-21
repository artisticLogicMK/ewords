<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteTransaction extends Model
{
    // Make all colums fillable -> since all will be filled
    protected $guarded = [];

    public function contestant()
    {
        return $this->belongsTo(Contestant::class);
    }

}
