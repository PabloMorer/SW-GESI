<?php

namespace Awsw\Gesi\FormulariosAjax\Asignatura;

use Awsw\Gesi\App;
use Awsw\Gesi\Datos\Asignatura;
use Awsw\Gesi\Validacion\Valido;
use Awsw\Gesi\FormulariosAjax\FormularioAjax;
use Awsw\Gesi\Sesion;

/**
 * Formulario AJAX de creación de una asignatura por parte de un administrador 
 * (personal de Secretaría).
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

class AsignaturaPsCreate extends FormularioAjax
{

    /**
     * Initialize specific form constants
     *
     * @var string FORM_ID
     * @var string FORM_NAME
     * @var string DATA_OBJECT_NAME
     * @var string SUBMIT_URL
     * @var string EXPECTED_SUBMIT_METHOD
     * @var string ON_SUCCESS_EVENT_NAME
     * @var string ON_SUCCESS_EVENT_TARGET
     */
    private const FORM_ID = 'asignatura-ps-create';
    private const FORM_NAME = 'Crear asignatura';
    private const TARGET_CLASS_NAME = 'Asignatura';
    private const SUBMIT_URL = '/ps/asignaturas/create/';
    private const EXPECTED_SUBMIT_METHOD = FormularioAjax::HTTP_POST;
    private const ON_SUCCESS_EVENT_NAME = 'created.asignatura';
    private const ON_SUCCESS_EVENT_TARGET = '#asignatura-ps-list';

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
            self::EXPECTED_SUBMIT_METHOD
        );

        $this->setOnSuccess(
            self::ON_SUCCESS_EVENT_NAME,
            self::ON_SUCCESS_EVENT_TARGET
        );
    }

    protected function getDefaultData(array $requestData) : array
    {
        // Formalización HATEOAS de niveles.
        $asignaturaLink = FormularioAjax::generateHateoasSelectLink(
            'nivel',
            'single',
             Valido::getNivelesHateoas()
        );

        // Mapear datos para que coincidan con los nombres de los inputs.
        $responseData = array(
            'status' => 'ok',
            'links' => array(
                $asignaturaLink
            )
        );

        return $responseData;
    }

    public function generateFormInputs(): string
    {
        $html = <<< HTML
        <div class="form-group">
            <label for="nivel">Nivel</label>
            <select class="form-control" name="nivel" id="nivel" required="required">
            </select>
        </div>
        <div class="form-group">
            <label for="cursoEscolar">Curso escolar</label>
            <input class="form-control" type="number" name="cursoEscolar" id="cursoEscolar" placeholder="Curso escolar" required="required">
            <small class="form-text text-muted">Introduce el año de inicio del curso escolar. Por ejemplo, para el curso 2018 - 2019, introduce 2018.</small>
        </div>
        <div class="form-group">
            <label for="nombreCorto">Nombre corto</label>
            <input class="form-control" type="text" name="nombreCorto" id="nombreCorto" placeholder="Nombre corto" required="required" />
        </div>
        <div class="form-group">
            <label for="nombreCompleto">Nombre completo</label>
            <input class="form-control" type="text" name="nombreCompleto" id="nombreCompleto" placeholder="Nombre completo" required="required" />
        </div>
        HTML;

        return $html;
    }

    public function processSubmit(array $data = array()): void
    {
        $curso_escolar = $data['cursoEscolar'] ?? null;
        $nombre_corto = $data['nombreCorto'] ?? null;
        $nombre_completo = $data['nombreCompleto'] ?? null;

        // Comprobar nivel.
        
        $nivel = $data['nivel'] ?? null;

        if (empty($nivel)) {
            $errors[] = 'El campo nivel no puede estar vacío.';
        }

        if(empty($curso_escolar)){
            $errors[] = 'El campo curso escolar no puede estar vacío.';
        }elseif(!Valido::testStdInt($curso_escolar)){
            $errors[] = 'El campo curso escolar no es válido.';
        }

        if (empty($nombre_corto)) {
            $errors[] = 'El campo nombre corto es obligatorio.';
        } elseif (!Valido::testCadenaB($nombre_corto)){
            $errors[] = 'El campo nombre corto no es válido.';
        }

        if (empty($nombre_completo)) {
            $errors[] = 'El campo nombre completo es obligatorio.';
        } elseif (!Valido::testCadenaB($nombre_completo)){
            $errors[] = 'El campo nombre completo no es válido.';
        }

        // Comprobar si hay errores.
        if (! empty($errors)) {
            $this->respondJsonError(400, $errors); // Bad request.
        } else {

            $asignatura = new Asignatura(
              null,
              $nivel,
              $curso_escolar,
              $nombre_corto,
              $nombre_completo
            );

            $asignatura_id = $asignatura->dbInsertar();

            if ($asignatura_id) {
                $responseData = array(
                    'status' => 'ok',
                    'messages' => array('La asignatura fue creada correctamente.'),
                    self::TARGET_CLASS_NAME => $asignatura
                );
                
                $this->respondJsonOk($responseData);
            } else {
                $errors[] = 'Hubo un error al crear la asignatura.';

                $this->respondJsonError(400, $errors); // Bad request.
            }
        }
    }

    public static function autoHandle(): void
    {
        $form = new self();
        $form->manage();
    }
}

?>