<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cryptos';

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

}
