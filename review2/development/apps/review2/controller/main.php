<?php
/**
 * User Controller
 *
 * @author Serhii Shkrabak
 * @global object $CORE
 * @package Controller\Main
 */
namespace Controller;
class Main
{
	use \Library\Shared;

	private $model;

	public function exec():?array {
		include 'model/config/patterns.php';
		$result = null;
		$url = $this->getVar('REQUEST_URI', 'e');

		if(!$url) throw new \Exception('REQUEST_UNKNOWN 5 - URL doesn\'t exist'); //проверка на наличие url

		$path = explode('/', $url);

		if (isset($path[2]) && !strpos($path[1], '.')) { // Disallow directory changing
			$file = ROOT . 'model/config/methods/' . $path[1] . '.php';
			if (file_exists($file)) { //если существует файл
				include $file;
				if (isset($methods[$path[2]])) {
					$details = $methods[$path[2]];
					$request = [];
					foreach ($details['params'] as $param) {
						$var = $this->getVar($param['name'], $param['source']);
						if ($var) {
							if(isset($param['pattern'])) {
								if(preg_match($patterns[$param['pattern']]['regex'], $var)) { //если соответсвует регулярному выражению
									if(isset($patterns[$param['pattern']]['callback']))
										$var = preg_replace_callback($patterns[$param['pattern']]['regex'], $patterns[$param['pattern']]['callback'], $var); 
									$request[$param['name']] = $var;
								}
								else throw new \Exception('REQUEST_INCORRECT 2 - Incorrect value. Please try again'); //неправильно введенные данные
							}
							else $request[$param['name']] = $var;
						}
						else if(!$param['required']) { //если параметр необязательный
							if(isset($param['default'])) //если установлено значение по умолчанию
								$request[$param['name']] = $param['default']; //устанавливается значение по умолчанию
							else throw new \Exception('REQUEST_UNKNOWN 5 - No default parameter'); //нет значения по умолчанию
						}
						else throw new \Exception('REQUEST_INCOMPLETE 1 - All required fields must be filled');
					}
					if (method_exists($this->model, $path[1] . $path[2])) {
						$method = [$this->model, $path[1] . $path[2]];
						$result = $method($request);
					}
					else throw new \Exception('REQUEST_UNKNOWN 5 - method doesn\'t exist');

				}
				else throw new \Exception('REQUEST_UNKNOWN 5 - method doesn\'t exist');

			}
			else throw new \Exception('REQUEST_UNKNOWN 5 - file doesn\'t exist'); //
		}

		return $result;
	}

	public function __construct() {
		// CORS configuration
		$origin = $this -> getVar('HTTP_ORIGIN', 'e');

		$front = $this -> getVar('FRONT', 'e');

		foreach ( [$front] as $allowed )
			if ( $origin == "https://$allowed") {
				header( "Access-Control-Allow-Origin: $origin" );
				header( 'Access-Control-Allow-Credentials: true' );
			}
		$this->model = new \Model\Main;
	}
}