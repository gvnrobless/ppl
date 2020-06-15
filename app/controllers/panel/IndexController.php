<?php

  namespace App\Controllers\Panel;

  use App\Controllers\BaseController;
  use App\Models\Usuario;

  class IndexController extends BaseController {

    public function getIndex() {
      $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->where('idRol', 1)->first();
      if($userData) {
        $ladiesData = Usuario::where('sexo_usuario', "FEMENINO")->get();
        $gentlemanData = Usuario::where('sexo_usuario', "MASCULINO")->get();
        $usersData = Usuario::query()->orderBy('idUsuario', 'desc')->get();
        return $this->render('panel/index.twig', [
          'name' => $_SESSION['name'],
          'username' => $_SESSION['username'],
          'user' => $userData,
          'ladies' => $ladiesData,
          'gentleman' => $gentlemanData,
          'users' => $usersData
        ]);
      } else {
        header('Location:' . BASE_URL . 'admin');
      }
    }
  }
