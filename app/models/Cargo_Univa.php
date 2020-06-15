<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Cargo_Univa extends Model {
    protected $table = 'cargos_univa';
    protected $primaryKey = 'idCU';
    protected $fillable = [
      'idUsuario',
      'cargo_cu',
      'departamento_cu',
      'anios_cu',
      'meses_cu',
      'nombrePuestoJefe_cu',
      'periodo_cu',
      'estatus'
    ];
  }
