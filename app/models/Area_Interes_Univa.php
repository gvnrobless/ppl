<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Area_Interes_Univa extends Model {
    protected $table = 'areas_interes_univa';
    protected $primaryKey = 'idAIU';
    protected $fillable = [
      'idUsuario',
      'departamento_aiu',
      'cargo_aiu',
      'motivo_aiu',
      'estatus'
    ];
  }
