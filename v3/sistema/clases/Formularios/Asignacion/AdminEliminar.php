<?php

/**
 * Gesión del formulario para eliminar una Asignacion de la Base de Datos.
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


namespace Awsw\Gesi\Formularios\Asignacion;

use Awsw\Gesi\Formularios\Formulario;
use Awsw\Gesi\Datos\Asignatura;
use Awsw\Gesi\Datos\Grupo;
use Awsw\Gesi\Datos\Asignacion;
use Awsw\Gesi\Vistas\Vista;


class AdminEliminar extends Formulario{

    private $id;
    private $nombre;


    public function __construct(string $action, int $id){
        parent::__construct('form-asignatura-eliminar', array('action' => $action));
        $this->id = $id;
       
    }

    protected function generaCampos(array & $datos_iniciales = array()) : void{
        $this->html .= <<< HTML
    
    <div class="form-group">
        <input type="checkbox" name="eliminar" id="eliminar" value="$this->id" required = "required" >
        <label for = "eliminar">Eliminar la asignacion y todos sus datos de Gesi. </label>
    </div>

    <div class= "form-actions">
        <button type="submit" class="btn">Eliminar</button>
    </div>

HTML;

    }


    protected function procesa(array & $datos): void{
        $eliminar = isset($datos['eliminar']) ?? '';

        if(empty($eliminar) ||$eliminar != $this->id){
            Vista::encolaMensajeError('Debes marcar la casilla de verificacion para eliminar la asignacion');

        } 

        

        if(!Vista::hayMensajesError()){
            $resultado = Asignacion::dbEliminar($this->id);

            if(!$resultado){
                Vista::encolaMensajeError("No se ha podido eliminar la asignacion correctamente", "/admin/asignaciones/");
            
            }else{
                Vista::encolaMensajeExito("Se ha eliminado la asignacion correctamente.", "/admin/asignaciones/");
            }

            $this->genera();
        }


       


    }




}