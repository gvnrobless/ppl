<?php
    namespace App\Controllers\Admin;
    use App\Controllers\BaseController;
    use App\Models\Usuario;
    use App\Models\Upload;

    class ProfileController extends BaseController {

        public function getIndex() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->first();
          if($userData) {
            $userId = $userData->idUsuario;
            $upload = Upload::where('idUsuario', $userId)->where('estatus', 1)->get();
            return $this->render('admin/profile.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData,
              'up' => $upload
            ]);
          } else {
            return $this->render('admin/profile.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
            ]);
          }
        }
    }
