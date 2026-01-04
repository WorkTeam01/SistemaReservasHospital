<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Specialty extends Model
{
    protected $table = 'specialties';
    protected $primaryKey = 'specialty_id';

    /**
     * Obtiene todas las especialidades activas (is_active = 1)
     */
    public function getActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca si existe una especialidad con el mismo nombre (para validación)
     * Ignora mayúsculas/minúsculas
     */
    public function findByName($name)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name = :name AND is_active = 1 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
