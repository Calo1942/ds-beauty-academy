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

    private $id_certificado;
    private $id_matricula;
    private $titulo_certificado;
    private $descripcion_certificado;
    private $categoria_certificado;
    private $url_pdf_certificado;
    private $estatus_certificado;
    private function validarDatos($data, $isUpdate = false)
    {
        $id = $data['id_certificado'] ?? null;
        $id_matricula = $data['id_matricula'] ?? null;
        $titulo = $data['titulo_certificado'] ?? null;
        $descripcion = $data['descripcion_certificado'] ?? null;
        $categoria = $data['categoria_certificado'] ?? null;
        $url_pdf = $data['url_pdf_certificado'] ?? null;
        $estatus = $data['estatus_certificado'] ?? 'Pendiente de Emisión';
        if ($isUpdate || $id !== null) {
            if ($this->validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del certificado es inválido.");
            }
            $this->id_certificado = $id;
        }
        if (self::validator($id_matricula, $this->validate_id) !== true) {
            throw new Exception("El ID de la matrícula es inválido.");
        }
        if (self::validator($titulo, $this->validate_text_long) !== true) {
            throw new Exception("El título del certificado es inválido.");
        }
        if (!empty($descripcion) && self::validator($descripcion, $this->validate_description) !== true) {
            throw new Exception("La descripción es inválida.");
        }
        if (self::validator($categoria, $this->validate_text_long) !== true) {
            throw new Exception("La categoría es inválida.");
        }
        if (!empty($url_pdf) && strlen($url_pdf) > 512) {
            throw new Exception("La URL del PDF es muy larga.");
        }
        $estatus_validos = ['Pendiente de Emisión', 'Emitido', 'Anulado'];
        if (!in_array($estatus, $estatus_validos, true)) {
            throw new Exception("El estatus del certificado es inválido.");
        }
        $this->id_matricula = $id_matricula;
        $this->titulo_certificado = $titulo;
        $this->descripcion_certificado = empty($descripcion) ? null : $descripcion;
        $this->categoria_certificado = $categoria;
        $this->url_pdf_certificado = empty($url_pdf) ? null : $url_pdf;
        $this->estatus_certificado = $estatus;
        return true;
    }
    public function verificarDatosExistentes($id_matricula, $id_excluir = null)
    {
        try {
            $sql = "SELECT id_certificado, estatus_certificado FROM certificados WHERE id_matricula = :id_matricula";
            if ($id_excluir) {
                $sql .= " AND id_certificado != :id_excluir";
            }
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id_matricula', $id_matricula);
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
            ':titulo' => $this->titulo_certificado,
            ':descripcion' => $this->descripcion_certificado,
            ':categoria' => $this->categoria_certificado,
            ':url_pdf' => $this->url_pdf_certificado,
            ':estatus' => $this->estatus_certificado
        ];
    }
    public function guardar($data)
    {
        try {
            $this->validarDatos($data);
            $this->guardarDatos();
            return $this->success(201, "Certificado creado exitosamente.");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }
    private function guardarDatos()
    {
        try {
            $certificadoExistente = $this->verificarDatosExistentes($this->id_matricula);
            if ($certificadoExistente) {
                throw new Exception("Ya existe un certificado para esta matrícula.");
            }
            $sqlInsert = "INSERT INTO certificados (
                id_matricula, 
                titulo_certificado, 
                descripcion_certificado, 
                categoria_certificado, 
                url_pdf_certificado, 
                estatus_certificado
            ) VALUES (
                :id_matricula, :titulo, :descripcion, :categoria, :url_pdf, :estatus
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
            $sql = "SELECT c.*, 
                    CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) as nombre_estudiante,
                    cu.nombre_curso 
                    FROM certificados c
                    JOIN matriculas m ON c.id_matricula = m.id_matricula
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN cursos cu ON m.id_curso = cu.id_curso
                    WHERE c.estatus_certificado != 'Anulado'";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->success(200, "Certificados obtenidos", $result);
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        }
    }
    public function buscar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del certificado es inválido.");
            }
            $sql = "SELECT c.*, 
                    CONCAT(e.primer_nombre_estudiante, ' ', e.primer_apellido_estudiante) as nombre_estudiante,
                    cu.nombre_curso 
                    FROM certificados c
                    JOIN matriculas m ON c.id_matricula = m.id_matricula
                    JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                    JOIN cursos cu ON m.id_curso = cu.id_curso
                    WHERE c.id_certificado = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                return $this->success(200, "Certificado obtenido", $result);
            }
            return $this->error(404, "Certificado no encontrado");
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }
    public function actualizar($id, $data)
    {
        try {
            $data['id_certificado'] = $id;
            $this->validarDatos($data, true);
            $this->actualizarDatos();
            return $this->success(200, "Certificado actualizado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }
    private function actualizarDatos()
    {
        try {
            $certificadoExistente = $this->verificarDatosExistentes($this->id_matricula, $this->id_certificado);
            if ($certificadoExistente) {
                throw new Exception("Ya existe otro certificado registrado para esta matrícula.");
            }
            $sql = "UPDATE certificados SET 
                id_matricula = :id_matricula,
                titulo_certificado = :titulo,
                descripcion_certificado = :descripcion,
                categoria_certificado = :categoria,
                url_pdf_certificado = :url_pdf,
                estatus_certificado = :estatus
                WHERE id_certificado = :id";
            $stmt = $this->con->prepare($sql);
            $parametros = $this->obtenerParametros();
            $parametros[':id'] = $this->id_certificado;
            $stmt->execute($parametros);
        } catch (\PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }
    public function eliminar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del certificado es inválido.");
            }
            $this->id_certificado = $id;
            $this->eliminarDatos();
            return $this->success(200, "Certificado anulado correctamente");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }
    private function eliminarDatos()
    {
        try {
            $sql = "UPDATE certificados SET estatus_certificado = 'Anulado' WHERE id_certificado = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $this->id_certificado);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}
