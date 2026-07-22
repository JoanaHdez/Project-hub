document.addEventListener('DOMContentLoaded', () => {

    const vistaSistemas = document.querySelector('[data-vista-sistemas]');
    const vistaModulos = document.querySelector('[data-vista-modulos]');
    const botonVolver = document.querySelector('[data-volver-sistemas]');
    const contenedorModulos = document.querySelector('[data-contenedor-modulos]');
    const tituloSistema = document.querySelector('[data-sistema-titulo]');
    const descripcionSistema = document.querySelector('[data-sistema-descripcion]');
    const totalModulos = document.querySelector('[data-total-modulos]');
    const proyectoSistema = document.querySelector('.explorador-modulos__proyecto');

    const sistemas = {
        extorsion: {
            titulo: 'Registro de Pláticas',
            proyecto: 'Proyecto Extorsión',
            descripcion: 'Explora las pantallas internas del sistema de registro de pláticas.',
            modulos: [
                {
                    nombre: 'Formulario principal',
                    tipo: 'Registro',
                    descripcion: 'Captura y validación de participantes.'
                },
                {
                    nombre: 'Dashboard',
                    tipo: 'Reportes',
                    descripcion: 'Consulta de registros e indicadores.'
                },
                {
                    nombre: 'Control móvil',
                    tipo: 'Operativo',
                    descripcion: 'Herramienta de control y validación desde celular.'
                },
                {
                    nombre: 'Constancias',
                    tipo: 'Documentos',
                    descripcion: 'Consulta y descarga de constancias.'
                }
            ]
        },

        eventos: {
            titulo: 'Sistema de Eventos',
            proyecto: 'Proyecto Eventos',
            descripcion: 'Explora los módulos utilizados en congresos y eventos.',
            modulos: [
                {
                    nombre: '8.º Congreso',
                    tipo: 'Micrositio',
                    descripcion: 'Sitio principal del congreso.'
                },
                {
                    nombre: 'Plantilla informativa',
                    tipo: 'Plantilla',
                    descripcion: 'Estructura reutilizable para congresos.'
                },
                {
                    nombre: 'Cuestionario',
                    tipo: 'Formulario',
                    descripcion: 'Captura de respuestas de participantes.'
                }
            ]
        },

        optica: {
            titulo: 'Gestión Óptica',
            proyecto: 'Proyecto Óptica',
            descripcion: 'Explora las funcionalidades internas del sistema de óptica.',
            modulos: [
                {
                    nombre: 'Clientes',
                    tipo: 'Catálogo',
                    descripcion: 'Consulta y administración de clientes.'
                },
                {
                    nombre: 'Historial clínico',
                    tipo: 'Clínico',
                    descripcion: 'Registro de antecedentes y evaluaciones.'
                },
                {
                    nombre: 'Nueva venta',
                    tipo: 'Comercial',
                    descripcion: 'Captura de ventas y productos.'
                },
                {
                    nombre: 'Historial de ventas',
                    tipo: 'Consulta',
                    descripcion: 'Seguimiento de ventas registradas.'
                }
            ]
        }
    };

    const crearTarjetaModulo = (modulo) => {

        return `
            <article class="modulo-card">

                <div class="modulo-card__imagen">
                    Vista previa
                </div>

                <div class="modulo-card__contenido">

                    <span class="modulo-card__tipo">
                        ${modulo.tipo}
                    </span>

                    <h3>
                        ${modulo.nombre}
                    </h3>

                    <p>
                        ${modulo.descripcion}
                    </p>

                    <div class="modulo-card__acciones">

                        <button
                            type="button"
                            class="modulo-card__detalle"
                        >
                            Ver ficha
                        </button>

                        <a
                            href="#"
                            class="modulo-card__abrir"
                        >
                            Abrir
                        </a>

                    </div>

                </div>

            </article>
        `;
    };

    const mostrarModulos = (claveSistema) => {

        const sistema = sistemas[claveSistema];

        if (!sistema) {
            return;
        }

        tituloSistema.textContent = sistema.titulo;
        proyectoSistema.textContent = sistema.proyecto;
        descripcionSistema.textContent = sistema.descripcion;
        totalModulos.textContent = sistema.modulos.length;

        contenedorModulos.innerHTML = sistema.modulos
            .map(crearTarjetaModulo)
            .join('');

        vistaSistemas.hidden = true;
        vistaModulos.hidden = false;

        vistaModulos.classList.remove('modulos-entrada');
        void vistaModulos.offsetWidth;
        vistaModulos.classList.add('modulos-entrada');
    };

    document.querySelectorAll('.sistema-card').forEach((tarjeta) => {

    const abrirSistema = () => {

        mostrarModulos(tarjeta.dataset.sistema);

    };

    tarjeta.addEventListener('click', abrirSistema);

    tarjeta.addEventListener('keydown', (evento) => {

        if (evento.key === 'Enter' || evento.key === ' ') {

            evento.preventDefault();

            abrirSistema();

        }

    });

});

    botonVolver?.addEventListener('click', () => {

        vistaModulos.hidden = true;
        vistaSistemas.hidden = false;

        vistaSistemas.classList.remove('modulos-entrada');
        void vistaSistemas.offsetWidth;
        vistaSistemas.classList.add('modulos-entrada');
    });

});