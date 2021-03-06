<?php

namespace Awsw\Gesi\Vistas;

/**
 * Representa un modelo para las vistas.
 *
 * @package awsw-gesi
 * Gesi
 * Aplicación de gestión de institutos de educación secundaria
 *
 * @author Andrés Ramiro Ramiro
 * @author Nicolás Pardina Popp
 * @author Pablo Román Morer Olmos
 * @author Juan Francisco Carrión Molina
 *
 * @version 0.0.4
 */

abstract class Modelo
{
	protected $nombre;
	protected $id;

	/**
	 * Procesa la lógica de la vista en el elemento <article>, que deberá 
	 * imprimir HTML y realizar lo que sea conveniente.
	 */
	abstract public function procesaContent(): void;

	public function getNombre(): string
	{
		return $this->nombre;
	}

	public function getId(): string
	{
		return $this->id;
	}
}

?>