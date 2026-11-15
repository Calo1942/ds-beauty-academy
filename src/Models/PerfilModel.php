<?php

namespace DsBeautyAcademy\Models;

use Exception;
use DsBeautyAcademy\config\connect\DBConnect;
use DsBeautyAcademy\Helpers\ApiResponse;
use DsBeautyAcademy\Helpers\Validations;
use PDO;

class PerfilModel extends DBConnect
{
    use ApiResponse, Validations;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene el perfil completo del usuario autenticado
     */
    public function obtenerPerfilUsuario(int $idUsuario)
    {
        try {
            $stmt = $this->con->prepare("
                SELECT 
                    u.id_usuario,
                    u.email_usuario,
                    u.id_rol,
                    r.nombre_rol,
                    u.id_instructor,
                    u.id_estudiante,
                    u.foto_perfil_usuario,
                    u.fecha_creacion,
                    -- Datos si es instructor
                    i.primer_nombre_instructor,
                    i.segundo_nombre_instructor,
                    i.primer_apellido_instructor,
                    i.segundo_apellido_instructor,
                    i.cedula_instructor,
                    i.telefono_principal_instructor,
                    i.telefono_alternativo_instructor,
                    i.usuario_instagram_instructor,
                    -- Datos si es estudiante
                    e.primer_nombre_estudiante,
                    e.segundo_nombre_estudiante,
                    e.primer_apellido_estudiante,
                    e.segundo_apellido_estudiante,
                    e.cedula_estudiante,
                    e.telefono_principal_estudiante,
                    e.telefono_alternativo_estudiante,
                    e.usuario_instagram_estudiante
                FROM usuarios u
                INNER JOIN roles r ON u.id_rol = r.id_rol
                LEFT JOIN instructores i ON u.id_instructor = i.id_instructor
                LEFT JOIN estudiantes e ON u.id_estudiante = e.id_estudiante
                WHERE u.id_usuario = :id
                LIMIT 1
            ");
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return $this->error(404, "Usuario no encontrado");
            }

            // Normalizar datos de nombre, teléfono, cédula, instagram
            $nombre = '';
            $primerNombre = '';
            $primerApellido = '';
            $telefono = '';
            $telefonoAlt = '';
            $cedula = '';
            $instagram = '';
            $tipoPerfil = 'admin';

            if (!empty($usuario['id_instructor'])) {
                $tipoPerfil = 'instructor';
                $primerNombre = $usuario['primer_nombre_instructor'] ?? '';
                $primerApellido = $usuario['primer_apellido_instructor'] ?? '';
                $nombre = trim($primerNombre . ' ' . $primerApellido);
                $telefono = $usuario['telefono_principal_instructor'] ?? '';
                $telefonoAlt = $usuario['telefono_alternativo_instructor'] ?? '';
                $cedula = $usuario['cedula_instructor'] ?? '';
                $instagram = $usuario['usuario_instagram_instructor'] ?? '';
            } elseif (!empty($usuario['id_estudiante'])) {
                $tipoPerfil = 'estudiante';
                $primerNombre = $usuario['primer_nombre_estudiante'] ?? '';
                $primerApellido = $usuario['primer_apellido_estudiante'] ?? '';
                $nombre = trim($primerNombre . ' ' . $primerApellido);
                $telefono = $usuario['telefono_principal_estudiante'] ?? '';
                $telefonoAlt = $usuario['telefono_alternativo_estudiante'] ?? '';
                $cedula = $usuario['cedula_estudiante'] ?? '';
                $instagram = $usuario['usuario_instagram_estudiante'] ?? '';
            } else {
                $tipoPerfil = 'admin';
                $primerNombre = 'Administrador';
                $primerApellido = 'General';
                $nombre = $usuario['nombre_rol'] ?? 'Administrador';
                $telefono = '';
                $telefonoAlt = '';
            }

            // Formatear fecha de miembro
            $fechaTimestamp = !empty($usuario['fecha_creacion']) ? strtotime($usuario['fecha_creacion']) : time();
            $meses = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            $dia = date('j', $fechaTimestamp);
            $mes = $meses[(int)date('n', $fechaTimestamp)] ?? date('F', $fechaTimestamp);
            $ano = date('Y', $fechaTimestamp);
            $miembroDesde = "{$dia} de {$mes} de {$ano}";

            // 1. Obtener Especialidades reales
            $especialidades = $this->obtenerEspecialidades($usuario);

            // 2. Obtener Cursos reales (especialmente para estudiantes)
            $cursos = [];
            if (!empty($usuario['id_estudiante'])) {
                $cursos = $this->obtenerCursosEstudiante((int)$usuario['id_estudiante']);
            }

            // 3. Obtener Certificados / Diplomas reales
            $certificados = $this->obtenerCertificados($usuario);

            // 4. Obtener Resumen / Estadísticas reales
            $estadisticas = $this->obtenerEstadisticas($usuario);

            $data = [
                'id_usuario' => $usuario['id_usuario'],
                'nombre' => $nombre,
                'primer_nombre' => $primerNombre,
                'primer_apellido' => $primerApellido,
                'email' => $usuario['email_usuario'],
                'rol' => $usuario['nombre_rol'],
                'tipo_perfil' => $tipoPerfil,
                'telefono' => $telefono,
                'telefono_alternativo' => $telefonoAlt,
                'cedula' => $cedula,
                'instagram' => $instagram,
                'foto' => $usuario['foto_perfil_usuario'] ?? null,
                'miembro_desde' => $miembroDesde,
                'especialidades' => $especialidades,
                'cursos' => $cursos,
                'certificados' => $certificados,
                'estadisticas' => $estadisticas
            ];

            return $this->success(200, "Perfil obtenido", $data);
        } catch (Exception $e) {
            return $this->error(500, "Error al obtener perfil: " . $e->getMessage());
        }
    }

    /**
     * Obtiene los cursos reales en los que está matriculado un estudiante
     */
    public function obtenerCursosEstudiante(int $idEstudiante): array
    {
        try {
            $stmt = $this->con->prepare("
                SELECT 
                    c.id_curso,
                    c.nombre_curso,
                    c.tipo_curso,
                    m.id_matricula,
                    m.estatus_cupo,
                    m.estatus_pago,
                    DATE_FORMAT(c.fecha_inicio_curso, '%d/%m/%Y') AS fecha_inicio,
                    CONCAT(i.primer_nombre_instructor, ' ', i.primer_apellido_instructor) AS nombre_instructor
                FROM matriculas m
                INNER JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                INNER JOIN cursos c ON ic.id_curso = c.id_curso
                INNER JOIN instructores i ON ic.id_instructor = i.id_instructor
                WHERE m.id_estudiante = :id
                ORDER BY m.id_matricula DESC
            ");
            $stmt->bindValue(':id', $idEstudiante, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtiene las especialidades reales asignadas al usuario o del catálogo
     */
    private function obtenerEspecialidades(array $usuario): array
    {
        $especialidades = [];
        try {
            if (!empty($usuario['id_instructor'])) {
                $stmt = $this->con->prepare("
                    SELECT e.id_especialidad, e.nombre_especialidad
                    FROM especialidades e
                    INNER JOIN especialidad_instructor ei ON e.id_especialidad = ei.id_especialidad
                    WHERE ei.id_instructor = :id AND e.estatus_especialidad = 1
                    ORDER BY e.nombre_especialidad ASC
                ");
                $stmt->bindValue(':id', $usuario['id_instructor'], PDO::PARAM_INT);
                $stmt->execute();
                $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif (empty($usuario['id_estudiante'])) {
                // Administrador: catálogo de especialidades activas
                $stmt = $this->con->query("
                    SELECT id_especialidad, nombre_especialidad 
                    FROM especialidades 
                    WHERE estatus_especialidad = 1 
                    ORDER BY id_especialidad ASC
                ");
                $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return [];
        }

        // Asignar íconos correspondientes según el nombre de la especialidad
        foreach ($especialidades as &$esp) {
            $nom = mb_strtolower($esp['nombre_especialidad']);
            if (strpos($nom, 'ceja') !== false) {
                $esp['icono'] = 'fa-eye';
            } elseif (strpos($nom, 'pestañ') !== false) {
                $esp['icono'] = 'fa-feather-alt';
            } elseif (strpos($nom, 'micro') !== false || strpos($nom, 'pigment') !== false) {
                $esp['icono'] = 'fa-pen-fancy';
            } elseif (strpos($nom, 'uña') !== false || strpos($nom, 'manicur') !== false || strpos($nom, 'polygel') !== false) {
                $esp['icono'] = 'fa-magic';
            } elseif (strpos($nom, 'maquillaj') !== false) {
                $esp['icono'] = 'fa-paint-brush';
            } elseif (strpos($nom, 'barber') !== false) {
                $esp['icono'] = 'fa-cut';
            } elseif (strpos($nom, 'cosmetol') !== false || strpos($nom, 'facial') !== false || strpos($nom, 'piel') !== false) {
                $esp['icono'] = 'fa-spa';
            } elseif (strpos($nom, 'masaj') !== false) {
                $esp['icono'] = 'fa-hands';
            } elseif (strpos($nom, 'depila') !== false) {
                $esp['icono'] = 'fa-fire-alt';
            } elseif (strpos($nom, 'color') !== false || strpos($nom, 'capilar') !== false) {
                $esp['icono'] = 'fa-palette';
            } else {
                $esp['icono'] = 'fa-star';
            }
        }

        return $especialidades;
    }

    /**
     * Obtiene los certificados reales emitidos para estudiantes o diplomas para instructores
     */
    private function obtenerCertificados(array $usuario): array
    {
        $certificados = [];
        try {
            if (!empty($usuario['id_instructor'])) {
                $stmt = $this->con->prepare("
                    SELECT 
                        id_diploma AS id,
                        nombre_diploma AS titulo,
                        categoria_diploma AS categoria,
                        DATE_FORMAT(fecha_creacion, '%d/%m/%Y') AS fecha,
                        url_pdf_diploma AS url_pdf
                    FROM diplomas
                    WHERE id_instructor = :id AND estatus_diploma = 1
                    ORDER BY id_diploma DESC
                ");
                $stmt->bindValue(':id', $usuario['id_instructor'], PDO::PARAM_INT);
                $stmt->execute();
                $certificados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif (!empty($usuario['id_estudiante'])) {
                $stmt = $this->con->prepare("
                    SELECT 
                        c.id_certificado AS id,
                        c.titulo_certificado AS titulo,
                        c.categoria_certificado AS categoria,
                        DATE_FORMAT(c.fecha_creacion, '%d/%m/%Y') AS fecha,
                        c.url_pdf_certificado AS url_pdf
                    FROM certificados c
                    INNER JOIN matriculas m ON c.id_matricula = m.id_matricula
                    WHERE m.id_estudiante = :id AND c.estatus_certificado = 'Emitido'
                    ORDER BY c.id_certificado DESC
                ");
                $stmt->bindValue(':id', $usuario['id_estudiante'], PDO::PARAM_INT);
                $stmt->execute();
                $certificados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return [];
        }

        // Devolver únicamente los registros reales sin inventar certificados simulados
        return $certificados;
    }

    /**
     * Calcula estadísticas reales según el rol del usuario
     */
    private function obtenerEstadisticas(array $usuario): array
    {
        $stats = [
            'item1_titulo' => 'Cursos',
            'item1_valor' => '0',
            'item1_icono' => 'fa-graduation-cap',
            'item2_titulo' => 'Estudiantes',
            'item2_valor' => '0',
            'item2_icono' => 'fa-users',
            'item3_titulo' => 'Acreditaciones',
            'item3_valor' => '0',
            'item3_icono' => 'fa-certificate'
        ];

        try {
            if (!empty($usuario['id_instructor'])) {
                $idInst = (int)$usuario['id_instructor'];

                // Conteo cursos asignados
                $stmt = $this->con->prepare("SELECT COUNT(*) FROM instructor_curso WHERE id_instructor = :id");
                $stmt->bindValue(':id', $idInst, PDO::PARAM_INT);
                $stmt->execute();
                $cursosCount = (int)$stmt->fetchColumn();

                // Conteo estudiantes atendidos
                $stmt = $this->con->prepare("
                    SELECT COUNT(DISTINCT m.id_estudiante) 
                    FROM matriculas m
                    INNER JOIN instructor_curso ic ON m.id_instructor_curso = ic.id_instructor_curso
                    WHERE ic.id_instructor = :id
                ");
                $stmt->bindValue(':id', $idInst, PDO::PARAM_INT);
                $stmt->execute();
                $estudiantesCount = (int)$stmt->fetchColumn();

                // Conteo diplomas
                $stmt = $this->con->prepare("SELECT COUNT(*) FROM diplomas WHERE id_instructor = :id AND estatus_diploma = 1");
                $stmt->bindValue(':id', $idInst, PDO::PARAM_INT);
                $stmt->execute();
                $diplomasCount = (int)$stmt->fetchColumn();

                $stats['item1_titulo'] = 'Cursos Impartidos';
                $stats['item1_valor'] = (string)$cursosCount;
                $stats['item2_titulo'] = 'Estudiantes Capacitados';
                $stats['item2_valor'] = (string)$estudiantesCount;
                $stats['item3_titulo'] = 'Acreditaciones';
                $stats['item3_valor'] = (string)$diplomasCount;
            } elseif (!empty($usuario['id_estudiante'])) {
                $idEst = (int)$usuario['id_estudiante'];

                // Conteo matrículas / cursos inscritos
                $stmt = $this->con->prepare("SELECT COUNT(*) FROM matriculas WHERE id_estudiante = :id AND estatus_cupo IN ('Reservado', 'Confirmado')");
                $stmt->bindValue(':id', $idEst, PDO::PARAM_INT);
                $stmt->execute();
                $cursosInscritos = (int)$stmt->fetchColumn();

                // Conteo certificados emitidos
                $stmt = $this->con->prepare("
                    SELECT COUNT(*) 
                    FROM certificados c
                    INNER JOIN matriculas m ON c.id_matricula = m.id_matricula
                    WHERE m.id_estudiante = :id AND c.estatus_certificado = 'Emitido'
                ");
                $stmt->bindValue(':id', $idEst, PDO::PARAM_INT);
                $stmt->execute();
                $certCount = (int)$stmt->fetchColumn();

                $stats['item1_titulo'] = 'Cursos Inscritos';
                $stats['item1_valor'] = (string)$cursosInscritos;
                $stats['item2_titulo'] = 'Certificados Logrados';
                $stats['item2_valor'] = (string)$certCount;
                $stats['item3_titulo'] = 'Estado Académico';
                $stats['item3_valor'] = $cursosInscritos > 0 ? 'Activo' : 'Nuevo';
            } else {
                // Admin general
                $stmt = $this->con->query("SELECT COUNT(*) FROM cursos WHERE estatus_curso != 'Borrador'");
                $cursosCount = (int)$stmt->fetchColumn();

                $stmt = $this->con->query("SELECT COUNT(*) FROM estudiantes WHERE estatus_estudiante = 1");
                $estudiantesCount = (int)$stmt->fetchColumn();

                $stmt = $this->con->query("SELECT COUNT(*) FROM certificados WHERE estatus_certificado = 'Emitido'");
                $certCount = (int)$stmt->fetchColumn();

                $stats['item1_titulo'] = 'Cursos Activos';
                $stats['item1_valor'] = (string)$cursosCount;
                $stats['item2_titulo'] = 'Comunidad DS';
                $stats['item2_valor'] = (string)$estudiantesCount;
                $stats['item3_titulo'] = 'Certificados Emitidos';
                $stats['item3_valor'] = (string)$certCount;
            }
        } catch (Exception $e) {
            // Mantener valores por defecto
        }

        return $stats;
    }

    /**
     * Actualiza datos de contacto o contraseña del perfil
     */
    public function actualizarPerfil(int $idUsuario, array $data)
    {
        try {
            $stmt = $this->con->prepare("SELECT * FROM usuarios WHERE id_usuario = :id LIMIT 1");
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return $this->error(404, "Usuario no encontrado");
            }

            // 1. Si se pasa nueva contraseña
            if (!empty($data['password_usuario'])) {
                $pass = $data['password_usuario'];
                if (strlen($pass) < 6) {
                    return $this->error(400, "La contraseña debe tener al menos 6 caracteres");
                }
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $stmtPass = $this->con->prepare("UPDATE usuarios SET password_usuario = :pass WHERE id_usuario = :id");
                $stmtPass->bindValue(':pass', $hash, PDO::PARAM_STR);
                $stmtPass->bindValue(':id', $idUsuario, PDO::PARAM_INT);
                $stmtPass->execute();
            }

            // 2. Normalizar campos
            $primerNombre = trim($data['primer_nombre'] ?? '');
            $primerApellido = trim($data['primer_apellido'] ?? '');
            $telefono = trim($data['telefono'] ?? '');
            $telefonoAlt = trim($data['telefono_alternativo'] ?? '');
            $rawInstagram = trim($data['instagram'] ?? '');
            $instagram = !empty($rawInstagram) ? ltrim($rawInstagram, '@') : null;

            $nombreCompleto = '';

            if (!empty($usuario['id_instructor'])) {
                // Actualizar datos del instructor
                $updates = [];
                $params = [':id' => $usuario['id_instructor']];

                if ($primerNombre !== '') {
                    $updates[] = "primer_nombre_instructor = :pnom";
                    $params[':pnom'] = $primerNombre;
                }
                if ($primerApellido !== '') {
                    $updates[] = "primer_apellido_instructor = :pape";
                    $params[':pape'] = $primerApellido;
                }
                $updates[] = "telefono_principal_instructor = :tel";
                $params[':tel'] = $telefono;

                $updates[] = "telefono_alternativo_instructor = :telalt";
                $params[':telalt'] = !empty($telefonoAlt) ? $telefonoAlt : null;

                $updates[] = "usuario_instagram_instructor = :ig";
                $params[':ig'] = $instagram;

                $sql = "UPDATE instructores SET " . implode(', ', $updates) . " WHERE id_instructor = :id";
                $stmtInst = $this->con->prepare($sql);
                foreach ($params as $param => $val) {
                    if ($val === null) {
                        $stmtInst->bindValue($param, null, PDO::PARAM_NULL);
                    } else {
                        $stmtInst->bindValue($param, $val, PDO::PARAM_STR);
                    }
                }
                $stmtInst->execute();

                // Obtener nombre resultante para actualizar la sesión
                $stmtNom = $this->con->prepare("SELECT primer_nombre_instructor, primer_apellido_instructor FROM instructores WHERE id_instructor = :id");
                $stmtNom->bindValue(':id', $usuario['id_instructor'], PDO::PARAM_INT);
                $stmtNom->execute();
                $resNom = $stmtNom->fetch(PDO::FETCH_ASSOC);
                $nombreCompleto = trim(($resNom['primer_nombre_instructor'] ?? '') . ' ' . ($resNom['primer_apellido_instructor'] ?? ''));

            } elseif (!empty($usuario['id_estudiante'])) {
                // Actualizar datos del estudiante
                $updates = [];
                $params = [':id' => $usuario['id_estudiante']];

                if ($primerNombre !== '') {
                    $updates[] = "primer_nombre_estudiante = :pnom";
                    $params[':pnom'] = $primerNombre;
                }
                if ($primerApellido !== '') {
                    $updates[] = "primer_apellido_estudiante = :pape";
                    $params[':pape'] = $primerApellido;
                }
                $updates[] = "telefono_principal_estudiante = :tel";
                $params[':tel'] = $telefono;

                $updates[] = "telefono_alternativo_estudiante = :telalt";
                $params[':telalt'] = !empty($telefonoAlt) ? $telefonoAlt : null;

                $updates[] = "usuario_instagram_estudiante = :ig";
                $params[':ig'] = $instagram;

                $sql = "UPDATE estudiantes SET " . implode(', ', $updates) . " WHERE id_estudiante = :id";
                $stmtEst = $this->con->prepare($sql);
                foreach ($params as $param => $val) {
                    if ($val === null) {
                        $stmtEst->bindValue($param, null, PDO::PARAM_NULL);
                    } else {
                        $stmtEst->bindValue($param, $val, PDO::PARAM_STR);
                    }
                }
                $stmtEst->execute();

                // Obtener nombre resultante para actualizar la sesión
                $stmtNom = $this->con->prepare("SELECT primer_nombre_estudiante, primer_apellido_estudiante FROM estudiantes WHERE id_estudiante = :id");
                $stmtNom->bindValue(':id', $usuario['id_estudiante'], PDO::PARAM_INT);
                $stmtNom->execute();
                $resNom = $stmtNom->fetch(PDO::FETCH_ASSOC);
                $nombreCompleto = trim(($resNom['primer_nombre_estudiante'] ?? '') . ' ' . ($resNom['primer_apellido_estudiante'] ?? ''));

            } else {
                $nombreCompleto = 'Administrador General';
            }

            return $this->success(200, "Perfil actualizado con éxito", [
                'nombre' => $nombreCompleto
            ]);
        } catch (Exception $e) {
            return $this->error(500, "Error al actualizar perfil: " . $e->getMessage());
        }
    }

    /**
     * Guarda la ruta de la nueva foto de perfil
     */
    public function actualizarFotoPerfil(int $idUsuario, string $rutaFoto)
    {
        try {
            $stmt = $this->con->prepare("UPDATE usuarios SET foto_perfil_usuario = :foto WHERE id_usuario = :id");
            $stmt->bindValue(':foto', $rutaFoto, PDO::PARAM_STR);
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            return $this->success(200, "Foto de perfil actualizada correctamente", [
                'foto_url' => $rutaFoto
            ]);
        } catch (Exception $e) {
            return $this->error(500, "Error al actualizar la foto de perfil: " . $e->getMessage());
        }
    }

    /**
     * Elimina la foto de perfil y el archivo físico del servidor
     */
    public function eliminarFotoPerfil(int $idUsuario)
    {
        try {
            // Consultar foto actual
            $stmt = $this->con->prepare("SELECT foto_perfil_usuario FROM usuarios WHERE id_usuario = :id LIMIT 1");
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $fotoActual = $stmt->fetchColumn();

            if (!empty($fotoActual)) {
                $rutaFisica = __DIR__ . '/../../' . ltrim($fotoActual, '/');
                if (file_exists($rutaFisica) && is_file($rutaFisica)) {
                    @unlink($rutaFisica);
                }
            }

            // Poner en NULL la columna
            $stmtUpdate = $this->con->prepare("UPDATE usuarios SET foto_perfil_usuario = NULL WHERE id_usuario = :id");
            $stmtUpdate->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmtUpdate->execute();

            return $this->success(200, "Foto de perfil eliminada exitosamente");
        } catch (Exception $e) {
            return $this->error(500, "Error al eliminar la foto de perfil: " . $e->getMessage());
        }
    }
}
