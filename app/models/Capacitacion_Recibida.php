<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Capacitacion_Recibida extends Model {
    protected $table = 'capacitacion_recibida';
    protected $primaryKey = 'idCR';
    protected $fillable = [
      'idUsuario',
      'nombre_cr',
      'fechaCapacitacion_cr',
      'noHoras_cr',
      'institucion_cr',
      'certificado_cr',
      'estatus'
    ];
  }
