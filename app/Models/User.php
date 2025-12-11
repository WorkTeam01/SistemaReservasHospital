<?php

namespace App\Models;

use App\Core\Model;

/**
 * Modelo User - Gestión de usuarios del sistema
 */
class User extends Model
{
    /**
     * Busca un usuario por su email
     * Solo retorna usuarios activos (is_active = 1)
     * 
     * @param string $email Email del usuario
     * @return array|false Datos del usuario o false si no existe
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        return $this->query($sql, ['email' => $email])->fetch();
    }

    /**
     * Busca un usuario por su email (incluyendo inactivos)
     * Usado para dar mensajes específicos a usuarios desactivados
     * 
     * @param string $email Email del usuario
     * @return array|false Datos del usuario o false si no existe
     */
    public function findByEmailIncludingInactive($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->query($sql, ['email' => $email])->fetch();
    }

    /**
     * Busca un usuario por su ID
     * 
     * @param int $userId ID del usuario
     * @return array|false Datos del usuario o false si no existe
     */
    public function findById($userId)
    {
        $sql = "SELECT * FROM users WHERE user_id = :user_id AND is_active = 1";
        return $this->query($sql, ['user_id' => $userId])->fetch();
    }
}
