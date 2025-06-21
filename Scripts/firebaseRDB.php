<?php
/*
 * class name: MySQLCursoDB
 * version: 1.0
 * author: Adaptado de firebaseRDB por OpenAI
 */

class MySQLCursoDB {
    private $conn;

    function __construct($host, $usuario, $contrasena, $basededatos) {
        $this->conn = new mysqli($host, $usuario, $contrasena, $basededatos);
        if ($this->conn->connect_error) {
            throw new Exception("❌ Error de conexión: " . $this->conn->connect_error);
        }
    }

    // Insertar curso
    public function insert($data) {
        $sql = "INSERT INTO cursos (id_firebase, nombre_curso, descripcion, imagen, profesor_id)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", 
            $data['id_firebase'], 
            $data['nombre_curso'], 
            $data['descripcion'], 
            $data['imagen'], 
            $data['profesor_id']
        );

        if ($stmt->execute()) {
            return "✅ Curso insertado con ID: " . $stmt->insert_id;
        } else {
            return "❌ Error al insertar: " . $stmt->error;
        }
    }

    // Actualizar curso
    public function update($id, $data) {
        $sql = "UPDATE cursos SET nombre_curso = ?, descripcion = ?, imagen = ?, profesor_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssii", 
            $data['nombre_curso'], 
            $data['descripcion'], 
            $data['imagen'], 
            $data['profesor_id'], 
            $id
        );

        if ($stmt->execute()) {
            return "✅ Curso actualizado correctamente.";
        } else {
            return "❌ Error al actualizar: " . $stmt->error;
        }
    }

    // Eliminar curso
    public function delete($id) {
        $sql = "DELETE FROM cursos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return "✅ Curso eliminado correctamente.";
        } else {
            return "❌ Error al eliminar: " . $stmt->error;
        }
    }

    // Obtener un curso por ID
    public function get($id) {
        $sql = "SELECT * FROM cursos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    // Buscar cursos con LIKE por nombre
    public function retrieve($campo, $valor) {
        $sql = "SELECT * FROM cursos WHERE $campo LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $like = "%" . $valor . "%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function cerrarConexion() {
        $this->conn->close();
    }
}
