<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class CertificadoInstructorModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'certificado_instructor';
    private $idField = 'id_certificado_instructor';

    private $titulo_certificado_instructor;
    private $descripcion_certificado_instructor;
    private $categoria_certificado_instructor;
    private $url_pdf_certificado_instructor;
    private $id_instructor;

    private $module_name = [
        'singular' => 'Certificado de Instructor',
        'plural' => 'Certificados de Instructores'
    ];

    public function setTituloCertificadoInstructor($valor)
    {
        if (self::validator($valor, $this->validate_text_long) !== true) {
            throw new Exception("El título del certificado es inválido.");
        }
        $this->titulo_certificado_instructor = $valor;
    }

    public function setDescripcionCertificadoInstructor($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_description) !== true) {
                throw new Exception("La descripción es inválida.");
            }
            $this->descripcion_certificado_instructor = $valor;
        } else {
            $this->descripcion_certificado_instructor = null;
        }
    }

    public function setCategoriaCertificadoInstructor($valor)
    {
        if (self::validator($valor, $this->validate_text_long) !== true) {
            throw new Exception("La categoría es inválida.");
        }
        $this->categoria_certificado_instructor = $valor;
    }

    public function setUrlPdfCertificadoInstructor($valor)
    {
        if (!empty($valor)) {
            if (strlen($valor) > 512) {
                throw new Exception("La URL del PDF es muy larga.");
            }
            $this->url_pdf_certificado_instructor = $valor;
        } else {
            $this->url_pdf_certificado_instructor = null;
        }
    }

    public function setIdInstructor($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID del instructor es inválido.");
        }
        $this->id_instructor = $valor;
    }

    private function validarYSetearDatos($data)
    {
        $validatedData = [];
        foreach ($data as $key => $value) {
            $methodName = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
                $validatedData[$key] = $this->$key;
            }
        }
        return $validatedData;
    }

    private function validarId($id)
    {
        if (self::validator($id, $this->validate_id) !== true) {
            throw new Exception("ID inválido");
        }
    }

    public function guardar($data)
    {
        try {
            $validatedData = $this->validarYSetearDatos($data);

            if (empty($validatedData)) {
                throw new Exception('No hay datos válidos para guardar');
            }

            $columns = array_keys($validatedData);
            $placeholders = array_fill(0, count($validatedData), '?');
            $values = array_values($validatedData);

            $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";

            $stmt = $this->con->prepare($sql);

            if ($stmt->execute($values)) {
                return self::success(201, "{$this->module_name['singular']} creado exitosamente");
            }
            throw new Exception('Error al guardar');
        } catch (Exception $e) {
            return self::error(500, 'Error al almacenar', $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $sql = "SELECT c.*, CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) as nombre_instructor 
                    FROM {$this->table} c
                    JOIN instructores i ON c.id_instructor = i.id_instructor";
            $stmt = $this->con->query($sql);
            $result = $stmt->fetchAll();
            return self::success(200, "{$this->module_name['plural']} obtenidos", $result);
        } catch (Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            $this->validarId($id);
            $sql = "SELECT c.*, CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) as nombre_instructor 
                    FROM {$this->table} c
                    JOIN instructores i ON c.id_instructor = i.id_instructor
                    WHERE c.{$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            return self::success(200, "{$this->module_name['singular']} obtenido", $result);
        } catch (Exception $e) {
            return self::error(500, 'Error al obtener', $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $this->validarId($id);

            $validatedData = $this->validarYSetearDatos($data);

            if (empty($validatedData)) {
                throw new Exception('No hay datos válidos para actualizar');
            }

            $updates = [];
            $values = [];

            foreach ($validatedData as $field => $value) {
                $updates[] = "$field = ?";
                $values[] = $value;
            }

            $values[] = $id;

            $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " 
                    WHERE {$this->idField} = ?";

            $stmt = $this->con->prepare($sql);
            if ($stmt->execute($values)) {
                return self::success(200, "{$this->module_name['singular']} actualizado");
            }
            throw new Exception('Error al actualizar');
        } catch (Exception $e) {
            return self::error(500, 'Error al actualizar', $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $this->validarId($id);

            $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute([$id])) {
                return self::success(200, "{$this->module_name['singular']} eliminado");
            }
            throw new Exception('Error al eliminar');
        } catch (Exception $e) {
            return self::error(500, 'Error al eliminar', $e->getMessage());
        }
    }
}
