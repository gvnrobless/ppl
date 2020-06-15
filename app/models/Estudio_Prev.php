<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Estudio_Prev extends Model {
    protected $table = 'estudios_prev';
    protected $primaryKey = 'idEP';
    protected $fillable = [
      'idUsuario',
      'grado_ep',
      'institucion_ep',
      'nombre_ep',
      'periodo_ep',
      'certificado_ep',
      'titulo_ep',
      'cedula_ep',
      'estatus'
    ];
  }
