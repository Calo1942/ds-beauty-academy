<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class ComprobantePagoModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $table = 'comprobante_pagos';
    private $idField = 'id_comprobante';
    private $statusField = 'estatus_comprobante';
    
    private $id_matricula;
    private $id_banco;
    private $monto_pago;
    private $fecha_pago;
    private $referencia_pago;
    private $url_comprobante_pago;
    private $estatus_comprobante;

    private $module_name = [
        'singular' => 'Comprobante de Pago',
        'plural' => 'Comprobantes de Pago'
    ];

    public $validate_estatus_comprobante_opciones = ['Pendiente', 'Aprobado', 'Rechazado'];

    public function setIdMatricula($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de matrícula es inválido.");
        }
        $this->id_matricula = $valor;
    }

    public function setIdBanco($valor)
    {
        if (self::validator($valor, $this->validate_id) !== true) {
            throw new Exception("El ID de banco es inválido.");
        }
        $this->id_banco = $valor;
    }

    public function setMontoPago($valor)
    {
        if (self::validator($valor, $this->validate_decimal) !== true) {
            throw new Exception("El monto de pago es inválido.");
        }
        $this->monto_pago = $valor;
    }

    public function setFechaPago($valor)
    {
        if (self::validator($valor, $this->validate_datetime) !== true && self::validator($valor, $this->validate_fecha) !== true) {
            throw new Exception("La fecha de pago es inválida.");
        }
        $this->fecha_pago = $valor;
    }

    public function setReferenciaPago($valor)
    {
        if (self::validator($valor, $this->validate_text) !== true) {
            throw new Exception("La referencia de pago es inválida.");
        }
        $this->referencia_pago = $valor;
    }

    public function setUrlComprobantePago($valor)
    {
        if (empty($valor) || strlen($valor) > 512) {
            throw new Exception("La URL del comprobante es inválida o muy larga.");
        }
        $this->url_comprobante_pago = $valor;
    }

    public function setEstatusComprobante($valor)
    {
        if (self::validator($valor, $this->validate_estatus_comprobante_opciones) !== true) {
            throw new Exception("El estatus del comprobante es inválido.");
        }
        $this->estatus_comprobante = $valor;
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
            $stmt = $this->con->query("SELECT * FROM {$this->table} WHERE {$this->statusField} != 'Rechazado'");
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

            $sql = "UPDATE {$this->table} SET {$this->statusField} = 'Rechazado' WHERE {$this->idField} = ?";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute([$id])) {
                return self::success(200, "{$this->module_name['singular']} rechazado/eliminado");
            }
            throw new Exception('Error al eliminar');
        } catch (Exception $e) {
            return self::error(500, 'Error al eliminar', $e->getMessage());
        }
    }
}
