<?php
  namespace App\Models;
  use Illuminate\Database\Eloquent\Model;

  class Idioma extends Model {
    protected $table = 'idiomas';
    protected $primaryKey = 'idIdioma';
    protected $fillable = [
      'idUsuario',
      'idioma_i',
      'hablado_i',
      'escrito_i',
      'leido_i',
      'estatus'
    ];
  }
