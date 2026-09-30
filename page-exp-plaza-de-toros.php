<?php

/**
 * Template Name: Expediente Plaza de Toros
 *
 * Página del expediente sobre la desafectación, enajenación y venta
 * de la Plaza de Toros «La Chata» de Aranda de Duero.
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
        <header class="text-center mb-4">
            <h1 class="display-5 fw-bold mb-3">Plaza de Toros «La Chata» de Aranda de Duero</h1>
            <p class="lead text-muted mb-0">Proceso de desafectación, enajenación y venta (2001-2003) y expediente de resolución del contrato (2025-2026)</p>
        </header>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <p class="text-secondary text-center" style="line-height:1.7">
                    La desafectación, enajenación y venta de la Plaza de Toros «La Chata» de Aranda de Duero (2001-2003) marcó un hito en la gestión del patrimonio local. Este proceso, que incluyó la venta del inmueble y los terrenos adjuntos y la posterior gestión del contrato de la feria taurina, ha sido uno de los temas más debatidos y complejos de la política arandina en el último cuarto de siglo, convirtiéndose en un símbolo fundamental sobre la necesidad de transparencia en la gestión pública municipal.
                </p>
                <p class="text-secondary text-center mb-0" style="line-height:1.7">
                    En julio de 2025 el Ayuntamiento abrió un nuevo expediente (ref. 2025/00016874B) de resolución del contrato de enajenación, tras constatarse que el adjudicatario, Toros Ricor S.L., no ha mantenido la continuidad de la feria taurina de las Fiestas Patronales —obligación recogida en el pliego del concurso— desde la edición de 2021. A raíz de esta providencia del alcalde, Antonio Linaje, se están elaborando sucesivos informes técnicos y jurídicos, tanto sobre el cumplimiento del contrato como sobre posibles deficiencias del expediente original de 2001-2003, que se recogen en esta página.
                </p>
            </div>
        </div>
    </div>

    <!-- Notas de Prensa -->
    <section class="mb-5">
        <h2 class="h3 fw-bold border-bottom pb-2 mb-3">Notas de Prensa</h2>
        <p class="text-secondary mb-4">Descripción detallada de los diferentes hechos analizados a través de las actas, documentos oficiales e informes jurídicos.</p>
        <div class="row row-cols-1 row-cols-md-2 g-4">

            <div class="col">
                <div class="card h-100 shadow-sm position-relative">
                    <img src="https://www.arandadeduero.es/wp-content/uploads/2026/06/pdt-chata.jpg" class="card-img-top" style="height:220px;object-fit:cover" alt="Plaza de toros La Chata">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold mb-2">
                            <a href="https://www.arandadeduero.es/wp-content/uploads/2026/06/pdt-NdP-1-origen-plaza-de-toros-Aranda.pdf" class="stretched-link text-decoration-none" target="_blank" rel="noopener">Nota de prensa 1 - Sobre la desafectación y enajenación</a>
                        </h3>
                        <p class="card-text text-secondary mb-0">Descripción breve sobre el proceso inicial de desafectación y enajenación.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm position-relative">
                    <img src="https://www.arandadeduero.es/wp-content/uploads/2026/07/pdt-firmas.jpg" class="card-img-top" style="height:220px;object-fit:cover" alt="Firmas del expediente">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold mb-2">
                            <a href="https://www.arandadeduero.es/wp-content/uploads/2026/07/pdt-NdP-Plaza-de-Toros-2.pdf" class="stretched-link text-decoration-none" target="_blank" rel="noopener">Nota de prensa 2 - Sobre la valoración económica, la falta de fiscalización y firma de un gobierno en funciones</a>
                        </h3>
                        <p class="card-text text-secondary mb-0">Se exponen las irregularidades administrativas, urbanísticas y económicas cometidas entre 2002 y 2003 en la venta de la antigua plaza de toros. Entre ellas destacan la infravaloración del suelo, la falta de fiscalización previa y la firma final en funciones sin cobrar el importe total ni exigir garantías.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm position-relative">
                    <img src="https://www.arandadeduero.es/wp-content/uploads/2026/09/pdt-nueva.jpg" class="card-img-top" style="height:220px;object-fit:cover" alt="Plaza de toros La Chata">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold mb-2">
                            <a href="https://www.arandadeduero.es/wp-content/uploads/2026/09/pdt-NdP-3-construccion-licencias-incumplimientos.pdf" class="stretched-link text-decoration-none" target="_blank" rel="noopener">Nota de prensa 3 - Sobre la construcción, licencias e incumplimientos</a>
                        </h3>
                        <p class="card-text text-secondary mb-0">Descripción detallada sobre la construcción, las licencias y los incumplimientos.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-dashed bg-light" style="border-style:dashed">
                    <img src="https://www.arandadeduero.es/wp-content/uploads/2026/07/pdt-firmas.jpg" class="card-img-top" style="height:220px;object-fit:cover;opacity:.4;filter:grayscale(1)" alt="">
                    <div class="card-body p-4">
                        <span class="badge bg-secondary mb-2">Próximamente</span>
                        <h3 class="h5 fw-bold text-muted mb-2">Nota de prensa 4</h3>
                        <p class="card-text text-muted mb-0">En preparación.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Informes Jurídicos -->
    <section class="mb-5">
        <h2 class="h3 fw-bold border-bottom pb-2 mb-3">Informes Jurídicos</h2>
        <p class="text-secondary mb-4">Informes jurídicos realizados por la secretaría municipal sobre el expediente, ordenados cronológicamente.</p>
        <div class="row row-cols-1 row-cols-md-2 g-3">

            <div class="col">
                <div class="card h-100 shadow-sm border-primary position-relative">
                    <div class="card-body p-4">
                        <h3 class="h6 card-title mb-2">
                            <a href="https://www.arandadeduero.es/wp-content/uploads/2026/09/pdt-20260302-IJ-Relación-de-las-deficiencias-jurídicas-observadas.pdf" class="stretched-link text-primary text-decoration-none" target="_blank" rel="noopener">⚖️ Relación de las deficiencias jurídicas observadas (02/03/2026)</a>
                        </h3>
                        <p class=" card-text text-muted small mb-0">Informe jurídico sobre las deficiencias jurídicas observadas en el expediente.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-primary position-relative">
                    <div class="card-body p-4">
                        <h3 class="h6 card-title mb-2">
                            <a href="https://www.arandadeduero.es/wp-content/uploads/2026/09/pdt-20260305-IJ-Informe-Jurídico-sobre-el-cumplimiento-del-contrato-de-enajenación-de-los-terrenos-de-Plaza-de-Toros.pdf" class="stretched-link text-primary text-decoration-none" target="_blank" rel="noopener">⚖️ Informe sobre el cumplimiento del contrato de enajenación (05/03/2026)</a>
                        </h3>
                        <p class="card-text text-muted small mb-0">Informe jurídico sobre el cumplimiento del contrato de enajenación de los terrenos de la Plaza de Toros.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-primary position-relative">
                    <div class="card-body p-4">
                        <h3 class="h6 card-title mb-2">
                            <a href="https://www.arandadeduero.es/wp-content/uploads/2026/09/pdt-20260806-IJ-Informe-Jurídico-sobre-procedimiento-de-enajenación-de-la-Plaza-de-toros-Informe-jurídico.pdf" class="stretched-link text-primary text-decoration-none" target="_blank" rel="noopener">⚖️ Informe sobre el procedimiento de enajenación (06/08/2026)</a>
                        </h3>
                        <p class="card-text text-muted small mb-0">Informe jurídico sobre el procedimiento de enajenación de la Plaza de Toros.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow-sm border-primary position-relative">
                    <div class="card-body p-4">
                        <h3 class="h6 card-title mb-2">
                            <a href="https://www.arandadeduero.es/wp-content/uploads/2026/09/pdt-20260814-IJ-Informe-descripción-deficiencias-de-tramitación-expediente-Plaza-de-Toros.pdf" class="stretched-link text-primary text-decoration-none" target="_blank" rel="noopener">⚖️ Descripción de las deficiencias de tramitación del expediente (14/08/2026)</a>
                        </h3>
                        <p class="card-text text-muted small mb-0">Informe jurídico que describe las deficiencias de tramitación del expediente de la Plaza de Toros.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Documentación Anexa -->
    <section class="mb-5">
        <h2 class="h3 fw-bold border-bottom pb-2 mb-3">Documentación Anexa</h2>
        <p class="text-secondary mb-2">Fuentes primarias de información: actas de comisiones y plenos, propuestas políticas y otra documentación de los expedientes municipales.</p>
        <p class="small text-muted fst-italic mb-4">Nota: Los cargos públicos y empleados públicos actuando en el ejercicio de sus funciones (como el secretario municipal o funcionarios que intervienen en la sesión) no requieren anonimización de su nombre.</p>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
            <div class="col">
                <div class="card h-100 shadow-sm position-relative">
                    <div class="card-body p-3">
                        <a href="https://www.arandadeduero.es/wp-content/uploads/2026/06/pdt-Doc-Anexa-7-Dictamen-C-O.pdf" class="stretched-link text-decoration-none" target="_blank" rel="noopener">📄 Documento 7 - Dictamen de la comisión de obras</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm position-relative">
                    <div class="card-body p-3">
                        <a href="https://www.arandadeduero.es/wp-content/uploads/2026/06/pdt-Doc-Anexa-9-Asunto-Cambio-de-calificación.pdf" class="stretched-link text-decoration-none" target="_blank" rel="noopener">📄 Documento 9 - Propuesta política de cambio de calificación</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm position-relative">
                    <div class="card-body p-3">
                        <a href="https://www.arandadeduero.es/wp-content/uploads/2026/09/pdt-20250730-Providencia.pdf" class="stretched-link text-decoration-none" target="_blank" rel="noopener">📄 Providencia (30/07/2025)</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow-sm position-relative">
                    <div class="card-body p-3">
                        <a href="https://www.arandadeduero.es/wp-content/uploads/2026/09/pdt-20250819-IT-festejos.pdf" class="stretched-link text-decoration-none" target="_blank" rel="noopener">📄 Informe Técnico sobre festejos (19/08/2025)</a>
                    </div>
                </div>
            </div>
            <!-- Añadir más documentos copiando el bloque .col de arriba -->
        </div>
    </section>

    <!-- Glosario -->
    <section>
        <h2 class="h3 fw-bold border-bottom pb-2 mb-3">Glosario</h2>
        <p class="text-secondary mb-4">Definiciones de términos técnicos y jurídicos clave para entender el expediente de forma sencilla.</p>
        <div class="card shadow-sm border-primary position-relative">
            <div class="card-body text-center p-4">
                <h3 class="h5 card-title mb-2">
                    <a href="https://www.arandadeduero.es/wp-content/uploads/2026/06/pdt-expediente-glosario.pdf" class="stretched-link text-primary text-decoration-none" target="_blank" rel="noopener">📖 Acceso al Glosario Técnico</a>
                </h3>
                <p class="card-text text-muted mb-0">Haz clic aquí para descargar el documento completo.</p>
            </div>
        </div>
    </section>

</div>

<?php

get_footer();
