<?php

namespace Awsw\Gesi\FormulariosAjax\MensajeSecretaria;

use Awsw\Gesi\App;
use Awsw\Gesi\Datos\MensajeSecretaria;
use Awsw\Gesi\FormulariosAjax\FormularioAjax;
use Awsw\Gesi\Sesion;

/**
 * Formulario AJAX de visualización de un usuario de personal docente por parte 
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
 * @version 0.0.4-beta.01
 */

class MensajeSecretariaPsRead extends FormularioAjax
{

    /**
     * Initialize specific form constants.
     *
     * @var string FORM_ID
     * @var string FORM_NAME
     * @var string TARGET_CLASS_NAME
     * @var string SUBMIT_URL
     */
    private const FORM_ID = 'mensaje-secretaria-ps-read';
    private const FORM_NAME = 'Ver mensaje de Secretaría';
    private const TARGET_CLASS_NAME = 'MensajeSecretaria';
    private const SUBMIT_URL = '/ps/mensajesecretaria/read/';

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
        if (! MensajeSecretaria::dbExisteId($uniqueId)) {
            $responseData = array(
                'status' => 'error',
                'error' => 404, // Not found.
                'messages' => array(
                    'El mensaje de Secretaría solicitado no existe.'
                )
            );

            return $responseData;
        }

        $mensaje = MensajeSecretaria::dbGet($uniqueId);

        // Map data to match placeholder inputs' names
        $responseData = array(
            'status' => 'ok',
            self::TARGET_CLASS_NAME => $mensaje
        );

        return $responseData;
    }

    public function generateFormInputs(): string
    {
        $html = <<< HTML
        <div class="form-group">
            <label>De</label>
            <input class="form-control" type="text" name="fromNombre" disabled="disabled" />
        </div>
        <div class="form-group">
            <label>Email</label>
            <input class="form-control" type="text" name="fromEmail" disabled="disabled" />
        </div>
        <div class="form-group">
            <label>Número de teléfono</label>
            <input class="form-control" type="text" name="fromTelefono" disabled="disabled" />
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input class="form-control" type="text" name="fecha" disabled="disabled" />
        </div>
        <div class="form-group">
            <label>Contenido del mensaje</label>
            <textarea class="form-control" name="contenido" rows="3" disabled="disabled"></textarea>
        </div>
        HTML;

        return $html;
    }

    public function processSubmit(array $data = array()): void
    {
    }
}

?>