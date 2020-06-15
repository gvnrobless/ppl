<?php
    namespace App\Controllers\Admin;

    use App\Controllers\BaseController;
    use App\Models\Usuario;

    class IndexController extends BaseController {

        public function getIndex() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->first();
          if($userData) {
            return $this->render('admin/index.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData
            ]);
          } else {
            return $this->render('admin/index.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
            ]);
          }
        }

    }
