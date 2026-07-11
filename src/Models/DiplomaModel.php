<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\config\interfaces\Crud;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;

class DiplomaModel extends DBConnect implements Crud
{
    use ApiResponse, Validations;

    private $id_diploma;
    private $nombre_diploma;
    private $descripcion_diploma;
    private $categoria_diploma;
    private $url_pdf_diploma;
    private $id_instructor;
    private $estatus_diploma;

    private function validarDatos($data, $isUpdate = false)
    {
        $id = $data['id_diploma'] ?? null;
        $nombre = $data['nombre_diploma'] ?? null;
        $descripcion = $data['descripcion_diploma'] ?? null;
        $categoria = $data['categoria_diploma'] ?? null;
        $url_pdf = $data['url_pdf_diploma'] ?? null;
        $id_instructor = $data['id_instructor'] ?? null;
        $estatus = $data['estatus_diploma'] ?? 1;

        if ($isUpdate || $id !== null) {
            if ($this->validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del diploma es inválido.");
            }
            $this->id_diploma = $id;
        }

        if (self::validator($nombre, $this->validate_text_long) !== true) {
            throw new Exception("El nombre del Diploma.");
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

        if (self::validator($id_instructor, $this->validate_id) !== true) {
            throw new Exception("El ID del instructor es inválido.");
        }

        $estatus = $this->parseBoolean($estatus);

        if (!in_array($estatus, $this->validate_boolean, true)) {
            throw new Exception("El estatus del diploma es inválido.");
        }

        $this->nombre_diploma = $nombre;
        $this->descripcion_diploma = empty($descripcion) ? null : $descripcion;
        $this->categoria_diploma = $categoria;
        $this->url_pdf_diploma = empty($url_pdf) ? null : $url_pdf;
        $this->id_instructor = $id_instructor;
        $this->estatus_diploma = $estatus;

        return true;
    }

    private function obtenerParametros()
    {
        return [
            ':nombre' => $this->nombre_diploma,
            ':descripcion' => $this->descripcion_diploma,
            ':categoria' => $this->categoria_diploma,
            ':url_pdf' => $this->url_pdf_diploma,
            ':id_instructor' => $this->id_instructor,
            ':estatus' => $this->estatus_diploma
        ];
    }

    public function guardar($data)
    {
        try {
            $this->validarDatos($data);
            $this->guardarDatos();
            return $this->success(201, "Diploma creado exitosamente.");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function guardarDatos()
    {
        try {
            $sqlInsert = "INSERT INTO diplomas (
                nombre_diploma, 
                descripcion_diploma, 
                categoria_diploma, 
                url_pdf_diploma, 
                id_instructor, 
                estatus_diploma
            ) VALUES (
                :nombre, :descripcion, :categoria, :url_pdf, :id_instructor, :estatus
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
            $sql = "SELECT c.*, CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) as nombre_instructor 
                    FROM diplomas c
                    JOIN instructores i ON c.id_instructor = i.id_instructor
                    WHERE c.estatus_diploma = 1";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->success(200, "Diplomas obtenidos", $result);
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        }
    }

    public function buscar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del diploma es inválido.");
            }

            $sql = "SELECT c.*, CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) as nombre_instructor 
                    FROM diplomas c
                    JOIN instructores i ON c.id_instructor = i.id_instructor
                    WHERE c.id_diploma = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                return $this->success(200, "Diploma obtenido", $result);
            }
            return $this->error(404, "Diploma");
        } catch (\PDOException $e) {
            return $this->error(400, "Error de base de datos", $e->getMessage());
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $data['id_diploma'] = $id;
            $this->validarDatos($data, true);
            $this->actualizarDatos();
            return $this->success(200, "Diploma actualizado");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function actualizarDatos()
    {
        try {
            $sql = "UPDATE diplomas SET 
                nombre_diploma = :nombre,
                descripcion_diploma = :descripcion,
                categoria_diploma = :categoria,
                url_pdf_diploma = :url_pdf,
                id_instructor = :id_instructor,
                estatus_diploma = :estatus
                WHERE id_diploma = :id";

            $stmt = $this->con->prepare($sql);

            $parametros = $this->obtenerParametros();
            $parametros[':id'] = $this->id_diploma;
            $stmt->execute($parametros);
        } catch (\PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            if (self::validator($id, $this->validate_id) !== true) {
                throw new Exception("El ID del diploma es inválido.");
            }
            $this->id_diploma = $id;
            $this->eliminarDatos();
            return $this->success(200, "Diploma eliminado");
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }
    }

    private function eliminarDatos()
    {
        try {
            $sql = "UPDATE diplomas SET estatus_diploma = 0 WHERE id_diploma = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':id', $this->id_diploma);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}
