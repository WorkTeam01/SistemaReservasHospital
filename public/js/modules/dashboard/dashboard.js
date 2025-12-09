/**
 * Dashboard Module - JavaScript
 * Funciones específicas para el dashboard
 */

$(document).ready(function () {
    // Animar contadores al cargar
    animateCounters();

    // Actualizar hora actual
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);

    // Inicializar tooltips si existen
    if (typeof $.fn.tooltip !== 'undefined') {
        $('[data-toggle="tooltip"]').tooltip();
    }
});

/**
 * Anima los números en las tarjetas de estadísticas
 */
function animateCounters() {
    $('.info-box-number').each(function (index) {
        const $this = $(this);
        const countTo = parseInt($this.text());

        if (!isNaN(countTo) && countTo > 0) {
            // Resetear a 0 primero
            $this.text('0');

            // Delay escalonado para cada contador
            setTimeout(function () {
                $({ countNum: 0 }).animate({
                    countNum: countTo
                }, {
                    duration: 1500,
                    easing: 'swing',
                    step: function () {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function () {
                        $this.text(countTo);
                    }
                });
            }, index * 150);
        }
    });
}

/**
 * Actualiza la hora actual en el dashboard
 */
function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('es-ES');
    const dateString = now.toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    $('#current-time').text(timeString);
    $('#current-date').text(dateString);
}

/**
 * Actualiza las tarjetas de estadísticas
 */
function updateStatsCards(data) {
    $('#total-users').text(data.totalUsers || 0);
    $('#total-patients').text(data.totalPatients || 0);
    $('#pending-appointments').text(data.pendingAppointments || 0);
    $('#today-appointments').text(data.todayAppointments || 0);
}
