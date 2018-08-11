<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MiningProp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'miningProperties';

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}
