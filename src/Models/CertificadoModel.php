<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class CertificadoModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'certificados';
    private $idField = 'id_certificado';
    private $statusField = 'estatus_certificado';
    
    private $id_matricula;
    private $codigo_certificado;
    private $url_pdf_certificado;
    private $fecha_emision;
    private $estatus_certificado;

    private $module_name = [
        'singular' => 'Certificado',
        'plural' => 'Certificados'
    ];

    public $validate_estatus_certificado_opciones = ['Emitido', 'Anulado'];

    public function setIdMatricula($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de matrícula es inválido.");
        }
        $this->id_matricula = $valor;
    }

    public function setCodigoCertificado($valor)
    {
        if (self::validator($valor, $this->validate_text) !== true) {
            throw new Exception("El código de certificado es inválido.");
        }
        $this->codigo_certificado = $valor;
    }

    public function setUrlPdfCertificado($valor)
    {
        if (empty($valor) || strlen($valor) > 512) {
            throw new Exception("La URL del certificado es inválida o muy larga.");
        }
        $this->url_pdf_certificado = $valor;
    }

    public function setFechaEmision($valor)
    {
        if (!empty($valor)) {
            if (self::validator($valor, $this->validate_datetime) !== true && self::validator($valor, $this->validate_fecha) !== true) {
                throw new Exception("La fecha de emisión es inválida.");
            }
            $this->fecha_emision = $valor;
        } else {
            $this->fecha_emision = date('Y-m-d H:i:s');
        }
    }

    public function setEstatusCertificado($valor)
    {
        if (self::validator($valor, $this->validate_estatus_certificado_opciones) !== true) {
            throw new Exception("El estatus del certificado es inválido.");
        }
        $this->estatus_certificado = $valor;
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
            $stmt = $this->con->query("SELECT * FROM {$this->table} WHERE {$this->statusField} = 'Emitido'");
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
            $stmt = $this->con->prepare("SELECT * FROM {$this->table} WHERE {$this->idField} = ?");
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

            $sql = "UPDATE {$this->table} SET {$this->statusField} = 'Anulado' WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute([$id])) {
                return self::success(200, "{$this->module_name['singular']} anulado");
            }
            throw new Exception('Error al eliminar');
        } catch (Exception $e) {
            return self::error(500, 'Error al eliminar', $e->getMessage());
        }
    }
}
