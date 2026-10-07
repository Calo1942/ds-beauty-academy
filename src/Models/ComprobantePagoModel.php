<?php

namespace DsBeautyAcademy\Models;

use Exception;
use PDO;
use PDOException;
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

        if (self::validator($referencia_transaccion, $this->validate_referencia) !== true) {
            throw new Exception("La referencia de la transacción es inválida.");
        }

        if (self::validator($monto, $this->validate_decimal) !== true || (float)$monto <= 0) {
            throw new Exception("El monto debe ser un número decimal mayor a cero.");
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
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($result) ? $result : false;
        } catch (PDOException $e) {
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

            $this->con->beginTransaction();

            $comprobanteExistente = $this->verificarDatosExistentes($this->referencia_transaccion);
            if ($comprobanteExistente) {
                throw new Exception("Ya existe un comprobante registrado con esta referencia de transacción.");
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
            $id_generado = $this->con->lastInsertId();

            $this->con->commit();

            return $this->success(201, "Comprobante de pago creado exitosamente.", [
                'id_comprobante_pago' => $id_generado
            ]);
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return $this->error(400, "Error en base de datos: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return $this->error(400, $e->getMessage());
        }
    }

    /**
     * Conciliación Transaccional de Pagos (ACID)
     * Verifica o rechaza el pago y el trigger trg_pago_estatus_change sincroniza la matrícula
     */
    public function conciliarPago($id_comprobante, $nuevo_estatus)
    {
        try {
            if (self::validator($id_comprobante, $this->validate_id) !== true) {
                throw new Exception("ID de comprobante inválido.");
            }

            if (!in_array($nuevo_estatus, ['Verificado', 'Rechazado'], true)) {
                throw new Exception("El estatus debe ser 'Verificado' o 'Rechazado'.");
            }

            $this->con->beginTransaction();

            // Bloqueo pesimista con FOR UPDATE
            $stmtCheck = $this->con->prepare("SELECT id_comprobante_pago, id_matricula, estatus_pago FROM comprobante_pagos WHERE id_comprobante_pago = ? FOR UPDATE");
            $stmtCheck->execute([$id_comprobante]);
            $comprobante = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$comprobante) {
                throw new Exception("Comprobante de pago no encontrado.");
            }

            $stmtUpdate = $this->con->prepare("UPDATE comprobante_pagos SET estatus_pago = ?, fecha_actualizacion = NOW() WHERE id_comprobante_pago = ?");
            $stmtUpdate->execute([$nuevo_estatus, $id_comprobante]);

            $this->con->commit();

            return $this->success(200, "Comprobante conciliado como '{$nuevo_estatus}' exitosamente.");

        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return $this->error(400, "Error al conciliar pago: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return $this->error(400, $e->getMessage());
        }
    }

    public function buscarTodos()
    {
        try {
            $sql = "SELECT cp.*, 
                           b.nombre_banco,
                           CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                           e.cedula_estudiante,
                           cu.nombre_curso,
                           m.precio_acordado,
                           m.estatus_pago AS estatus_matricula
                    FROM comprobante_pagos cp
                    JOIN banco b ON cp.id_banco = b.id_banco
                    JOIN matriculas m ON cp.id_matricula = m.id_matricula
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                    JOIN cursos cu ON ic.id_curso = cu.id_curso
                    ORDER BY cp.id_comprobante_pago DESC";

            $stmt = $this->con->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->success(200, "Comprobantes de pago obtenidos", $result);
        } catch (PDOException $e) {
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
                           CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) AS nombre_estudiante,
                           e.cedula_estudiante,
                           cu.nombre_curso,
                           m.precio_acordado,
                           m.estatus_pago AS estatus_matricula
                    FROM comprobante_pagos cp
                    JOIN banco b ON cp.id_banco = b.id_banco
                    JOIN matriculas m ON cp.id_matricula = m.id_matricula
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                    JOIN cursos cu ON ic.id_curso = cu.id_curso
                    WHERE cp.id_comprobante_pago = ?";

            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $this->success(200, "Comprobante de pago obtenido", $result);
            }
            return $this->error(404, "Comprobante de pago no encontrado");
        } catch (PDOException $e) {
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

            $this->con->beginTransaction();

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

            $this->con->commit();

            return $this->success(200, "Comprobante de pago actualizado correctamente");
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return $this->error(400, "Error al actualizar: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return $this->error(400, $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del comprobante es inválido.");
            }

            $this->con->beginTransaction();

            $sql = "UPDATE comprobante_pagos SET estatus_pago = 'Rechazado' WHERE id_comprobante_pago = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);

            $this->con->commit();

            return $this->success(200, "Comprobante de pago rechazado correctamente");
        } catch (PDOException $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return $this->error(400, "Error al eliminar: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->con->inTransaction()) {
                $this->con->rollBack();
            }
            return $this->error(400, $e->getMessage());
        }
    }
}
