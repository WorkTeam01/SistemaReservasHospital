<?php

namespace App\Core;

use PDOException;
use PDO;
use App\Config\Database;

/**
 * Clase Base Model
 * Maneja la conexión a base de datos y provee métodos CRUD genéricos.
 */
abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        // Obtener la conexión del Singleton
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ejecuta una consulta preparada de forma segura.
     * 
     * @param string $sql Consulta SQL
     * @param array $params Parámetros para bind
     * @return \PDOStatement
     */
    protected function query($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Error DB: " . $e->getMessage());
        }
    }

    /**
     * Obtiene todos los registros de la tabla.
     * 
     * @return array
     */
    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un registro por su ID.
     * 
     * @param int $id ID del registro
     * @return array|false
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo registro.
     * 
     * @param array $data Datos del registro
     * @return bool
     */
    public function create($data)
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Actualiza un registro existente.
     * 
     * @param int $id ID del registro
     * @param array $data Datos a actualizar
     * @return bool
     */
    public function update($id, $data)
    {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ', ');

        $sql = "UPDATE {$this->table} SET $fields WHERE {$this->primaryKey} = :id";
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Elimina un registro.
     * 
     * @param int $id ID del registro
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Cuenta el total de registros en la tabla.
     * 
     * @return int
     */
    public function count()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table}");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Busca registros con condiciones WHERE.
     * 
     * @param array $conditions Condiciones ['campo' => 'valor']
     * @return array
     */
    public function where($conditions)
    {
        $fields = '';
        foreach ($conditions as $key => $value) {
            $fields .= "$key = :$key AND ";
        }
        $fields = rtrim($fields, ' AND ');

        $sql = "SELECT * FROM {$this->table} WHERE $fields";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca el primer registro que cumpla las condiciones.
     * 
     * @param array $conditions Condiciones ['campo' => 'valor']
     * @return array|false
     */
    public function findWhere($conditions)
    {
        $fields = '';
        foreach ($conditions as $key => $value) {
            $fields .= "$key = :$key AND ";
        }
        $fields = rtrim($fields, ' AND ');

        $sql = "SELECT * FROM {$this->table} WHERE $fields LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
