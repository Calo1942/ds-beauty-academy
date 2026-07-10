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

    private $id_comprobante_pago;
    private $id_matricula;
    private $id_banco;
    private $referencia_transaccion;
    private $monto;
    private $url_img_comprobante;
    private $fecha_pago;
    private $estatus_pago;

    private function validarDatos($data, $isUpdate = false)
    {
        $id = $data['id_comprobante_pago'] ?? null;
        $id_matricula = $data['id_matricula'] ?? null;
        $id_banco = $data['id_banco'] ?? null;
        $referencia_transaccion = $data['referencia_transaccion'] ?? null;
        $monto = $data['monto'] ?? null;
        $url_img_comprobante = $data['url_img_comprobante'] ?? null;
        $fecha_pago = $data['fecha_pago'] ?? null;
        $estatus = $data['estatus_pago'] ?? 'Pendiente';

        if ($isUpdate || $id !== null) {
            if ($this->validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del comprobante es inválido.");
            }
            $this->id_comprobante_pago = $id;
        }

        if (self::validator($id_matricula, $this->validate_id) !== true) {
            throw new Exception("El ID de la matrícula es inválido.");
        }

        if (self::validator($id_banco, $this->validate_id) !== true) {
            throw new Exception("El ID del banco es inválido.");
        }

        if (self::validator($referencia_transaccion, $this->validate_text) !== true) {
            throw new Exception("La referencia de la transacción es inválida.");
        }

        if (self::validator($monto, $this->validate_decimal) !== true) {
            throw new Exception("El monto es inválido.");
        }

        if (!empty($url_img_comprobante) && strlen($url_img_comprobante) > 512) {
            throw new Exception("La URL de la imagen del comprobante es muy larga.");
        }

        if (self::validator($fecha_pago, $this->validate_datetime) !== true && self::validator($fecha_pago, $this->validate_fecha) !== true) {
            throw new Exception("La fecha de pago es inválida.");
        }

        $estatus_validos = ['Pendiente', 'Verificado', 'Rechazado'];
        if (!in_array($estatus, $estatus_validos, true)) {
            throw new Exception("El estatus del pago es inválido.");
        }

        $this->id_matricula = $id_matricula;
        $this->id_banco = $id_banco;
        $this->referencia_transaccion = $referencia_transaccion;
        $this->monto = $monto;
        $this->url_img_comprobante = empty($url_img_comprobante) ? null : $url_img_comprobante;
        $this->fecha_pago = $fecha_pago;
        $this->estatus_pago = $estatus;

        return true;
    }

    public function verificarDatosExistentes($referencia, $id_excluir = null)
    {
        try {
            $sql = "SELECT id_comprobante_pago, estatus_pago FROM comprobante_pagos WHERE referencia_transaccion = :referencia";
            if ($id_excluir) {
                $sql .= " AND id_comprobante_pago != :id_excluir";
            }

            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':referencia', trim($referencia));
            if ($id_excluir) {
                $stmt->bindValue(':id_excluir', $id_excluir);
            }
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return ($result) ? $result : false;
        } catch (\PDOException $e) {
            throw new Exception("Error al verificar existencia: " . $e->getMessage());
        }
    }

    private function obtenerParametros()
    {
        return [
            ':id_matricula' => $this->id_matricula,
            ':id_banco' => $this->id_banco,
            ':referencia' => $this->referencia_transaccion,
            ':monto' => $this->monto,
            ':url_img' => $this->url_img_comprobante,
            ':fecha_pago' => $this->fecha_pago,
            ':estatus' => $this->estatus_pago
        ];
    }

    public function guardar($data)
    {
        try {
            $this->validarDatos($data);
            $this->guardarDatos();
            return $this->success(201, "Comprobante de pago creado exitosamente.");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function guardarDatos()
    {
        try {
            $comprobanteExistente = $this->verificarDatosExistentes($this->referencia_transaccion);

            if ($comprobanteExistente) {
                throw new Exception("Ya existe un comprobante con esta referencia de transacción.");
            }

            $sqlInsert = "INSERT INTO comprobante_pagos (
                id_matricula, 
                id_banco, 
                referencia_transaccion, 
                monto, 
                url_img_comprobante, 
                fecha_pago, 
                estatus_pago
            ) VALUES (
                :id_matricula, :id_banco, :referencia, :monto, :url_img, :fecha_pago, :estatus
            )";
            $stmtInsert = $this->con->prepare($sqlInsert);
            $stmtInsert->execute($this->obtenerParametros());
        } catch (\PDOException $e) {
            throw new Exception("Error en la base de datos: " . $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $sql = "SELECT cp.*, 
                    b.nombre_banco,
                    CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) as nombre_estudiante,
                    cu.nombre_curso 
                    FROM comprobante_pagos cp
                    JOIN banco b ON cp.id_banco = b.id_banco
                    JOIN matriculas m ON cp.id_matricula = m.id_matricula
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN cursos cu ON m.id_curso = cu.id_curso
                    WHERE cp.estatus_pago != 'Rechazado'";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->success(200, "Comprobantes de pago obtenidos", $result);
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del comprobante es inválido.");
            }

            $sql = "SELECT cp.*, 
                    b.nombre_banco,
                    CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) as nombre_estudiante,
                    cu.nombre_curso 
                    FROM comprobante_pagos cp
                    JOIN banco b ON cp.id_banco = b.id_banco
                    JOIN matriculas m ON cp.id_matricula = m.id_matricula
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN cursos cu ON m.id_curso = cu.id_curso
                    WHERE cp.id_comprobante_pago = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                return $this->success(200, "Comprobante de pago obtenido", $result);
            }
            return $this->error(404, "Comprobante de pago no encontrado");
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $data['id_comprobante_pago'] = $id;
            $this->validarDatos($data, true);
            $this->actualizarDatos();
            return $this->success(200, "Comprobante de pago actualizado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function actualizarDatos()
    {
        try {
            $comprobanteExistente = $this->verificarDatosExistentes($this->referencia_transaccion, $this->id_comprobante_pago);
            if ($comprobanteExistente) {
                throw new Exception("Ya existe otro comprobante registrado con esta referencia.");
            }

            $sql = "UPDATE comprobante_pagos SET 
                id_matricula = :id_matricula,
                id_banco = :id_banco,
                referencia_transaccion = :referencia,
                monto = :monto,
                url_img_comprobante = :url_img,
                fecha_pago = :fecha_pago,
                estatus_pago = :estatus
                WHERE id_comprobante_pago = :id";

            $stmt = $this->con->prepare($sql);

            $parametros = $this->obtenerParametros();
            $parametros[':id'] = $this->id_comprobante_pago;
            $stmt->execute($parametros);
        } catch (\PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del comprobante es inválido.");
            }
            $this->id_comprobante_pago = $id;
            $this->eliminarDatos();
            return $this->success(200, "Comprobante de pago rechazado/eliminado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function eliminarDatos()
    {
        try {
            $sql = "UPDATE comprobante_pagos SET estatus_pago = 'Rechazado' WHERE id_comprobante_pago = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $this->id_comprobante_pago);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}
