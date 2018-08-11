<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pools';

    public function servers()
    {
        return $this->hasMany(Server::class);
    }
}
