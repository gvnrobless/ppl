<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Hijo extends Model {
    protected $table = 'hijos';
    protected $primaryKey = 'idHijo';
    protected $fillable = [
      'idUsuario',
      'nombre_hijo',
      'fechaNac_hijo',
      'gradoEscolar_hijo',
      'estatus'
    ];
  }
