<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Capacitacion_Impartida extends Model {
    protected $table = 'capacitacion_impartida';
    protected $primaryKey = 'idCI';
    protected $fillable = [
      'idUsuario',
      'nombre_ci',
      'fechaCapacitacion_ci',
      'noHoras_ci',
      'institucion_ci',
      'certificado_ci',
      'estatus'
    ];
  }
