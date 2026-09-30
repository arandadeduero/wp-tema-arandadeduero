<?php

/**
 * Template Name: Barrios, Propuestas y Respuestas
 *
 * Página del proceso de escucha vecinal "Barrios, propuestas y respuestas",
 * con el resumen de las reuniones celebradas en cada barrio de Aranda de Duero.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();

$header_image = wp_get_attachment_url(get_theme_mod('aranda_de_duero_default_header_image'));
if (get_field('cabecera_de_pagina')) {
    $header_image = get_field('cabecera_de_pagina');
}

$barrios = array(
    array(
        'nombre' => 'La Aguilera',
        'fecha' => '5 de mayo de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/La-Aguilera.-Reunión-con-desarrollo.pdf',
    ),
    array(
        'nombre' => 'Costaján',
        'fecha' => '12 de mayo de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/07/02.-Costajan.-Resumen-Reunion-2.pdf',
    ),
    array(
        'nombre' => 'Sinovas',
        'fecha' => '19 de mayo de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/07/03.-Sinovas.-Resumen-Reunion-3.pdf',
    ),
    array(
        'nombre' => 'La Calabaza',
        'fecha' => '2 de junio de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/07/04.-La-Calabaza.-Resumen-reunion-2-1.pdf',
    ),
    array(
        'nombre' => 'La Estación',
        'fecha' => '9 de junio de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/La-Estación.-Resumen-reunión-09-06-2026.pdf',
    ),
    array(
        'nombre' => 'El Polígono',
        'fecha' => '18 de junio de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/El-Polígono.-Resumen-reunión-18-06-2026.pdf',
    ),
    array(
        'nombre' => 'Allendeduero',
        'fecha' => '30 de junio de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/Allendeduero.-Resumen-reunión-30-06-2026.pdf',
    ),
    array(
        'nombre' => 'Tenerías',
        'fecha' => '7 de julio de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/Tenerías.-Resumen-reunión-07-07-2026-1.pdf',
    ),
    array(
        'nombre' => 'Zona Centro',
        'fecha' => '14 de julio de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/Zona-Centro.-Resumen-reunión-14-07-2026.pdf',
    ),
    array(
        'nombre' => 'Santa Catalina',
        'fecha' => '21 de julio de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/Santa-Catalina.-Resumen-reunión-21-07-2026-1.pdf',
    ),
    array(
        'nombre' => 'Ferial-Bañuelos',
        'fecha' => '4 de agosto de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/Ferial-Bañuelos.-Resumen-reunión-04-08-2026-1.pdf',
    ),
    array(
        'nombre' => 'Las Casitas',
        'fecha' => '4 de agosto de 2026',
        'pdf' => 'https://www.arandadeduero.es/wp-content/uploads/2026/10/Las-Casitas.-Resumen-reunión-11-08-2026-1.pdf',
    ),
);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 p-0">
            <img src="<?php echo $header_image; ?>" class="img-fluid w-100 cabecera_pagina" alt="<?php echo $header_image; ?>" />
        </div>
    </div>
</div>

<div class="container py-5">

    <!-- Cabecera -->
    <div class="bg-light p-4 p-md-5 rounded-3 mb-5">
        <header class="text-center mb-0">
            <h1 class="display-5 fw-bold mb-3">Barrios, propuestas y respuestas</h1>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p class="lead text-muted mb-0">
                        Proceso de escucha por todas las asociaciones de vecinos de Aranda de Duero, en la primavera y el verano de 2026, por parte del alcalde Antonio Linaje, para recoger las propuestas de los vecinos y vecinas de la ciudad.
                    </p>
                </div>
            </div>
        </header>
    </div>

    <!-- Nota de Prensa -->
    <section class="mb-5">
        <h2 class="h3 fw-bold border-bottom pb-2 mb-3">Nota de Prensa</h2>
        <div class="card shadow-sm border-primary position-relative">
            <div class="card-body text-center p-4">
                <h3 class="h5 card-title mb-2">
                    <a href="https://www.arandadeduero.es/finaliza-la-ronda-de-reuniones-vecinales-de-barrios-propuestas-y-respuestas/" class="stretched-link text-primary text-decoration-none">📰 Finaliza la ronda de reuniones vecinales de «Barrios, propuestas y respuestas»</a>
                </h3>
                <p class="card-text text-muted mb-0">Haz clic aquí para leer la nota de prensa completa.</p>
            </div>
        </div>
    </section>

    <!-- Propuestas por Barrio -->
    <section>
        <h2 class="h3 fw-bold border-bottom pb-2 mb-3">Propuestas por Barrio</h2>
        <p class="text-secondary mb-4">Resumen de las reuniones celebradas con cada asociación de vecinos, ordenadas cronológicamente.</p>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            <?php foreach ($barrios as $barrio) : ?>
                <div class="col">
                    <div class="card h-100 shadow-sm position-relative">
                        <div class="card-body p-4">
                            <h3 class="h5 fw-bold mb-2">📍 <?php echo esc_html($barrio['nombre']); ?></h3>
                            <p class="card-text text-muted small mb-3">Reunión realizada el <?php echo esc_html($barrio['fecha']); ?>.</p>
                            <a href="<?php echo esc_url($barrio['pdf']); ?>" class="stretched-link text-decoration-none text-blue fw-semibold" target="_blank" rel="noopener">➔ Enlace al resumen</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</div>

<?php

get_footer();
