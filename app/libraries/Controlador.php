<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
	class Controlador {

		public function modelo($modelo) {
			require_once '../app/models/' . $modelo . '.php';
			return new $modelo();
		}

		public function vista($vista, $datos = []) {
			if(file_exists('../app/views/' . $vista . '.php')) {
				require_once '../app/views/' . $vista . '.php';
			} else {
				require_once '../app/views/Error/Error404.php';
			}
		}

		public function MostrarAlerta($vista, $selectkey, $selectvalue, $type, $message){
            $this->vista($vista, [$selectkey => $selectvalue ,'type' => $type, 'message' => $message]);
        }

	}
