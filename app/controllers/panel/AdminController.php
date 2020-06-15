<?php
    namespace App\Controllers\Panel;
    use App\Controllers\BaseController;
    use Sirius\Validation\Validator;
    use App\Models\Usuario;
    use App\Models\Upload;

    class AdminController extends BaseController {

        public function getIndex() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->where('idRol', 1)->first();
          if($userData) {
            $usersData = Usuario::where('idRol', 1)->get();
            return $this->render('panel/admin.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData,
              'users' => $usersData
            ]);
          } else {
            header('Location:' . BASE_URL . 'admin');
          }
        }

        public function postIndex() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->where('idRol', 1)->first();
          if($userData) {
            $result = false;
            $error = "";
            $userMod = Usuario::where('correoUniva_usuario', $_POST['correo'])->update(['idRol' => 1]);
            $usersData = Usuario::where('idRol', 1)->get();
            if($userMod) {
              $result = true;
            } else {
              $error = "El correo introducido no fue encontrado";
            }
            return $this->render('panel/admin.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData,
              'users' => $usersData,
              'result' => $result,
              'errors' => $error
            ]);
          } else {
            header('Location:' . BASE_URL . 'admin');
          }
        }

        public function getDelete($correo) {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->where('idRol', 1)->first();
          if($userData) {
            $userMod = Usuario::where('correoUniva_usuario', $correo)->update(['idRol' => 2]);
            $usersData = Usuario::where('idRol', 1)->get();
            return $this->render('partials/delete.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData,
              'users' => $usersData
            ]);
          } else {
            header('Location:' . BASE_URL . 'admin');
          }
        }

        public function getAdministratives() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->where('idRol', 1)->first();
          if($userData) {
            $usersData = Usuario::query()->orderBy('nombre_usuario', 'asc')->get();
            return $this->render('panel/administratives.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData,
              'users' => $usersData
            ]);
          } else {
            header('Location:' . BASE_URL . 'admin');
          }
        }

        public function getView($correo) {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->where('idRol', 1)->first();
          if($userData) {
            $userDoc = Usuario::where('correoUniva_usuario', $correo)->first();
            $userId = $userDoc->idUsuario;
            $upload = Upload::where('idUsuario', $userId)->where('estatus', 1)->get();
            return $this->render('panel/documents.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData,
              'up' => $upload,
              'userSearch' => $userDoc
            ]);
          } else {
            header('Location:' . BASE_URL . 'admin');
          }
        }

        public function getReports() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->where('idRol', 1)->first();
          if($userData) {
            return $this->render('panel/reports.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData
            ]);
          } else {
            header('Location:' . BASE_URL . 'admin');
          }
        }

        public function postReports() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->where('idRol', 1)->first();
          if($userData) {
            $userCSV = Usuario::
            leftJoin('relaciones_familiares', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'relaciones_familiares.idUsuario');
            })
            ->leftJoin('hijos', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'hijos.idUsuario')
              ->where('hijos.estatus', '=', 1);
            })
            ->leftJoin('experiencia_profesional_previa_univa', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'experiencia_profesional_previa_univa.idUsuario')
              ->where('experiencia_profesional_previa_univa.estatus', '=', 1);
            })
            ->leftJoin('cargos_univa', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'cargos_univa.idUsuario')
              ->where('cargos_univa.estatus', '=', 1);
            })
            ->leftJoin('areas_interes_univa', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'areas_interes_univa.idUsuario')
              ->where('areas_interes_univa.estatus', '=', 1);
            })
            ->leftJoin('idiomas', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'idiomas.idUsuario')
              ->where('idiomas.estatus', '=', 1);
            })
            ->leftJoin('formacion_academica_actual', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'formacion_academica_actual.idUsuario')
              ->where('formacion_academica_actual.estatus', '=', 1);
            })
            ->leftJoin('estudios_prev', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'estudios_prev.idUsuario')
              ->where('estudios_prev.estatus', '=', 1);
            })
            ->leftJoin('capacitacion_recibida', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'capacitacion_recibida.idUsuario')
              ->where('capacitacion_recibida.estatus', '=', 1);
            })
            ->leftJoin('capacitacion_impartida', function ($join) {
              $join->on('usuarios.idUsuario', '=', 'capacitacion_impartida.idUsuario')
              ->where('capacitacion_impartida.estatus', '=', 1);
            })
            ->get();
            $csv = array();
            $usersAll = Usuario::all();
            for ($a = 0; $a < sizeof($usersAll); $a++) {
              $csv[$a]['nombre_usuario'] = $userCSV[$a]['nombre_usuario'];
              $csv[$a]['noNomina_usuario'] = $userCSV[$a]['noNomina_usuario'];
              $csv[$a]['ciudadEstadoNac_usuario'] = $userCSV[$a]['ciudadEstadoNac_usuario'];
              $csv[$a]['fechaNacimiento_usuario'] = $userCSV[$a]['fechaNacimiento_usuario'];
              $csv[$a]['sexo_usuario'] = $userCSV[$a]['sexo_usuario'];
              $csv[$a]['curp_usuario'] = $userCSV[$a]['curp_usuario'];
              $csv[$a]['noIMSS_usuario'] = $userCSV[$a]['noIMSS_usuario'];
              $csv[$a]['rfc_usuario'] = $userCSV[$a]['rfc_usuario'];
              $csv[$a]['calle_usuario'] = $userCSV[$a]['calle_usuario'];
              $csv[$a]['noExt_usuario'] = $userCSV[$a]['noExt_usuario'];
              $csv[$a]['noInt_usuario'] = $userCSV[$a]['noInt_usuario'];
              $csv[$a]['colonia_usuario'] = $userCSV[$a]['colonia_usuario'];
              $csv[$a]['cp_usuario'] = $userCSV[$a]['cp_usuario'];
              $csv[$a]['estado_usuario'] = $userCSV[$a]['estado_usuario'];
              $csv[$a]['municipio_usuario'] = $userCSV[$a]['municipio_usuario'];
              $csv[$a]['telefono_usuario'] = $userCSV[$a]['telefono_usuario'];
              $csv[$a]['celular_usuario'] = $userCSV[$a]['celular_usuario'];
              $csv[$a]['correoUniva_usuario'] = $userCSV[$a]['correoUniva_usuario'];
              $csv[$a]['correoPersonal_usuario'] = $userCSV[$a]['correoPersonal_usuario'];
              $csv[$a]['estadoCivil_rf'] = $userCSV[$a]['estadoCivil_rf'];
              $csv[$a]['fechaMatrimonio_rf'] = $userCSV[$a]['fechaMatrimonio_rf'];
              $csv[$a]['nombreConyuge_rf'] = $userCSV[$a]['nombreConyuge_rf'];
              $csv[$a]['fechaNacConyuge_rf'] = $userCSV[$a]['fechaNacConyuge_rf'];
              $csv[$a]['nombreMadre_rf'] = $userCSV[$a]['nombreMadre_rf'];
              $csv[$a]['nombrePadre_rf'] = $userCSV[$a]['nombrePadre_rf'];
              $csv[$a]['nombre_hijo'] = $userCSV[$a]['nombre_hijo'];
              $csv[$a]['fechaNac_hijo'] = $userCSV[$a]['fechaNac_hijo'];
              $csv[$a]['gradoEscolar_hijo'] = $userCSV[$a]['gradoEscolar_hijo'];
              $csv[$a]['empresa_eppu'] = $userCSV[$a]['empresa_eppu'];
              $csv[$a]['puesto_eppu'] = $userCSV[$a]['puesto_eppu'];
              $csv[$a]['noAnios_eppu'] = $userCSV[$a]['noAnios_eppu'];
              $csv[$a]['noMeses_eppu'] = $userCSV[$a]['noMeses_eppu'];
              $csv[$a]['nombrePuestoJefe_eppu'] = $userCSV[$a]['nombrePuestoJefe_eppu'];
              $csv[$a]['telefono_eppu'] = $userCSV[$a]['telefono_eppu'];
              $csv[$a]['cargo_cu'] = $userCSV[$a]['cargo_cu'];
              $csv[$a]['departamento_cu'] = $userCSV[$a]['departamento_cu'];
              $csv[$a]['anios_cu'] = $userCSV[$a]['anios_cu'];
              $csv[$a]['meses_cu'] = $userCSV[$a]['meses_cu'];
              $csv[$a]['nombrePuestoJefe_cu'] = $userCSV[$a]['nombrePuestoJefe_cu'];
              $csv[$a]['periodo_cu'] = $userCSV[$a]['periodo_cu'];
              $csv[$a]['departamento_aiu'] = $userCSV[$a]['departamento_aiu'];
              $csv[$a]['cargo_aiu'] = $userCSV[$a]['cargo_aiu'];
              $csv[$a]['motivo_aiu'] = $userCSV[$a]['motivo_aiu'];
              $csv[$a]['idioma_i'] = $userCSV[$a]['idioma_i'];
              $csv[$a]['hablado_i'] = $userCSV[$a]['hablado_i'];
              $csv[$a]['escrito_i'] = $userCSV[$a]['escrito_i'];
              $csv[$a]['leido_i'] = $userCSV[$a]['leido_i'];
              $csv[$a]['estudio_faa'] = $userCSV[$a]['estudio_faa'];
              $csv[$a]['escuela_faa'] = $userCSV[$a]['escuela_faa'];
              $csv[$a]['lunes_faa'] = $userCSV[$a]['lunes_faa'];
              $csv[$a]['martes_faa'] = $userCSV[$a]['martes_faa'];
              $csv[$a]['miercoles_faa'] = $userCSV[$a]['miercoles_faa'];
              $csv[$a]['jueves_faa'] = $userCSV[$a]['jueves_faa'];
              $csv[$a]['viernes_faa'] = $userCSV[$a]['viernes_faa'];
              $csv[$a]['sabado_faa'] = $userCSV[$a]['sabado_faa'];
              $csv[$a]['fechaInicio_faa'] = $userCSV[$a]['fechaInicio_faa'];
              $csv[$a]['fechaFin_faa'] = $userCSV[$a]['fechaFin_faa'];
              $csv[$a]['grado_ep'] = $userCSV[$a]['grado_ep'];
              $csv[$a]['institucion_ep'] = $userCSV[$a]['institucion_ep'];
              $csv[$a]['nombre_ep'] = $userCSV[$a]['nombre_ep'];
              $csv[$a]['periodo_ep'] = $userCSV[$a]['periodo_ep'];
              $csv[$a]['certificado_ep'] = $userCSV[$a]['certificado_ep'];
              $csv[$a]['titulo_ep'] = $userCSV[$a]['titulo_ep'];
              $csv[$a]['cedula_ep'] = $userCSV[$a]['cedula_ep'];
              $csv[$a]['nombre_cr'] = $userCSV[$a]['nombre_cr'];
              $csv[$a]['fechaCapacitacion_cr'] = $userCSV[$a]['fechaCapacitacion_cr'];
              $csv[$a]['noHoras_cr'] = $userCSV[$a]['noHoras_cr'];
              $csv[$a]['institucion_cr'] = $userCSV[$a]['institucion_cr'];
              $csv[$a]['certificado_cr'] = $userCSV[$a]['certificado_cr'];
              $csv[$a]['nombre_ci'] = $userCSV[$a]['nombre_ci'];
              $csv[$a]['fechaCapacitacion_ci'] = $userCSV[$a]['fechaCapacitacion_ci'];
              $csv[$a]['noHoras_ci'] = $userCSV[$a]['noHoras_ci'];
              $csv[$a]['institucion_ci'] = $userCSV[$a]['institucion_ci'];
              $csv[$a]['certificado_ci'] = $userCSV[$a]['certificado_ci'];
            }

            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=reporte.csv');
            $output = fopen("php://output", "w");
            fputcsv($output, array('Nombre', 'Nomina', 'Ciudad', 'Nacimiento', 'Sexo', 'Curp', 'Imss', 'Rfc', 'Calle', 'Ext', 'Int', 'Colonia', 'Cp', 'Estado', 'Municipio', 'Telefono', 'Celular', 'Correo Univa', 'Correo Personal', 'Estado Civil', 'Fecha Matrimonio', 'Nombre Conyuge', 'Fecha Conyuge', 'Nombre Madre', 'Nombre Padre', 'Nombre Hijo', 'Fecha Hijo', 'Grado Escolar Hijo', 'Empresa Anterior', 'Puesto Anterior', 'Anos', 'Meses', 'Jefe Anterior', 'Telefono', 'Cargos Univa', 'Departamento', 'Anos', 'Meses', 'Jefe', 'Periodo', 'Departamento Interes', 'Cargo', 'Motivo', 'Idioma', 'Hablado', 'Escrito', 'Leido', 'Estudios Actuales', 'Escuela', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Fecha de Inicio', 'Fecha de Finalizacion', 'Grado Cursado', 'Institucion', 'Carrera', 'Periodo', 'Certificado', 'Titulo', 'Cedula', 'Evento Recibido', 'Fecha', 'Horas', 'Institucion', 'Certificado', 'Evento Impartido', 'Fecha', 'Horas', 'Institucion', 'Certificado'));
            for ($i = 0; $i < sizeof($csv); $i++) {
              fputcsv($output, [$csv[$i]['nombre_usuario'], $csv[$i]['noNomina_usuario'], $csv[$i]['ciudadEstadoNac_usuario'], $csv[$i]['fechaNacimiento_usuario'], $csv[$i]['sexo_usuario'], $csv[$i]['curp_usuario'], $csv[$i]['noIMSS_usuario'], $csv[$i]['rfc_usuario'], $csv[$i]['calle_usuario'], $csv[$i]['noExt_usuario'], $csv[$i]['noInt_usuario'], $csv[$i]['colonia_usuario'], $csv[$i]['cp_usuario'], $csv[$i]['estado_usuario'], $csv[$i]['municipio_usuario'], $csv[$i]['telefono_usuario'], $csv[$i]['celular_usuario'], $csv[$i]['correoUniva_usuario'], $csv[$i]['correoPersonal_usuario'], $csv[$i]['estadoCivil_rf'], $csv[$i]['fechaMatrimonio_rf'], $csv[$i]['nombreConyuge_rf'], $csv[$i]['fechaNacConyuge_rf'], $csv[$i]['nombreMadre_rf'], $csv[$i]['nombrePadre_rf'], $csv[$i]['nombre_hijo'], $csv[$i]['fechaNac_hijo'], $csv[$i]['gradoEscolar_hijo'], $csv[$i]['empresa_eppu'], $csv[$i]['puesto_eppu'], $csv[$i]['noAnios_eppu'], $csv[$i]['noMeses_eppu'], $csv[$i]['nombrePuestoJefe_eppu'], $csv[$i]['telefono_eppu'], $csv[$i]['cargo_cu'], $csv[$i]['departamento_cu'], $csv[$i]['anios_cu'], $csv[$i]['meses_cu'], $csv[$i]['nombrePuestoJefe_cu'], $csv[$i]['periodo_cu'], $csv[$i]['departamento_aiu'], $csv[$i]['cargo_aiu'], $csv[$i]['motivo_aiu'], $csv[$i]['idioma_i'], $csv[$i]['hablado_i'], $csv[$i]['escrito_i'], $csv[$i]['leido_i'], $csv[$i]['estudio_faa'], $csv[$i]['escuela_faa'], $csv[$i]['lunes_faa'], $csv[$i]['martes_faa'], $csv[$i]['miercoles_faa'], $csv[$i]['jueves_faa'], $csv[$i]['viernes_faa'], $csv[$i]['sabado_faa'], $csv[$i]['fechaInicio_faa'], $csv[$i]['fechaFin_faa'], $csv[$i]['grado_ep'], $csv[$i]['institucion_ep'], $csv[$i]['nombre_ep'], $csv[$i]['periodo_ep'], $csv[$i]['certificado_ep'], $csv[$i]['titulo_ep'], $csv[$i]['cedula_ep'], $csv[$i]['nombre_cr'], $csv[$i]['fechaCapacitacion_cr'], $csv[$i]['noHoras_cr'], $csv[$i]['institucion_cr'], $csv[$i]['certificado_cr'], $csv[$i]['nombre_ci'], $csv[$i]['fechaCapacitacion_ci'], $csv[$i]['noHoras_ci'], $csv[$i]['institucion_ci'], $csv[$i]['certificado_ci']]);
            }
            
            return $this->render('panel/reports.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData
            ]);
          } else {
            header('Location:' . BASE_URL . 'admin');
          }
        }
    }