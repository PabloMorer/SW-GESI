<?php

/**
 * Métodos relacionados con las asignaturas.
 *
 * @package awsw-gesi
 * Gesi
 * Aplicación de gestión de institutos de educación secundaria
 *
 * @author Andrés Ramiro Ramiro
 * @author Cintia María Herrera Arenas
 * @author Nicolás Pardina Popp
 * @author Pablo Román Morer Olmos
 * @author Juan Francisco Carrión Molina
 *
 * @version 0.0.2
 */

namespace Awsw\Gesi\Datos;

use \Awsw\Gesi\App;

class Asignatura
{

	private $id;

	private $curso;

	private $nombre_corto;

	private $nombre_largo;

	/**
	 * Constructor.
	 */
	private function __construct(
		$id,
		$curso,
		$nombre_corto,
		$nombre_largo
		)
		{
		    $this->id = $id;
			$this->curso = $curso;
			$this->nombre_corto = $nombre_corto;
			$this->nombre_largo = $nombre_largo;
		}


		/**
		 * Crea una nueva Asignatura
		 * 
		 * @param $nombreAsign Nombre de la asignatura a crear
		 * 
		 */
	public function crea($nombreAsignatura, $nombreAsign, $curso): bool{

		if(self::dbExiste($nombreAsignatura, $curso)){
			$crea = false;
		}else{
			$asignatura = new Asignatura(0, $curso, $nombreAsignatura, $nombreAsign);
			if(){
				$crea = true;
			}else{
				$crea =
			}
		}

		return $crea;
	}

	public static function dbExiste($nombre, $curso): bool{

		$bbdd = App::getSingleton()->bbddCon();

		$sentencia = $bbdd->prepare("
			SELECT id
			FROM gesi_usuarios
			WHERE nombre = ? AND curso = ?
			LIMIT 1
		");

		$sentencia->bind_param(
			"si",
			$nombre,
			$curso
		);
		
		$sentencia->execute();
		
		$sentencia->store_result();

		if ($sentencia->num_rows > 0) {
			$existe = true;
		} else {
			$existe = false;
		}

		$sentencia->close();

		return $existe;
	 

	/** 
	 * 	Insertar una nueva Asignatura en la base de datos
	 * 
	 * @param Asignatura
	 * 
	 * @return int
	 */		$bbdd = App::getSingleton()->bbddCon();

		$sentencia = $bbdd->prepare("
			SELECT 
id,
			curso,
			nombre_corto,
			nombre_largo
			FROM
				gesi_asignatura
			WHERE
				id = ?
			LIMIT 1
		");
		$sentencia->bind_param(
			"i",
			$id
		);
		
		$sentencia->execute();
		$resultado = $sentencia->get_result()->fetch_object();
		
		$asignatura = new Asignatura(
			$resultado->id,
			$resultado->curso,
			$resultado->nombre_corto,
			$resultado->nombre_largo

		);
		
		$sentencia->close();

		return $asignatura;
	}


	/**
	 * Comprueba si una asignatura existe en la base de datos en base a su
	 * identificador.
	 *
	 * @param int
	 *
	 * @return bool
	 */

	public static function  dbExisteId(int $id) : bool
	{		
		$bbdd = App::getSingleton()->bbddCon();

		$sentencia = $bbdd->prepare("
			SELECT
				id
			FROM
				gesi_asignatura
			WHERE
				id = ?
			LIMIT 1
		");

		$sentencia->bind_param(
			"i",
			$id
		);
		
		$sentencia->execute();
		
		$sentencia->store_result();

		if ($sentencia->num_rows > 0) {
			$existe = true;
		} else {
			$existe = false;
		}

		$sentencia->close();

		return $existe;
	}


	/**
	 * Actualiza la información de una asignatura en la base de datos.
	 * 
	 * @param asignatura la asignatura cuya información se va a actualizar.
	 * 
	 * @return bool Resultado de la ejecución de la sentencia.
	 */
	public static function dbActualizar() : bool
	{
		$bbdd = App::getSingleton()->bbddCon();

		$sentencia = $bbdd->prepare("
			UPDATE
				gesi_asignatura
			SET
				curso = ?,
				nombre_corto = ?,
				nombre_largo = ?,
			WHERE
				id = ?
		");

		$id = $this->getId();
		$curso = $this->getCurso();
		$nombre_corto = $this->getNombreCorto();
		$nombre_largo = $this->getNombreLargo();

		$sentencia->bind_param(
			"issi", 
			$curso,
			$nombre_corto,
			$nombre_largo,
			$id
		);

		$resultado = $sentencia->execute();

		$sentencia->close();

		return $resultado;
	}

	public function dbGetAll(): array {

		$bbdd = App::getSingleton()->bbddCon();

		$sentencia = $bbdd->prepare("
			SELECT 
				id,
				curso,
				nombre_corto,
				nombre_largo
			FROM
				gesi_asignaturas			
		");
	
		$sentencia->execute();
		$sentencia->store_result();

		$sentencia->bind_result(
			$result_id,
			$result_curso,
			$result_nombre_corto,
			$result_nombre_largo
		);

		$asignaturas = array();

		while($sentencia->fetch()) {
			$asginaturas[] = new Asignatura(
				$result_id,
				$result_curso,
				$result_nombre_corto,
				$result_nombre_largo
			);	
		}
		$sentencia->close();
		return $asignaturas;	
	}

	public function dbAny(): bool {
		$bbdd = App::getSingleton()->bbddCon();

		$sentencia = $bbdd->prepare("
			SELECT
				id
			FROM
				gesi_mensajes_secretaria
		");

		$sentencia->execute();
		$sentencia->store_result();

		if($sentencia->num_rows > 0){
			$existe = true;
		}
		else{
			$existe = false;
		}

		$sentencia->close();

		return $existe;
	}

	public function getId(){
		return $this->id;
	}

	public function getCurso(){
		return $this->curso;
	}

	public function getNombreCorto(){
		return $this->nombre_corto;
	}

	public function getNombreLargo(){
		return $this->nombre_largo;
	}
}