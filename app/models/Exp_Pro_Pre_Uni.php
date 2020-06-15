<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Exp_Pro_Pre_Uni extends Model {
    protected $table = 'experiencia_profesional_previa_univa';
    protected $primaryKey = 'idEPPU';
    protected $fillable = [
      'idUsuario',
      'empresa_eppu',
      'puesto_eppu',
      'noAnios_eppu',
      'noMeses_eppu',
      'nombrePuestoJefe_eppu',
      'telefono_eppu',
      'estatus'
    ];
  }
