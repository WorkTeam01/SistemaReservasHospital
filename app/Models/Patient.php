<?php

namespace App\Models;

use App\Core\Model;

/**
 * Modelo Patient
 * Gestiona los pacientes del sistema
 */
class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'patient_id';

    /**
     * Obtiene todos los pacientes activos
     * 
     * @return array
     */
    public function getActivePatients()
    {
        return $this->where(['is_active' => 1]);
    }

    /**
     * Busca un paciente por email
     * 
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email)
    {
        return $this->findWhere(['email' => $email]);
    }

    /**
     * Busca un paciente por DNI
     * 
     * @param string $dni
     * @return array|false
     */
    public function findByDni($dni)
    {
        return $this->findWhere(['dni' => $dni]);
    }
}
