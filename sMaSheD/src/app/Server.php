<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'servers';

    public function pool()
    {
        return $this->belongsTo(Pool::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }


}
