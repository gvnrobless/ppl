<?php

    namespace App\Controllers;

    use Sirius\Validation\Validator;
    use App\Models\Usuario;
    use App\Models\Administrador;
    require 'nusoap.php';

    class AuthController extends BaseController {

        public function getIndex() {
            return $this->render('login.twig');
        }

        public function postIndex() {
            $validator = new Validator();
            $validator->add('usuario', 'required');
            $validator->add('contrasenia', 'required');

            if($validator->validate($_POST)) {
              $client = new \SoapClient("http://ws.univa.mx/WsPortalBiblioteca/wsPortalBiblioteca.asmx?WSDL",array('cache_wsdl' => WSDL_CACHE_NONE,'trace' => TRUE, 'SOAP_version=>SOAP_1_1', 'encoding'=>'UTF-8'));
              $parametros = array("strMatricula" => $_POST['usuario'], "StrPassword" => $_POST['contrasenia']);
              $resultado = $client->strGetNombre($parametros)->strGetNombreResult;
              if(strlen($resultado) > 0){
                $_SESSION['name'] = $resultado;
                $_SESSION['username'] = $_POST['usuario'];
                header('Location:' . BASE_URL . 'admin');
                return null;
              }
                //Auth not Ok
                $validator->addMessage('Error', 'El nombre de usuario y la contraseña son incorrectos');
            }
            $errors = $validator->getMessages();
            return $this->render('login.twig', [
                'errors' => $errors
            ]);
        }

        public function getLogout() {
            unset($_SESSION['name']);
            unset($_SESSION['username']);
            header('Location: ' . BASE_URL);
        }
    }
