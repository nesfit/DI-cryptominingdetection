<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ports';

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function crypto()
    {
        return $this->belongsTo(Crypto::class);
    }

}
