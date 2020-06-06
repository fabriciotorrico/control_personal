<?php

namespace App\Models\Partes;

use Illuminate\Database\Eloquent\Model;

class ParteDiario extends Model
{
    //
    protected $table = 'partes_diarios';
    protected $primaryKey = 'id_persona';

    /**
     * Get the Person that owns the Persona.
    */

    public function persona()
    {
        //return $this->belongsTo('App\Persona', 'foreign_key', 'local_key');
        return $this->belongsTo('App\Persona', 'id_persona', 'id_persona');
    }
}
