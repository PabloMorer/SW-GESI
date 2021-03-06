<?php

namespace Awsw\Gesi\FormulariosAjax\Usuario;

use Awsw\Gesi\App;
use Awsw\Gesi\Datos\Grupo;
use Awsw\Gesi\FormulariosAjax\FormularioAjax;
use Awsw\Gesi\Datos\Usuario;
use Awsw\Gesi\Sesion;

/**
 * Formulario AJAX de visualización de un usuario estudiante por parte 
 * de un administrador (personal de Secretaría).
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

class EstPsRead extends FormularioAjax
{

    /**
     * Initialize specific form constants.
     *
     * @var string FORM_ID
     * @var string FORM_NAME
     * @var string TARGET_CLASS_NAME
     * @var string SUBMIT_URL
     */
    private const FORM_ID = 'usuario-est-ps-read';
    private const FORM_NAME = 'Ver estudiante';
    private const TARGET_CLASS_NAME = 'Usuario';
    private const SUBMIT_URL = '/ps/usuarios/est/read/';

    /**
     * Constructs the form object
     */
    public function __construct($api = false)
    {
        Sesion::requerirSesionPs($api);

        $app = App::getSingleton();

        parent::__construct(
            self::FORM_ID,
            self::FORM_NAME,
            self::TARGET_CLASS_NAME,
            $app->getUrl() . self::SUBMIT_URL,
            null
        );

        $this->setReadOnlyTrue();
    }

    protected function getDefaultData(array $requestData) : array
    {
        // Check that uniqueId was provided
        if (! isset($requestData['uniqueId'])) {
            $responseData = array(
                'status' => 'error',
                'error' => 400, // Bad request
                'messages' => array(
                    'Falta el parámetro "uniqueId".'
                )
            );

            return $responseData;
        }

        $uniqueId = $requestData['uniqueId'];

        // Comprobar que el uniqueId es válido.
        if (! Usuario::dbExisteId($uniqueId)) {
            $responseData = array(
                'status' => 'error',
                'error' => 404, // Not found.
                'messages' => array(
                    'El usuario estudiante solicitado no existe.'
                )
            );

            return $responseData;
        }
        
        // Formalización HATEOAS de grupos.
        $grupoLink = FormularioAjax::generateHateoasSelectLink(
            'grupo',
            'single',
            Grupo::dbGetAll()
        );

        $usuario = Usuario::dbGet($uniqueId);

        // Map data to match placeholder inputs' names
        $responseData = array(
            'status' => 'ok',
            'links' => array(
                $grupoLink
            ),
            self::TARGET_CLASS_NAME => $usuario
        );

        return $responseData;
    }

    public function generateFormInputs(): string
    {
        $html = <<< HTML
        <div class="form-group">
            <label for="nif">NIF</label>
            <input class="form-control" type="text" name="nif" id="nif" placeholder="NIF" disabled="disabled">
        </div>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" disabled="disabled">
        </div>
        <div class="form-group">
            <label for="apellidos">Apellidos</label>
            <input class="form-control" type="text" name="apellidos" id="apellidos" placeholder="Apellidos" disabled="disabled">
        </div>
        <div class="form-group">
            <label for="fechaNacimiento">Fecha de nacimiento</label>
            <input class="form-control" type="text" name="fechaNacimiento" id="fechaNacimiento" placeholder="Fecha de nacimiento" disabled="disabled">
        </div>
        <div class="form-group">
            <label for="fechaUltimoAcceso">Fecha de último acceso</label>
            <input class="form-control" type="text" name="fechaUltimoAcceso" id="fechaNacimiento" placeholder="Fecha de último acceso" disabled="disabled">
        </div>
        <div class="form-group">
            <label for="numeroTelefono">Número de teléfono</label>
            <input class="form-control" type="text" name="numeroTelefono" id="numeroTelefono" placeholder="Número de teléfono" disabled="disabled">
        </div>
        <div class="form-group">
            <label for="email">Dirección de correo electrónico</label>
            <input class="form-control" type="text" name="email" id="email" placeholder="Dirección de correo electrónico" disabled="disabled">
        </div>
        <div class="form-group">
            <label for="grupo">Grupo</label>
            <select class="form-control" name="grupo" id="grupo" disabled="disabled">
            </select>
        </div>
        HTML;

        return $html;
    }

    public function processSubmit(array $data = array()): void
    {
    }
}

?>