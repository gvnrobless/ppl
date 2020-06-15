<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Formacion_Academica_Actual extends Model {
    protected $table = 'formacion_academica_actual';
    protected $primaryKey = 'idFAA';
    protected $fillable = [
      'idUsuario',
      'estudio_faa',
      'escuela_faa',
      'lunes_faa',
      'martes_faa',
      'miercoles_faa',
      'jueves_faa',
      'viernes_faa',
      'sabado_faa',
      'fechaInicio_faa',
      'fechaFin_faa',
      'estatus'
    ];
  }
