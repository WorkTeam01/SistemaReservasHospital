<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * Modelo Dashboard
 * Obtiene estadísticas y datos para el dashboard del administrador
 */
class Dashboard extends Model
{
    // No es necesario definir $table aquí porque este modelo
    // usa queries personalizadas, no CRUD genérico

    /**
     * Obtiene el total de usuarios activos
     * 
     * @return int
     */
    public function getTotalUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE is_active = 1";
        $stmt = $this->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Obtiene el total de pacientes activos
     * 
     * @return int
     */
    public function getTotalPatients()
    {
        $sql = "SELECT COUNT(*) as total FROM patients WHERE is_active = 1";
        $stmt = $this->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Obtiene el total de citas pendientes
     * 
     * @return int
     */
    public function getPendingAppointments()
    {
        $sql = "SELECT COUNT(*) as total FROM appointments WHERE status = 'pending'";
        $stmt = $this->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Obtiene el total de citas del día actual
     * 
     * @return int
     */
    public function getTodayAppointments()
    {
        $sql = "SELECT COUNT(*) as total FROM appointments 
                WHERE DATE(date_time) = CURDATE()";
        $stmt = $this->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Obtiene las próximas citas (límite de 5)
     * 
     * @param int $limit
     * @return array
     */
    public function getUpcomingAppointments($limit = 5)
    {
        $sql = "SELECT 
                    a.appointment_id,
                    a.date_time,
                    a.status,
                    CONCAT(p.name, ' ', p.last_name) as patient_name,
                    CONCAT(u.name, ' ', u.last_name) as doctor_name,
                    s.name as specialty_name
                FROM appointments a
                INNER JOIN patients p ON a.patient_id = p.patient_id
                INNER JOIN users u ON a.doctor_id = u.user_id
                LEFT JOIN specialties s ON u.specialty_id = s.specialty_id
                WHERE a.date_time >= NOW()
                ORDER BY a.date_time ASC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
