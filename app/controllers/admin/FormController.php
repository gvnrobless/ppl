<?php
    namespace App\Controllers\Admin;
    use App\Controllers\BaseController;
    use App\Models\Usuario;
    use App\Models\Area_Interes_Univa;
    use App\Models\Capacitacion_Impartida;
    use App\Models\Capacitacion_Recibida;
    use App\Models\Cargo_Univa;
    use App\Models\Estudio_Prev;
    use App\Models\Exp_Pro_Pre_Uni;
    use App\Models\Formacion_Academica_Actual;
    use App\Models\Hijo;
    use App\Models\Idioma;
    use App\Models\RelacionFamiliar;
    use App\Models\Upload;
    use Sirius\Validation\Validator;

    class FormController extends BaseController {

        public function getIndex() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->first();
          if($userData) {
            $userId = $userData->idUsuario;
            $familyData = RelacionFamiliar::where('idUsuario', $userId)->first();
            $childrenData = Hijo::where('idUsuario', $userId)->where('estatus', 1)->get();
            $eppuData = Exp_Pro_Pre_Uni::where('idUsuario', $userId)->where('estatus', 1)->get();
            $positionData = Cargo_Univa::where('idUsuario', $userId)->where('estatus', 1)->get();
            $aiuData = Area_Interes_Univa::where('idUsuario', $userId)->where('estatus', 1)->get();
            $languageData = idioma::where('idUsuario', $userId)->where('estatus', 1)->get();
            $faaData = Formacion_Academica_Actual::where('idUsuario', $userId)->where('estatus', 1)->first();
            $epData = Estudio_Prev::where('idUsuario', $userId)->where('estatus', 1)->get();
            $crData = Capacitacion_Recibida::where('idUsuario', $userId)->where('estatus', 1)->get();
            $ciData = Capacitacion_Impartida::where('idUsuario', $userId)->where('estatus', 1)->get();
            return $this->render('admin/form-edit.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData,
              'family' => $familyData,
              'children' => $childrenData,
              'eppu' => $eppuData,
              'position' => $positionData,
              'aiu' => $aiuData,
              'language' => $languageData,
              'faa' => $faaData,
              'ep' => $epData,
              'cr' => $crData,
              'ci' => $ciData
            ]);
          } else {
            return $this->render('admin/form.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username']
            ]);
          }
        }

        public function postCreate() {
          $userExist = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->first();
          if(strlen($userExist) <= 0) {
            $user = new Usuario();
            $user->idRol = 2;
            $user->nombre_usuario = $this->clean($_POST['nombre_usuario']);
            $user->noNomina_usuario = $_POST['noNomina_usuario'];
            $user->ciudadEstadoNac_usuario = $this->clean($_POST['ciudadEstadoNac_usuario']);
            $user->fechaNacimiento_usuario = $_POST['fechaNacimiento_usuario'];
            $user->sexo_usuario = $this->clean($_POST['sexo_usuario']);
            $user->curp_usuario = $this->clean($_POST['curp_usuario']);
            $user->noIMSS_usuario = $_POST['noIMSS_usuario'];
            $user->rfc_usuario = $this->clean($_POST['rfc_usuario']);
            $user->calle_usuario = $this->clean($_POST['calle_usuario']);
            $user->noExt_usuario = $this->clean($_POST['noExt_usuario']);
            $user->noInt_usuario = $this->clean($_POST['noInt_usuario']);
            $user->colonia_usuario = $this->clean($_POST['colonia_usuario']);
            $user->cp_usuario = $_POST['cp_usuario'];
            $user->estado_usuario = $this->clean($_POST['estado_usuario']);
            $user->municipio_usuario = $this->clean($_POST['municipio_usuario']);
            $user->telefono_usuario = $_POST['telefono_usuario'];
            $user->celular_usuario = $_POST['celular_usuario'];
            $user->correoUniva_usuario = $_POST['correoUniva_usuario'];
            $user->correoPersonal_usuario = $_POST['correoPersonal_usuario'];
            $user->save();

            $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->first();

            if($userData) {
              $userId = $userData->idUsuario;
              $rf = new RelacionFamiliar();
              $rf->idUsuario = $userId;
              $rf->estadoCivil_rf = $this->clean($_POST['estadoCivil_rf']);
              $rf->nombreConyuge_rf = $this->clean($_POST['nombreConyuge_rf']);
              $rf->nombreMadre_rf = $this->clean($_POST['nombreMadre_rf']);
              $rf->nombrePadre_rf = $this->clean($_POST['nombrePadre_rf']);
              if (!empty($_POST['fechaMatrimonio_rf'])) {
                $rf->fechaMatrimonio_rf = $_POST['fechaMatrimonio_rf'];
              }
              if (!empty($_POST['fechaNacConyuge_rf'])) {
                $rf->fechaNacConyuge_rf = $_POST['fechaNacConyuge_rf'];
              }
              $rf->save();

              for ($a = 1; $a < 11; $a++) {
                if (!empty($_POST['nombre_hijo' . $a])) {
                  $hijo = new Hijo();
                  $hijo->idUsuario = $userId;
                  $hijo->nombre_hijo = $this->clean($_POST['nombre_hijo' . $a]);
                  if (!empty($_POST['fechaNac_hijo' . $a])) {
                    $hijo->fechaNac_hijo = $_POST['fechaNac_hijo' . $a];
                  }
                  $hijo->gradoEscolar_hijo = $this->clean($_POST['gradoEscolar_hijo' . $a]);
                  $hijo->estatus = 1;
                  $hijo->save();
                }
              }

              for ($b = 1; $b < 16; $b++) {
                if (!empty($_POST['empresa_eppu' . $b])) {
                  $eppu = new Exp_Pro_Pre_Uni();
                  $eppu->idUsuario = $userId;
                  $eppu->empresa_eppu = $this->clean($_POST['empresa_eppu' . $b]);
                  $eppu->puesto_eppu = $this->clean($_POST['puesto_eppu' . $b]);
                  if (!empty($_POST['noAnios_eppu' . $b])) {
                    $eppu->noAnios_eppu = $_POST['noAnios_eppu' . $b];
                  } else {
                    $eppu->noAnios_eppu = 0;
                  }
                  if (!empty($_POST['noMeses_eppu' . $b])) {
                    $eppu->noMeses_eppu = $_POST['noMeses_eppu' . $b];
                  } else {
                    $eppu->noMeses_eppu = 0;
                  }
                  $eppu->nombrePuestoJefe_eppu = $this->clean($_POST['nombrePuestoJefe_eppu' . $b]);
                  $eppu->telefono_eppu = $_POST['telefono_eppu' . $b];
                  $eppu->estatus = 1;
                  $eppu->save();
                }
              }

              for ($c = 1; $c < 16; $c++) {
                if (!empty($_POST['cargo_cu' . $c])) {
                  $cu = new Cargo_Univa();
                  $cu->idUsuario = $userId;
                  $cu->cargo_cu = $this->clean($_POST['cargo_cu' . $c]);
                  $cu->departamento_cu = $this->clean($_POST['departamento_cu' . $c]);
                  if (!empty($_POST['anios_cu' . $c])) {
                    $cu->anios_cu = $_POST['anios_cu' . $c];
                  } else {
                    $cu->anios_cu = 0;
                  }
                  if (!empty($_POST['meses_cu' . $c])) {
                    $cu->meses_cu = $_POST['meses_cu' . $c];
                  } else {
                    $cu->meses_cu = 0;
                  }
                  $cu->nombrePuestoJefe_cu = $this->clean($_POST['nombrePuestoJefe_cu' . $c]);
                  $cu->periodo_cu = $this->clean($_POST['periodo_cu' . $c]);
                  $cu->estatus = 1;
                  $cu->save();
                }
              }

              for ($d = 1; $d < 6; $d++) {
                if (!empty($_POST['departamento_aiu' . $d])) {
                  $aiu = new Area_Interes_Univa();
                  $aiu->idUsuario = $userId;
                  $aiu->departamento_aiu = $this->clean($_POST['departamento_aiu' . $d]);
                  $aiu->cargo_aiu = $this->clean($_POST['cargo_aiu' . $d]);
                  $aiu->motivo_aiu = $this->clean($_POST['motivo_aiu' . $d]);
                  $aiu->estatus = 1;
                  $aiu->save();
                }
              }

              for ($e = 1; $e < 6; $e++) {
                if(!empty($_POST['idioma_i' . $e])) {
                  $idioma = new Idioma();
                  $idioma->idUsuario = $userId;
                  $idioma->idioma_i = $this->clean($_POST['idioma_i' . $e]);
                  if(!empty($_POST['hablado_i' . $e])) {
                    $idioma->hablado_i = $_POST['hablado_i' . $e];
                  } else {
                    $idioma->hablado_i = 0;
                  }
                  if(!empty($_POST['escrito_i' . $e])) {
                    $idioma->escrito_i = $_POST['escrito_i' . $e];
                  } else {
                    $idioma->escrito_i = 0;
                  }
                  if(!empty($_POST['leido_i' . $e])) {
                    $idioma->leido_i = $_POST['leido_i' . $e];
                  } else {
                    $idioma->leido_i = 0;
                  }
                  $idioma->estatus = 1;
                  $idioma->save();
                }
              }

              if(!empty($_POST['estudio_faa'])) {
                $faa = new Formacion_Academica_Actual();
                $faa->idUsuario = $userId;
                $faa->estudio_faa = $this->clean($_POST['estudio_faa']);
                $faa->escuela_faa = $this->clean($_POST['escuela_faa']);
                if(!empty($_POST['horario_faaLunes'])) {
                  $faa->lunes_faa = $_POST['horario_faaLunes'];
                }
                if(!empty($_POST['horario_faaMartes'])) {
                  $faa->martes_faa = $_POST['horario_faaMartes'];
                }
                if(!empty($_POST['horario_faaMiercoles'])) {
                  $faa->miercoles_faa = $_POST['horario_faaMiercoles'];
                }
                if(!empty($_POST['horario_faaJueves'])) {
                  $faa->jueves_faa = $_POST['horario_faaJueves'];
                }
                if(!empty($_POST['horario_faaViernes'])) {
                  $faa->viernes_faa = $_POST['horario_faaViernes'];
                }
                if(!empty($_POST['horario_faaSabado'])) {
                  $faa->sabado_faa = $_POST['horario_faaSabado'];
                }
                if(!empty($_POST['fechaInicio_faa'])) {
                  $faa->fechaInicio_faa = $_POST['fechaInicio_faa'];
                }
                if(!empty($_POST['fechaFin_faa'])) {
                  $faa->fechaFin_faa = $_POST['fechaFin_faa'];
                }
                $faa->estatus = 1;
                $faa->save();
              }

              for ($f = 1; $f < 8; $f++) {
                if(!empty($_POST['institucion_ep' . $f])) {
                  $ep = new Estudio_Prev();
                  $ep->idUsuario = $userId;
                  $ep->grado_ep = $this->clean($_POST['grado_ep' . $f]);
                  $ep->institucion_ep = $this->clean($_POST['institucion_ep' . $f]);
                  $ep->nombre_ep = $this->clean($_POST['nombre_ep' . $f]);
                  $ep->periodo_ep = $this->clean($_POST['periodo_ep' . $f]);
                  $ep->certificado_ep = $this->clean($_POST['certificado_ep' . $f]);
                  $ep->titulo_ep = $this->clean($_POST['titulo_ep' . $f]);
                  $ep->cedula_ep = $this->clean($_POST['cedula_ep' . $f]);
                  $ep->estatus = 1;
                  $ep->save();
                }
              }

              for ($g = 1; $g < 16; $g++) {
                if(!empty($_POST['nombre_cr' . $g])) {
                  $cr = new Capacitacion_Recibida();
                  $cr->idUsuario = $userId;
                  $cr->nombre_cr = $this->clean($_POST['nombre_cr' . $g]);
                  if(!empty($_POST['fechaCapacitacion_cr' . $g])) {
                    $cr->fechaCapacitacion_cr = $_POST['fechaCapacitacion_cr' . $g];
                  }
                  if(!empty($_POST['noHoras_cr' . $g])) {
                    $cr->noHoras_cr = $_POST['noHoras_cr' . $g];
                  } else {
                    $cr->noHoras_cr = 0;
                  }
                  $cr->institucion_cr = $this->clean($_POST['institucion_cr' . $g]);
                  $cr->certificado_cr = $this->clean($_POST['certificado_cr' . $g]);
                  $cr->estatus = 1;
                  $cr->save();
                }
              }

              for ($h = 1; $h < 16; $h++) {
                if(!empty($_POST['nombre_ci' . $h])) {
                  $ci = new Capacitacion_Impartida();
                  $ci->idUsuario = $userId;
                  $ci->nombre_ci = $this->clean($_POST['nombre_ci' . $h]);
                  if(!empty($_POST['fechaCapacitacion_ci' . $h])) {
                    $ci->fechaCapacitacion_ci = $_POST['fechaCapacitacion_ci' . $h];
                  }
                  if(!empty($_POST['noHoras_ci' . $h])) {
                    $ci->noHoras_ci = $_POST['noHoras_ci' . $h];
                  } else {
                    $ci->noHoras_ci = 0;
                  }
                  $ci->institucion_ci = $this->clean($_POST['institucion_ci' . $h]);
                  $ci->certificado_ci = $this->clean($_POST['certificado_ci' . $h]);
                  $ci->estatus = 1;
                  $ci->save();
                }
              }

              return $this->render('partials/success.twig', [
                'name' => $_SESSION['name'],
                'username' => $_SESSION['username']
              ]);
            }
          }
        }

        public function postEdit() {
          $userExist = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->first();
          $userId = $userExist->idUsuario;
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->update([
            'nombre_usuario' => $this->clean($_POST['nombre_usuario']),
            'noNomina_usuario' => $_POST['noNomina_usuario'],
            'ciudadEstadoNac_usuario' => $this->clean($_POST['ciudadEstadoNac_usuario']),
            'fechaNacimiento_usuario' => $_POST['fechaNacimiento_usuario'],
            'sexo_usuario' => $this->clean($_POST['sexo_usuario']),
            'curp_usuario' => $this->clean($_POST['curp_usuario']),
            'noIMSS_usuario' => $_POST['noIMSS_usuario'],
            'rfc_usuario' => $this->clean($_POST['rfc_usuario']),
            'calle_usuario' => $this->clean($_POST['calle_usuario']),
            'noExt_usuario' => $this->clean($_POST['noExt_usuario']),
            'noInt_usuario' => $this->clean($_POST['noInt_usuario']),
            'colonia_usuario' => $this->clean($_POST['colonia_usuario']),
            'cp_usuario' => $_POST['cp_usuario'],
            'estado_usuario' => $this->clean($_POST['estado_usuario']),
            'municipio_usuario' => $this->clean($_POST['municipio_usuario']),
            'telefono_usuario' => $_POST['telefono_usuario'],
            'celular_usuario' => $_POST['celular_usuario'],
            'correoUniva_usuario' => $_POST['correoUniva_usuario'],
            'correoPersonal_usuario' => $_POST['correoPersonal_usuario']
          ]);

          if(!empty($_POST['fechaMatrimonio_rf']) && !empty($_POST['fechaNacConyuge_rf'])) {
            $rf = RelacionFamiliar::where('idUsuario', $userId)->update([
            'estadoCivil_rf' => $this->clean($_POST['estadoCivil_rf']),
            'fechaMatrimonio_rf' => $_POST['fechaMatrimonio_rf'],
            'nombreConyuge_rf' => $this->clean($_POST['nombreConyuge_rf']),
            'fechaNacConyuge_rf' => $_POST['fechaNacConyuge_rf'],
            'nombreMadre_rf' => $this->clean($_POST['nombreMadre_rf']),
            'nombrePadre_rf' => $this->clean($_POST['nombrePadre_rf'])
            ]);
          } else {
            $rf = RelacionFamiliar::where('idUsuario', $userId)->update([
            'estadoCivil_rf' => $this->clean($_POST['estadoCivil_rf']),
            'fechaMatrimonio_rf' => null,
            'nombreConyuge_rf' => $this->clean($_POST['nombreConyuge_rf']),
            'fechaNacConyuge_rf' => null,
            'nombreMadre_rf' => $this->clean($_POST['nombreMadre_rf']),
            'nombrePadre_rf' => $this->clean($_POST['nombrePadre_rf'])
            ]);
          }

          /* Hijos Start */
          $hijo = Hijo::where('idUsuario', $userId)->get();
          for ($ah = 0; $ah < sizeof($hijo); $ah++) {
            $hijoEstatus = Hijo::updateOrCreate(
              ["idHijo" => $hijo[$ah]->idHijo, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $a1 = 0;
          for ($a = 1; $a < 11; $a++) {
            if (!empty($_POST['nombre_hijo' . $a])) {
              if($a1 < sizeof($hijo)) { $idHijo = $hijo[$a1]->idHijo; }
              else { $idHijo = 0; }
              if (!empty($_POST['fechaNac_hijo' . $a])) {
                $hijoData = Hijo::updateOrCreate(
                ["idHijo" => $idHijo, 'idUsuario' => $userId],
                [
                  'nombre_hijo' => $this->clean($_POST['nombre_hijo' . $a]),
                  'fechaNac_hijo' => $_POST['fechaNac_hijo' . $a],
                  'gradoEscolar_hijo' => $this->clean($_POST['gradoEscolar_hijo' . $a]),
                  'estatus' => 1
                ]);
              } else {
                $hijoData = Hijo::updateOrCreate(
                ["idHijo" => $idHijo, 'idUsuario' => $userId],
                [
                  'nombre_hijo' => $this->clean($_POST['nombre_hijo' . $a]),
                  'fechaNac_hijo' => null,
                  'gradoEscolar_hijo' => $this->clean($_POST['gradoEscolar_hijo' . $a]),
                  'estatus' => 1
                ]);
              }
              $a1++;
            }
          }
          /* Hijos End */

          /* Experiencia Start */
          $eppu = Exp_Pro_Pre_Uni::where('idUsuario', $userId)->get();
          for ($bh = 0; $bh < sizeof($eppu); $bh++) {
            $eppuEstatus = Exp_Pro_Pre_Uni::updateOrCreate(
              ["idEPPU" => $eppu[$bh]->idEPPU, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $b1 = 0;
          for ($b = 1; $b < 16; $b++) {
            if (!empty($_POST['empresa_eppu' . $b])) {
              if($b1 < sizeof($eppu)) { $idEPPU = $eppu[$b1]->idEPPU; }
              else { $idEPPU = 0; }
              $anios = 0;
              $meses = 0;
              if (!empty($_POST['noAnios_eppu' . $b])) {
                $anios = $_POST['noAnios_eppu' . $b];
              }
              if (!empty($_POST['noMeses_eppu' . $b])) {
                $meses = $_POST['noMeses_eppu' . $b];
              }
              $eppuData = Exp_Pro_Pre_Uni::updateOrCreate(
                ["idEPPU" => $idEPPU, 'idUsuario' => $userId],
                [
                  'empresa_eppu' => $this->clean($_POST['empresa_eppu' . $b]),
                  'puesto_eppu' => $this->clean($_POST['puesto_eppu' . $b]),
                  'noAnios_eppu' => $anios,
                  'noMeses_eppu' => $meses,
                  'nombrePuestoJefe_eppu' => $this->clean($_POST['nombrePuestoJefe_eppu' . $b]),
                  'telefono_eppu' => $_POST['telefono_eppu' . $b],
                  'estatus' => 1
                ]
              );
              $b1++;
            }
          }
          /* Experiencia End */

          /* Cargos Start */
          $cu = Cargo_Univa::where('idUsuario', $userId)->get();
          for ($ch = 0; $ch < sizeof($cu); $ch++) {
            $cuEstatus = Cargo_Univa::updateOrCreate(
              ["idCU" => $cu[$ch]->idCU, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $c1 = 0;
          for ($c = 1; $c < 16; $c++) {
            if (!empty($_POST['cargo_cu' . $c])) {
              if($c1 < sizeof($cu)) { $idCU = $cu[$c1]->idCU; }
              else { $idCU = 0; }
              $anios = 0;
              $meses = 0;
              if (!empty($_POST['anios_cu' . $c])) {
                $anios = $_POST['anios_cu' . $c];
              }
              if (!empty($_POST['meses_cu' . $c])) {
                $meses = $_POST['meses_cu' . $c];
              }
              $cuData = Cargo_Univa::updateOrCreate(
                ["idCU" => $idCU, 'idUsuario' => $userId],
                [
                  'cargo_cu' => $this->clean($_POST['cargo_cu' . $c]),
                  'departamento_cu' => $this->clean($_POST['departamento_cu' . $c]),
                  'anios_cu' => $anios,
                  'meses_cu' => $meses,
                  'nombrePuestoJefe_cu' => $this->clean($_POST['nombrePuestoJefe_cu' . $c]),
                  'periodo_cu' => $this->clean($_POST['periodo_cu' . $c]),
                  'estatus' => 1
                ]
              );
              $c1++;
            }
          }
          /* Cargos End */

          /* Areas Start */
          $aiu = Area_Interes_Univa::where('idUsuario', $userId)->get();
          for ($dh = 0; $dh < sizeof($aiu); $dh++) {
            $aiuEstatus = Area_Interes_Univa::updateOrCreate(
              ["idAIU" => $aiu[$dh]->idAIU, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $d1 = 0;
          for ($d = 1; $d < 6; $d++) {
            if (!empty($_POST['departamento_aiu' . $d])) {
              if($d1 < sizeof($aiu)) { $idAIU = $aiu[$d1]->idAIU; }
              else { $idAIU = 0; }
              $aiuData = Area_Interes_Univa::updateOrCreate(
                ["idAIU" => $idAIU, 'idUsuario' => $userId],
                [
                  'departamento_aiu' => $this->clean($_POST['departamento_aiu' . $d]),
                  'cargo_aiu' => $this->clean($_POST['cargo_aiu' . $d]),
                  'motivo_aiu' => $this->clean($_POST['motivo_aiu' . $d]),
                  'estatus' => 1
                ]
              );
              $d1++;
            }
          }
          /* Areas End */

          /* Idiomas Start */
          $idioma = Idioma::where('idUsuario', $userId)->get();
          for ($eh = 0; $eh < sizeof($idioma); $eh++) {
            $idiomaEstatus = Idioma::updateOrCreate(
              ["idIdioma" => $idioma[$eh]->idIdioma, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $e1 = 0;
          for ($e = 1; $e < 6; $e++) {
            if(!empty($_POST['idioma_i' . $e])) {
              if($e1 < sizeof($idioma)) { $idIdioma = $idioma[$e1]->idIdioma; }
              else { $idIdioma = 0; }
              $hablado = 0;
              $escrito = 0;
              $leido = 0;
              if(!empty($_POST['hablado_i' . $e])) {
                $hablado = $_POST['hablado_i' . $e];
              }
              if(!empty($_POST['escrito_i' . $e])) {
                $escrito = $_POST['escrito_i' . $e];
              }
              if(!empty($_POST['leido_i' . $e])) {
                $leido = $_POST['leido_i' . $e];
              }
              $idiomaData = Idioma::updateOrCreate(
                ["idIdioma" => $idIdioma, 'idUsuario' => $userId],
                [
                  'idioma_i' => $this->clean($_POST['idioma_i' . $e]),
                  'hablado_i' => $hablado,
                  'escrito_i' => $escrito,
                  'leido_i' => $leido,
                  'estatus' => 1
                ]
              );
              $e1++;
            }
          }
          /* Idiomas End */

          /* Formacion Start */
          $formacion = Formacion_Academica_Actual::where('idUsuario', $userId)->get();
          for ($foh = 0; $foh < sizeof($formacion); $foh++) {
            $formacionEstatus = Formacion_Academica_Actual::updateOrCreate(
              ["idFAA" => $formacion[$foh]->idFAA, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $faa = Formacion_Academica_Actual::where('idUsuario', $userId)->get();
          if(!empty($_POST['estudio_faa'])) {
            if(sizeof($faa) > 0) { $idFAA = $faa[0]->idFAA; }
            else { $idFAA = 0; }
            if(empty($_POST['horario_faaLunes'])) {
              $_POST['horario_faaLunes'] = null;
            }
            if(empty($_POST['horario_faaMartes'])) {
              $_POST['horario_faaMartes'] = null;
            }
            if(empty($_POST['horario_faaMiercoles'])) {
              $_POST['horario_faaMiercoles'] = null;
            }
            if(empty($_POST['horario_faaJueves'])) {
              $_POST['horario_faaJueves'] = null;
            }
            if(empty($_POST['horario_faaViernes'])) {
              $_POST['horario_faaViernes'] = null;
            }
            if(empty($_POST['horario_faaSabado'])) {
              $_POST['horario_faaSabado'] = null;
            }
            if(empty($_POST['fechaInicio_faa'])) {
              $_POST['fechaInicio_faa'] = null;
            }
            if(empty($_POST['fechaFin_faa'])) {
              $_POST['fechaFin_faa'] = null;
            }
            $faaData = Formacion_Academica_Actual::updateOrCreate(
              ["idFAA" => $idFAA, "idUsuario" => $userId],
              [
                'estudio_faa' => $this->clean($_POST['estudio_faa']),
                'escuela_faa' => $this->clean($_POST['escuela_faa']),
                'lunes_faa' => $_POST['horario_faaLunes'],
                'martes_faa' => $_POST['horario_faaMartes'],
                'miercoles_faa' => $_POST['horario_faaMiercoles'],
                'jueves_faa' => $_POST['horario_faaJueves'],
                'viernes_faa' => $_POST['horario_faaViernes'],
                'sabado_faa' => $_POST['horario_faaSabado'],
                'fechaInicio_faa' => $_POST['fechaInicio_faa'],
                'fechaFin_faa' => $_POST['fechaFin_faa'],
                'estatus' => 1
              ]);
          }
          /* Formacion End */

          /* Estudios Start */
          $ep = Estudio_Prev::where('idUsuario', $userId)->get();
          for ($fh = 0; $fh < sizeof($ep); $fh++) {
            $epEstatus = Estudio_Prev::updateOrCreate(
              ["idEP" => $ep[$fh]->idEP, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $f1 = 0;
          for ($f = 1; $f < 8; $f++) {
            if(!empty($_POST['institucion_ep' . $f])) {
              if($f1 < sizeof($ep)) { $idEP = $ep[$f1]->idEP; }
              else { $idEP = 0; }
              $epData = Estudio_Prev::updateOrCreate(
                ["idEP" => $idEP, 'idUsuario' => $userId],
                [
                  'grado_ep' => $this->clean($_POST['grado_ep' . $f]),
                  'institucion_ep' => $this->clean($_POST['institucion_ep' . $f]),
                  'nombre_ep' => $this->clean($_POST['nombre_ep' . $f]),
                  'periodo_ep' => $this->clean($_POST['periodo_ep' . $f]),
                  'certificado_ep' => $this->clean($_POST['certificado_ep' . $f]),
                  'titulo_ep' => $this->clean($_POST['titulo_ep' . $f]),
                  'cedula_ep' => $this->clean($_POST['cedula_ep' . $f]),
                  'estatus' => 1
                ]
              );
              $f1++;
            }
          }
          /* Estudios End */

          /* Recibidos Start */
          $cr = Capacitacion_Recibida::where('idUsuario', $userId)->get();
          for ($gh = 0; $gh < sizeof($cr); $gh++) {
            $crEstatus = Capacitacion_Recibida::updateOrCreate(
              ["idCR" => $cr[$gh]->idCR, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $g1 = 0;
          for ($g = 1; $g < 16; $g++) {
            if(!empty($_POST['nombre_cr' . $g])) {
              if($g1 < sizeof($cr)) { $idCR = $cr[$g1]->idCR; }
              else { $idCR = 0; }
              $horas = 0;
              if(!empty($_POST['noHoras_cr' . $g])) {
                $horas = $_POST['noHoras_cr' . $g];
              }
              if(!empty($_POST['fechaCapacitacion_cr' . $g])) {
                $crData = Capacitacion_Recibida::updateOrCreate(
                ["idCR" => $idCR, 'idUsuario' => $userId],
                [
                  'nombre_cr' => $this->clean($_POST['nombre_cr' . $g]),
                  'fechaCapacitacion_cr' => $_POST['fechaCapacitacion_cr' . $g],
                  'noHoras_cr' => $horas,
                  'institucion_cr' => $this->clean($_POST['institucion_cr' . $g]),
                  'certificado_cr' => $this->clean($_POST['certificado_cr' . $g]),
                  'estatus' => 1
                ]);
              } else {
                $crData = Capacitacion_Recibida::updateOrCreate(
                ["idCR" => $idCR, 'idUsuario' => $userId],
                [
                  'nombre_cr' => $this->clean($_POST['nombre_cr' . $g]),
                  'fechaCapacitacion_cr' => null,
                  'noHoras_cr' => $horas,
                  'institucion_cr' => $this->clean($_POST['institucion_cr' . $g]),
                  'certificado_cr' => $this->clean($_POST['certificado_cr' . $g]),
                  'estatus' => 1
                ]
              );
              }
              $g1++;
            }
          }
          /* Recibidos End */

          /* Impartidos Start */
          $ci = Capacitacion_Impartida::where('idUsuario', $userId)->get();
          for ($hh = 0; $hh < sizeof($ci); $hh++) {
            $ciEstatus = Capacitacion_Impartida::updateOrCreate(
              ["idCI" => $ci[$hh]->idCI, 'idUsuario' => $userId],
              [
                'estatus' => 0
              ]
            );
          }

          $h1 = 0;
          for ($h = 1; $h < 16; $h++) {
            if(!empty($_POST['nombre_ci' . $h])) {
              if($h1 < sizeof($ci)) { $idCI = $ci[$h1]->idCI; }
              else { $idCI = 0; }
              $horas = 0;
              if(!empty($_POST['noHoras_ci' . $h])) {
                $horas = $_POST['noHoras_ci' . $h];
              }
              if(!empty($_POST['fechaCapacitacion_ci' . $h])) {
                $ciData = Capacitacion_Impartida::updateOrCreate(
                ["idCI" => $idCI, 'idUsuario' => $userId],
                [
                  'nombre_ci' => $this->clean($_POST['nombre_ci' . $h]),
                  'fechaCapacitacion_ci' => $_POST['fechaCapacitacion_ci' . $h],
                  'noHoras_ci' => $horas,
                  'institucion_ci' => $this->clean($_POST['institucion_ci' . $h]),
                  'certificado_ci' => $this->clean($_POST['certificado_ci' . $h]),
                  'estatus' => 1
                ]);
              } else {
                $ciData = Capacitacion_Impartida::updateOrCreate(
                ["idCI" => $idCI, 'idUsuario' => $userId],
                [
                  'nombre_ci' => $this->clean($_POST['nombre_ci' . $h]),
                  'fechaCapacitacion_ci' => null,
                  'noHoras_ci' => $horas,
                  'institucion_ci' => $this->clean($_POST['institucion_ci' . $h]),
                  'certificado_ci' => $this->clean($_POST['certificado_ci' . $h]),
                  'estatus' => 1
                ]);
              }
              $h1++;
            }
          }
          /* Impartidos End */

          return $this->render('partials/success.twig', [
            'name' => $_SESSION['name'],
            'username' => $_SESSION['username']
          ]);
        }

        public function getDocuments() {
          $userData = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->first();
          if($userData) {
            $userId = $userData->idUsuario;
            $upload = Upload::where('idUsuario', $userId)->where('estatus', 1)->get();
            return $this->render('admin/documents.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'user' => $userData,
              'up' => $upload
            ]);
          } else {
            return $this->render('admin/profile.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username']
            ]);
          }
        }

        public function postDocuments() {
          $user = Usuario::where('correoUniva_usuario', $_SESSION['username'] . "@univa.mx")->first();
          $userId = $user->idUsuario;
          $success = 0;
          $errors = [];
          $er = 0;

          /* Acta Nacimiento Start */
          $upload1 = Upload::where('idUsuario', $userId)->where('tipo', 1)->get();

          $name1 = $_FILES['actaNacimiento']['name'];
          $size1 = $_FILES['actaNacimiento']['size'];
          $type1 = $_FILES['actaNacimiento']['type'];
          $tmp1 = $_FILES['actaNacimiento']['tmp_name'];

          $fileExt1 = explode('.', $name1);
          $fileActExt1 = strtolower(end($fileExt1));
          $allowed1 = array('pdf');

          if(isset($name1) && !empty($name1)) {
            if(in_array($fileActExt1, $allowed1) && $size1 < 900000) {
              $name1 = 'actaNacimiento_' . $user->noNomina_usuario . '.' . $fileActExt1;
              $destination1 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
              $target1 = $destination1 . basename($name1);
              if(move_uploaded_file($tmp1, $target1)) {
                if(sizeof($upload1) > 0) { $idUpload1 = $upload1[0]->idUpload; }
                else { $idUpload1 = 0; }
                $upload1Data = Upload::updateOrCreate(
                  ["idUpload" => $idUpload1, "idUsuario" => $userId,
                  "archivo" => $name1],
                  [
                    'archivo' => $name1,
                    'tipo' => 1,
                    'estatus' => 1,
                  ]);
              }
            } else {
                $errors[$er] = "Error al cargar el archivo: Acta de Nacimiento -> " . $name1;
                $success = 1;
                $er++;
              }
          }
          /* Acta Nacimiento End */

          /* INE Start */
          $upload2 = Upload::where('idUsuario', $userId)->where('tipo', 2)->get();

          $name2 = $_FILES['ine']['name'];
          $size2 = $_FILES['ine']['size'];
          $type2 = $_FILES['ine']['type'];
          $tmp2 = $_FILES['ine']['tmp_name'];

          $fileExt2 = explode('.', $name2);
          $fileActExt2 = strtolower(end($fileExt2));
          $allowed2 = array('pdf');

          if(isset($name2) && !empty($name2)) {
            if(in_array($fileActExt2, $allowed2) && $size2 < 900000) {
              $name2 = 'ine_' . $user->noNomina_usuario . '.' . $fileActExt2;
              $destination2 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
              $target2 = $destination2 . basename($name2);
              if(move_uploaded_file($tmp2, $target2)) {
                if(sizeof($upload2) > 0) { $idUpload2 = $upload2[0]->idUpload; }
                else { $idUpload2 = 0; }
                $upload2Data = Upload::updateOrCreate(
                  ["idUpload" => $idUpload2, "idUsuario" => $userId,
                  "archivo" => $name2],
                  [
                    'archivo' => $name2,
                    'tipo' => 2,
                    'estatus' => 1,
                  ]);
              }
            } else {
                $errors[$er] = "Error al cargar el archivo: INE / IFE -> " . $name2;
                $success = 1;
                $er++;
              }
          }
          /* INE End */

          /* Comprobante Start */
          $upload3 = Upload::where('idUsuario', $userId)->where('tipo', 3)->get();
          $name3 = $_FILES['comprobante']['name'];
          $size3 = $_FILES['comprobante']['size'];
          $type3 = $_FILES['comprobante']['type'];
          $tmp3 = $_FILES['comprobante']['tmp_name'];

          $fileExt3 = explode('.', $name3);
          $fileActExt3 = strtolower(end($fileExt3));
          $allowed3 = array('pdf');

          if (isset($name3) && !empty($name3)) {
            if(in_array($fileActExt3, $allowed3) && $size3 < 900000) {
              $name3 = 'comprobante_' . $user->noNomina_usuario . '.' . $fileActExt3;
              $destination3 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
              $target3 = $destination3 . basename($name3);
              if(move_uploaded_file($tmp3, $target3))
              {
                if(sizeof($upload3) > 0) { $idUpload3 = $upload3[0]->idUpload; }
                else { $idUpload3 = 0; }
                $upload3Data = Upload::updateOrCreate(
                  ["idUpload" => $idUpload3, "idUsuario" => $userId,
                  "archivo" => $name3],
                  [
                    'archivo' => $name3,
                    'tipo' => 3,
                    'estatus' => 1,
                  ]);
              }
            } else {
              $errors[$er] = "Error al cargar el archivo: Comprobante de Domicilio -> " . $name3;
              $success = 1;
              $er++;
            }
          }
          /* Comprobante End */

          /* RFC Start */
          $upload4 = Upload::where('idUsuario', $userId)->where('tipo', 4)->get();

          $name4 = $_FILES['rfc']['name'];
          $size4 = $_FILES['rfc']['size'];
          $type4 = $_FILES['rfc']['type'];
          $tmp4 = $_FILES['rfc']['tmp_name'];

          $fileExt4 = explode('.', $name4);
          $fileActExt4 = strtolower(end($fileExt4));
          $allowed4 = array('pdf');

          if (isset($name4) && !empty($name4)) {
            if(in_array($fileActExt4, $allowed4) && $size4 < 900000) {
              $name4 = 'rfc_' . $user->noNomina_usuario . '.' . $fileActExt4;
              $destination4 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
              $target4 = $destination4 . basename($name4);
              if(move_uploaded_file($tmp4, $target4))
              {
                if(sizeof($upload4) > 0) { $idUpload4 = $upload4[0]->idUpload; }
                else { $idUpload4 = 0; }
                $upload4Data = Upload::updateOrCreate(
                  ["idUpload" => $idUpload4, "idUsuario" => $userId,
                  "archivo" => $name4],
                  [
                    'archivo' => $name4,
                    'tipo' => 4,
                    'estatus' => 1,
                  ]);
              }
            } else {
              $errors[$er] = "Error al cargar el archivo: RFC -> " . $name4;
              $success = 1;
              $er++;
            }
          }
          /* RFC End */

          /* IMSS Start */
          $upload5 = Upload::where('idUsuario', $userId)->where('tipo', 5)->get();

          $name5 = $_FILES['imss']['name'];
          $size5 = $_FILES['imss']['size'];
          $type5 = $_FILES['imss']['type'];
          $tmp5 = $_FILES['imss']['tmp_name'];

          $fileExt5 = explode('.', $name5);
          $fileActExt5 = strtolower(end($fileExt5));
          $allowed5 = array('pdf');

          if (isset($name5) && !empty($name5)) {
            if(in_array($fileActExt5, $allowed5) && $size5 < 900000) {
              $name5 = 'imss_' . $user->noNomina_usuario . '.' . $fileActExt5;
              $destination5 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
              $target5 = $destination5 . basename($name5);
              if(move_uploaded_file($tmp5, $target5))
              {
                if(sizeof($upload5) > 0) { $idUpload5 = $upload5[0]->idUpload; }
                else { $idUpload5 = 0; }
                $upload5Data = Upload::updateOrCreate(
                  ["idUpload" => $idUpload5, "idUsuario" => $userId,
                  "archivo" => $name5],
                  [
                    'archivo' => $name5,
                    'tipo' => 5,
                    'estatus' => 1,
                  ]);
              }
            } else {
              $errors[$er] = "Error al cargar el archivo: IMSS -> " . $name5;
              $success = 1;
              $er++;
            }
          }
          /* IMSS End */

          /* CURP Start */
          $upload6 = Upload::where('idUsuario', $userId)->where('tipo', 6)->get();

          $name6 = $_FILES['curp']['name'];
          $size6 = $_FILES['curp']['size'];
          $type6 = $_FILES['curp']['type'];
          $tmp6 = $_FILES['curp']['tmp_name'];

          $fileExt6 = explode('.', $name6);
          $fileActExt6 = strtolower(end($fileExt6));
          $allowed6 = array('pdf');

          if (isset($name6) && !empty($name6)) {
            if(in_array($fileActExt6, $allowed6) && $size6 < 900000) {
              $name6 = 'curp_' . $user->noNomina_usuario . '.' . $fileActExt6;
              $destination6 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
              $target6 = $destination6 . basename($name6);
              if(move_uploaded_file($tmp6, $target6))
              {
                if(sizeof($upload6) > 0) { $idUpload6 = $upload6[0]->idUpload; }
                else { $idUpload6 = 0; }
                $upload6Data = Upload::updateOrCreate(
                  ["idUpload" => $idUpload6, "idUsuario" => $userId,
                  "archivo" => $name6],
                  [
                    'archivo' => $name6,
                    'tipo' => 6,
                    'estatus' => 1,
                  ]);
              }
            } else {
              $errors[$er] = "Error al cargar el archivo: CURP -> " . $name6;
              $success = 1;
              $er++;
            }
          }
          /* CURP End */

          /* Acta Matrimonio Start */
          $upload7 = Upload::where('idUsuario', $userId)->where('tipo', 7)->get();

          $name7 = $_FILES['actaMatrimonio']['name'];
          $size7 = $_FILES['actaMatrimonio']['size'];
          $type7 = $_FILES['actaMatrimonio']['type'];
          $tmp7 = $_FILES['actaMatrimonio']['tmp_name'];

          $fileExt7 = explode('.', $name7);
          $fileActExt7 = strtolower(end($fileExt7));
          $allowed7 = array('pdf');

          if(isset($name7) && !empty($name7)) {
            if(in_array($fileActExt7, $allowed7) && $size7 < 900000) {
              $name7 = 'actaMatrimonio_' . $user->noNomina_usuario . '.' . $fileActExt7;
              $destination7 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
              $target7 = $destination7 . basename($name7);
              if(move_uploaded_file($tmp7, $target7))
              {
                if(sizeof($upload7) > 0) { $idUpload7 = $upload7[0]->idUpload; }
                else { $idUpload7 = 0; }
                $upload7Data = Upload::updateOrCreate(
                  ["idUpload" => $idUpload7, "idUsuario" => $userId,
                  "archivo" => $name7],
                  [
                    'archivo' => $name7,
                    'tipo' => 7,
                    'estatus' => 1,
                  ]);
              }
            } else {
              $errors[$er] = "Error al cargar el archivo: Acta de Matrimonio -> " . $name7;
              $success = 1;
              $er++;
            }
          }
          /* Acta Matrimonio End */

          /* Acta Hijos Start */
          $upload8 = Upload::where('idUsuario', $userId)->where('tipo', 8)->get();

          $name8 = $_FILES['actaHijos']['name'];
          $size8 = $_FILES['actaHijos']['size'];
          $type8 = $_FILES['actaHijos']['type'];
          $tmp8 = $_FILES['actaHijos']['tmp_name'];

          $fileExt8 = explode('.', $name8);
          $fileActExt8 = strtolower(end($fileExt8));
          $allowed8 = array('pdf');

          if(isset($name8) && !empty($name8)) {
            if(in_array($fileActExt8, $allowed8) && $size8 < 900000) {
              $name8 = 'actaHijos_' . $user->noNomina_usuario . '.' . $fileActExt8;
              $destination8 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
              $target8 = $destination8 . basename($name8);
              if(move_uploaded_file($tmp8, $target8))
              {
                if(sizeof($upload8) > 0) { $idUpload8 = $upload8[0]->idUpload; }
                else { $idUpload8 = 0; }
                $upload8Data = Upload::updateOrCreate(
                  ["idUpload" => $idUpload8, "idUsuario" => $userId,
                  "archivo" => $name8],
                  [
                    'archivo' => $name8,
                    'tipo' => 8,
                    'estatus' => 1,
                  ]);
              }
            } else {
              $errors[$er] = "Error al cargar el archivo: Actas de Nacimiento de Hijos -> " . $name8;
              $success = 1;
              $er++;
            }
          }
          /* Acta Hijos End */

          /* Titulos Start */
          $upload9A = Upload::where('idUsuario', $userId)->where('tipo', 9)->get();

          $uploadFiles = sizeof($_FILES['titulos']['name']);
          $uploadedFiles = sizeof($upload9A);
          $add = $uploadFiles + $uploadedFiles;
          if($uploadFiles <= 7 && $uploadedFiles <= 7 && abs($add) <= 7) {
              foreach($_FILES['titulos']['name'] as $file => $name) {
                $name9 = $name;
                $size9 = $_FILES['titulos']['size'][$file];
                $type9 = $_FILES['titulos']['type'][$file];
                $tmp9 = $_FILES['titulos']['tmp_name'][$file];

                $fileExt9 = explode('.', $name9);
                $fileActExt9 = strtolower(end($fileExt9));
                $allowed9 = array('pdf');
                if (isset($name9) && !empty($name9)) {
                  if(in_array($fileActExt9, $allowed9) && $size9 < 900000) {
                    $destination9 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
                    $target9 = $destination9 . basename($name9);
                    if(move_uploaded_file($tmp9, $target9))
                    {
                      $upload9Data = Upload::updateOrCreate(
                        ["idUsuario" => $userId, "archivo" => $name9],
                        [
                          'archivo' => $name9,
                          'tipo' => 9,
                          'estatus' => 1,
                        ]);
                    }
                  } else {
                    $errors[$er] = "Error al cargar el archivo: Titulos -> " . $name9;
                    $success = 1;
                    $er++;
                  }
                }
              }
          } else {
            if ($add > 8) {
              $errors[$er] = "Limite excedido de archivos. Solo son permitidos guardar (7 títulos), favor de verificar cuantos archivos esta subiendo y cuantos tiene guardados en el sistema";
              $success = 1;
              $er++;
            }
          }
          /* Titulos End */

          /* Cedulas Start */
          $upload10A = Upload::where('idUsuario', $userId)->where('tipo', 10)->get();

          $uploadFilesCedulas = sizeof($_FILES['cedulas']['name']);
          $uploadedFilesCedulas = sizeof($upload10A);
          $add10 = $uploadFilesCedulas + $uploadedFilesCedulas;
          if($uploadFilesCedulas <= 7 && $uploadedFilesCedulas <= 7 && abs($add10) <= 7) {
              foreach($_FILES['cedulas']['name'] as $file => $name) {
                $name10 = $name;
                $size10 = $_FILES['cedulas']['size'][$file];
                $type10 = $_FILES['cedulas']['type'][$file];
                $tmp10 = $_FILES['cedulas']['tmp_name'][$file];

                $fileExt10 = explode('.', $name10);
                $fileActExt10 = strtolower(end($fileExt10));
                $allowed10 = array('pdf');
                if (isset($name10) && !empty($name10)) {
                  if(in_array($fileActExt10, $allowed10) && $size10 < 900000) {
                    $destination10 = getcwd().DIRECTORY_SEPARATOR . "/uploads/";
                    $target10 = $destination10 . basename($name10);
                    if(move_uploaded_file($tmp10, $target10)) {
                      $upload10Data = Upload::updateOrCreate(
                        ["idUsuario" => $userId, "archivo" => $name10],
                        [
                          'archivo' => $name10,
                          'tipo' => 10,
                          'estatus' => 1,
                        ]);
                    }
                  } else {
                    $errors[$er] = "Error al cargar el archivo: Cédulas -> " . $name10;
                    $success = 1;
                    $er++;
                  }
                }
              }
          } else {
            if ($add10 > 8) {
              $errors[$er] = "Limite excedido de archivos. Solo son permitidos guardar (7 cédulas), favor de verificar cuantos archivos esta subiendo y cuantos tiene guardados en el sistema";
              $success = 1;
              $er++;
            }
          }
          /* Cedulas End */

          if($success == 1) {
            return $this->render('partials/error.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username'],
              'error' => $errors
            ]);
          } else {
            return $this->render('partials/success.twig', [
              'name' => $_SESSION['name'],
              'username' => $_SESSION['username']
            ]);
          }
        }

        public function getSuccess() {
          return $this->render('partials/success.twig', [
            'name' => $_SESSION['name'],
            'username' => $_SESSION['username']
          ]);
        }

        public function getError() {
          return $this->render('partials/error.twig', [
            'name' => $_SESSION['name'],
            'username' => $_SESSION['username']
          ]);
        }

        public function clean($string) {
        $string = str_replace(
            array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ñ', 'Ñ'),
            array('Á', 'Á', 'É', 'É', 'Í', 'Í', 'Ó', 'Ó', 'Ú', 'Ú', 'Ñ', 'Ñ'),
            $string
        );
          $string = trim($string);
          $string = strtoupper($string);
          return $string;
        }
    }
