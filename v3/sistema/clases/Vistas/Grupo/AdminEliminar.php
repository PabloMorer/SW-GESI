<?php 

/**
 * TODO: NOMBRE DE LA CLASE
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

namespace Awsw\Gesi\Vistas\Grupo;

use Awsw\Gesi\Datos\Grupo;
use Awsw\Gesi\Vistas\Modelo;
use Awsw\Gesi\Sesion;
use Awsw\Gesi\Vistas\Vista;

use Awsw\Gesi\Formularios\Grupo\AdminEliminar as Formulario;

class AdminEliminar extends Modelo
{
	private const VISTA_NOMBRE = "Eliminar grupo "; // TODO
	private const VISTA_ID = "grupo-grupo-eliminar";

	private $grupo;
	private $form;
	public function __construct(int $grupo_id)
	{
		Sesion::requerirSesionPs();

		if(Grupo::dbExisteId($grupo_id)){
			$this->grupo = Grupo::dbGet($grupo_id);
		}else{
			Vista::encolaMensajeError('El grupo especificado no existe.', '/admin/grupos');
		}

		$this->nombre = self::VISTA_NOMBRE;
		$this->id = self::VISTA_ID;
		$this->form = new Formulario("/admin/grupos/$grupo_id/eliminar/", $this->grupo->getId(), $this->grupo->getNombreCorto());


		$this->form->gestiona();
	}

	public function procesaContent() : void 
	{
		$formulario = $this->form->getHtml();
		$html = <<< HTML
		<header class="page-header">
			<h1>$this->nombre</h1>
		</header>

		<section class="page-content">
			$formulario;
		</section>

HTML;

		echo $html;

	}
}

?>