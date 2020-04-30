<?php

/**
 * Puntos de entrada relacionados con la vistas vistas relacionadas con eventos.
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

namespace Awsw\Gesi\Controladores;

use Awsw\Gesi\Vistas\Vista as V;

class Evento extends Controlador
{
	public static function controla() : void
	{

		parent::go('/eventos/', function () {
			V::dibuja(new \Awsw\Gesi\Vistas\Evento\Lista());
		});
		
		parent::go('/eventos/([0-9]+)/ver/', function ($evento_id) {
			V::dibuja(new \Awsw\Gesi\Vistas\Evento\Ver($evento_id));
		});

	}
}