<?php

/**
 * Vista de asignaturas.
 *
 * - PAS: puede editar todas las asignaturas.
 * - PD: puede ver las asignaturas que tiene asignadas.
 * - Estudiante: puede ver las asignaturas en las que está matriculado.
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

require_once __DIR__ . "/sistema/configuracion.php";

use \Awsw\Gesi\Vistas\Vista;

Vista::setPaginaActual("Asignaturas", "asignaturas");

Vista::incluirCabecera();

?>
<div class="wrapper">
	<div class="container">
		<header class="page-header">
			<h1>Asignaturas</h1>
		</header>

		<section class="page-content">
			...
		</section>
	</div>
</div>
<?php

Vista::incluirPie();
