<?php

/**
 * Template Name: Pagina de Inicio
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 px-0">
            <div class="home-video-wrapper mb-4">
                <div class="home-video ">
                    <?php
                    echo do_shortcode('[smartslider3 slider="2"]');
                    /*<video id="home-video-video" class="w-100" autoplay muted loop>
                            <source src="<?//php echo wp_get_attachment_url( get_theme_mod('aranda_de_duero_front_page_video')); ?>" />
                        </video>*/
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner de plenos  -->
<?php
if (get_theme_mod('aranda_de_duero_show_banner') == 1 && get_theme_mod('aranda_de_duero_show_banner_text') != ''):
?>
    <div class="container">
        <div class="row my-5">
            <div class="col-12">
                <!-- Inicio banner plenos  -->
                <div id="banner_streaming" class="d-md-flex w-100 justify-content-between align-items-center">
                    <div id="banner_left" class="d-flex">
                        <div><img src="<?php echo get_template_directory_uri(); ?>/images/webcamIcon.png"></div>
                        <div class="ml-3"><small class="banner_streaming_color font-weight-bold">En directo - <?php echo get_theme_mod('aranda_de_duero_show_banner_text'); ?></small><br><span class="font-weight-bold">PLENO DEL AYUNTAMIENTO DE ARANDA DE DUERO</span></div>
                    </div>
                    <div id="banner_right" class="d-flex align-items-center justify-content-end pr-5">
                        <div><span style="color: #b50101; text-decoration: underline;">Síguelo a través de Youtube </span> </div>
                        <div class="ml-3 pr-4"><img src="<?php echo get_template_directory_uri(); ?>/images/playIcon.png"></div>
                    </div>
                </div>
                <!-- Fin banner plenos  -->
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Secciones principales -->
<?php
$query = aranda_de_duero_content(
    'post',
    'publish_date',
    'DESC',
    '9'
);

?>
<div class="container mt-4">
    <div class="row">
        <div class="col mt-4">
            <!-- the loop -->
            <h4 class="text-blue font-weight-normal text-uppercase">Últimas noticias</h4>
            <?php $count = 0; ?>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php
                $image_type = 'large';

                // Open row for first item or every 4 items after first
                if ($count == 0 || ($count > 0 && ($count - 1) % 4 == 0)) {
                    echo '<div class="row">';
                }

                // Set column width
                if ($count == 0) {
                    $col_class = 'col-lg-12';
                } else {
                    $col_class = 'col-lg-3 col-md-6 mt-4';
                }

                echo '<div class="' . $col_class . '">';
                ?>
                <div class="events-agenda-card p-3 h-100">
                    <div class="row p-2">
                        <?php
                        if (get_the_post_thumbnail_url(get_the_ID(), 'large')) {
                        ?>
                            <div class="col-12">
                                <div class="p-1 mb-3 <?php $count == 0 ? print_r('first-news') : ''; ?>">
                                    <img class="d-block mx-auto" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), $image_type); ?>" alt="<?php the_title(); ?>">
                                </div>
                            </div>
                            <div class="col-12 p-3">
                                <div class="home-main-news-description">
                                    <a href="<?php echo the_permalink(get_the_ID()); ?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;">
                                        <h2 class="h6 mb-0 font-weight-normal"><?php the_title(); ?></h2>
                                    </a>
                                </div>
                                <?php
                                if ($count == 0) {
                                ?>
                                    <div class="mt-3">
                                        <p class="small"><?php echo wp_strip_all_tags(get_the_excerpt(), true); ?></p>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="home-main-news-button my-3">
                                    <a href="<?php echo the_permalink(get_the_ID()); ?>" class="text-blue"><span class="arrow">➔</span> <?php _e('Leer noticia'); ?></a>
                                </div>

                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="col-12">
                                <div class="home-main-news-description">
                                    <a href="<?php echo the_permalink(get_the_ID()); ?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;">
                                        <h2 class="h6 mb-0 font-weight-normal"><?php the_title(); ?></h2>
                                    </a>
                                </div>
                                <div class="home-main-news-button my-3">
                                    <a href="<?php echo the_permalink(get_the_ID()); ?>" class="text-blue"><span class="arrow">➔</span> <?php _e('Leer noticia'); ?></a>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                </div>
                <?php echo '</div>'; // close col 
                ?>
                <?php
                // Close row after first item or every 4 items after first, or at end
                if ($count == 0 || ($count > 0 && ($count - 1) % 4 == 3) || $query->current_post == $query->post_count - 1) {
                    echo '</div>'; // close row
                }
                ?>
                <?php $count++; ?>
            <?php endwhile; ?>

            <!-- end of the loop -->
            <?php wp_reset_postdata(); ?>
        </div>

    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="p-0 col-6 text-center">
                <div class="home-more-news col-8 p-0 mx-auto">
                    <a href="<?php echo get_permalink(get_page_by_title('Noticias')); ?>page/2/" class="home-more-news-button">
                        <p class="mb-0 py-2"><?php _e('Más actualidad'); ?></p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Fin noticias  -->

    <!-- Agenda de eventos  -->
    <?php
    // Get upcoming events for the main agenda section
    $agenda_events = aranda_de_duero_get_upcoming_events(50);

    if (! empty($agenda_events)) :
        $first_event = $agenda_events[0]; // Get the first event for the date display
        $event_time = strtotime($first_event['date_start']);
    ?>
        <div class="container py-5">
            <div class="events-agenda-card p-4">
                <h4 class="agenda-title text-blue mb-4">AGENDA DE EVENTOS</h4>

                <div class="row no-gutters bg-white shadow-sm">
                    <!-- Event Poster (First Event) -->
                    <div class="col-md-2 border-right p-3">
                        <?php if (! empty($first_event['thumbnail'])) : ?>
                            <a href="<?php echo esc_url($first_event['link']); ?>" title="<?php echo esc_attr($first_event['title']); ?>">
                                <img src="<?php echo esc_url($first_event['thumbnail']); ?>" class="img-fluid" alt="<?php echo esc_attr($first_event['title']); ?>">
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url($first_event['link']); ?>" title="<?php echo esc_attr($first_event['title']); ?>" class="text-decoration-none">
                                <div class="bg-light d-flex align-items-center justify-content-center" style="min-height: 200px;">
                                    <span class="text-muted"><?php esc_html_e('Sin imagen', 'aranda-de-duero'); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Current Date Display -->
                    <div class="col-md-2 border-right p-3 d-flex flex-column align-items-center justify-content-center text-center">
                        <span class="text-muted text-uppercase small"><?php echo esc_html(date_i18n('l')); ?></span>
                        <h1 class="display-3 font-weight-bold mb-0"><?php echo esc_html(date_i18n('d')); ?></h1>
                        <span class="h5 text-uppercase"><?php echo esc_html(date_i18n('F')); ?></span>
                    </div>

                    <!-- Events Grid (2x2) -->
                    <?php
                    // Display events in a 2-column grid
                    $event_index = 0;
                    $events_to_show = array_slice($agenda_events, 0, 4); // Show up to 4 events

                    for ($col = 0; $col < 2; $col++) : // 2 columns
                    ?>
                        <div class="col-md-4 <?php echo ($col < 1) ? 'border-right' : ''; ?>">
                            <?php
                            // Display 2 events per column
                            for ($row = 0; $row < 2; $row++) :
                                $event_idx = ($col * 2) + $row;

                                if (! empty($events_to_show[$event_idx])) :
                                    $event   = $events_to_show[$event_idx];
                                    $event_dt = strtotime($event['date_start']);
                                    $is_last = ($event_idx === count($events_to_show) - 1);
                            ?>
                                    <div class="event-item p-3 <?php echo (! $is_last && $row === 0) ? 'border-bottom' : ''; ?>">
                                        <h6 class="event-name">
                                            <a href="<?php echo esc_url($event['link']); ?>" class="text-dark text-decoration-none">
                                                <?php echo esc_html($event['title']); ?>
                                            </a>
                                        </h6>
                                        <div class="event-meta small text-muted">
                                            <span>📅 <?php echo esc_html(date_i18n('d-m-Y', $event_dt)); ?></span>
                                        </div>
                                    </div>
                            <?php
                                endif;
                            endfor;
                            ?>
                        </div>
                    <?php
                    endfor;
                    ?>
                </div>
            </div>
        </div>
</div>
<?php endif; ?>
<!-- Fin agenda de eventos  -->
<?php

get_footer();
