<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Partes\ParteDiario;
use PhpParser\Builder\Class_;

class Persona extends Model
{
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    /**
     * Get the User record associated with the Person.
     */
    public function usuario()
    {
        return $this->hasOne('App\User', 'id_persona', 'id_persona');
    }

    /**
     * Get the User record associated with the Person.
     */

        /**
     * Get the User record associated with the Person.
     */
    public function parte_diario()
    {
        return $this->hasMany(Models\Partes\ParteDiario::class, 'id_persona', 'id_persona');
    }
}
