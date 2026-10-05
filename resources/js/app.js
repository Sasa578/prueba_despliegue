import '../css/app.css';

/**
 * Agenda de Pagos — lógica de interfaz
 * - Autocompleta el monto total con el costo del servicio seleccionado.
 * - Agrega / elimina filas dinámicas de participantes.
 */

document.addEventListener('DOMContentLoaded', () => {
    // ---- Autocompletar monto según servicio ----
    const serviceSelect = document.getElementById('service_id');
    const montoInput = document.getElementById('monto_total');

    if (serviceSelect && montoInput) {
        const autocompletarMonto = () => {
            const option = serviceSelect.options[serviceSelect.selectedIndex];
            const costo = option?.dataset?.costo;

            if (costo) {
                montoInput.value = costo;
            }
        };

        serviceSelect.addEventListener('change', autocompletarMonto);

        // Si ya hay un servicio preseleccionado (p. ej. al venir del dashboard)
        if (serviceSelect.value && !montoInput.value) {
            autocompletarMonto();
        }
    }

    // ---- Filas dinámicas de participantes ----
    const container = document.getElementById('participants-container');
    const addButton = document.getElementById('add-participant');
    const template = document.getElementById('participant-template');

    if (container && addButton && template) {
        const renumerar = () => {
            container.querySelectorAll('.participant-row').forEach((row, index) => {
                row.querySelectorAll('select, input').forEach((campo) => {
                    campo.name = campo.name.replace(/participantes\[\d+\]/, `participantes[${index}]`);
                });
            });
        };

        addButton.addEventListener('click', () => {
            const fila = template.content.cloneNode(true);
            container.appendChild(fila);
            renumerar();
        });

        container.addEventListener('click', (evento) => {
            const boton = evento.target.closest('.btn-remove-row');

            if (boton) {
                boton.closest('.participant-row').remove();
                renumerar();
            }
        });
    }
});
