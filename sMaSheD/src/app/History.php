<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'history';

    public function miningProp()
    {
        return $this->belongsTo(MiningProp::class);
    }
}
