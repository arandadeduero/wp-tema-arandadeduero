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
        if ( get_theme_mod('aranda_de_duero_show_banner') == 1 && get_theme_mod('aranda_de_duero_show_banner_text') !=''):
     ?>
        <div class="container">
            <div class="row my-5">
                <div class="col-12">
                   <!-- Inicio banner plenos  -->
                        <div id="banner_streaming" class="d-md-flex w-100 justify-content-between align-items-center">		
                                <div id="banner_left" class="d-flex">
                                    <div><img src="<?php echo get_template_directory_uri();?>/images/webcamIcon.png"></div>
                                    <div class="ml-3"><small class="banner_streaming_color font-weight-bold">En directo - <?php echo get_theme_mod('aranda_de_duero_show_banner_text');?></small><br><span class="font-weight-bold">PLENO DEL AYUNTAMIENTO DE ARANDA DE DUERO</span></div>
                                </div>
                                <div id="banner_right" class="d-flex align-items-center justify-content-end pr-5">
                                    <div><span style="color: #b50101; text-decoration: underline;">Síguelo a través de Youtube </span>  </div>
                                    <div class="ml-3 pr-4"><img src="<?php echo get_template_directory_uri();?>/images/playIcon.png"></div>
                                </div>
		                </div>
                   <!-- Fin banner plenos  -->
                </div>
            </div>
        </div>
    <?php endif;?>
    <!-- Secciones principales -->
    <?php 
        $args = array(
            'taxonomy' => 'home_section_category',
            // 'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC' 
        );
        $taxonomies = get_terms($args);
        
    ?>
    <?php if ( !empty($taxonomies) ) : ?> 
        <div class="container mt-3">
            <div class="row align-center justify-content-center">
                <!-- the loop -->
                <?php foreach ($taxonomies as $category) {?>
                    <div class="col-lg-3 mb-4 category-section" >
                        <div class="home-main-section  rounded text-center" style="background-color:<?php echo get_theme_mod('aranda_de_duero_main_section_background'); ?>;">
                            <a href="<?php echo get_term_link($category->term_id); ?>" class="home-main-section-a" data-categoryid="<?php echo $category->term_id?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;"><p class="mb-0 py-2" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;"><?php echo $category->name; ?></p></a>
                        </div>
                    </div>
                    
                <?php } ?>
                <!-- end of the loop -->
            </div>
            
        </div>
        <div class="container">
            <?php foreach ($taxonomies as $category) {?>
            <div class="category-principal-sections category-<?php echo $category->term_id?> mt-3" >
                <?php
                    $args = array(
                        'post_type' => 'seccion-principal',
                        'order' => 'ASC',
                        'orderby' => 'post_title',
                        'tax_query' => array(
                            'relation' => 'AND',
                            array(
                                'taxonomy' => 'home_section_category',
                                'field'    => 'term_id',
                                'terms'    => $category->term_id
                            )
                        ),
                    );
                    $query = new WP_Query( $args );
                    
                ?>
                    <div class="row justify-content-center">
                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                <div class="card category-principal-sections-section rounded m-3 p-0 col-lg-2 col-6">
                                    <img src="<?php echo get_field('seccion_principal_icon')?>" class="img-fluid py-3 px-5" alt="<?php the_title(); ?>">
                                    <div class="card-body category-principal-sections-title text-center">
                                        <?php //var_dump(get_field('term_slug'));
                                        //die; ?>
                                        <a href="<?php echo get_field('seccion_principal_link'); ?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;" class=""><?php the_title(); ?></a>
                                    </div>
                                </div>
                        <?php endwhile;?>
                        
                    </div>
                    <?php wp_reset_postdata(); ?>
            </div>
            <?php } ?>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php endif;?>
    <!-- Fin secciones principales  -->
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
            <div class="col-12">
                <div class="home-news-title mb-4 text-center">
                    <h3>Actualidad y Eventos</h3>   
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 col-sm-12 mt-4">
            <?php if ( $query->have_posts() ) : ?> 
                
                    
                        <!-- the loop -->
                            <?php $count = 0; ?>
                            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                <?php 
                                $image_type='';
                                if($count == 0 || $count%2 == 1)  {
                                    echo '<div class="row">';
                                } 
                                
                                if($count == 0) {
                                    $image_type='medium';
                                    echo '<div class="col-lg-12">';
                                } else {
                                    $image_type='medium';
                                    echo '<div class="col-sm-12 col-lg-6 mt-4">';
                                }?>
                                        <div class="home-main-news p-3 rounded h-100">
                                            <div class="row">
                                                <?php 
                                                    if(get_the_post_thumbnail_url(get_the_ID(), 'medium')) {
                                                ?>
                                                        <div class="col-12">
                                                            <div class="home-main-news-image mb-3 <?php $count == 0 ? print_r('first-news') : '' ;?>">
                                                                <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), $image_type);?>" alt="<?php the_title(); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="home-main-news-description">
                                                                <a href="<?php echo the_permalink(get_the_ID());?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;"><h2 class="h6 mb-0 font-weight-bold"><?php the_title(); ?></h2></a>
                                                            </div>
                                                            <?php
                                                                if($count == 0) {
                                                            ?>
                                                                <div class="mt-3">
                                                                 <p class="small"><?php echo wp_strip_all_tags( get_the_excerpt(), true ); ?></p>
                                                                </div>
                                                            <?php
                                                                } 
                                                            ?>
                                                            <div class="home-main-news-button my-3">
                                                                <a href="<?php echo the_permalink(get_the_ID());?>" class="btn btn-primary" role="button" style="color:white !important;"><?php _e('Leer más');?></a>
                                                            </div>

                                                        </div>
                                                <?php
                                                    } else {
                                                ?>
                                                        <div class="col-12">
                                                            <div class="home-main-news-description">
                                                                <a href="<?php echo the_permalink(get_the_ID());?>" style="color:<?php echo get_theme_mod('aranda_de_duero_main_section_text_color'); ?>!important;"><h2 class="h6 mb-0 font-weight-bold"><?php the_title(); ?></h2></a>
                                                            </div>
                                                            <div class="home-main-news-button my-3">
                                                                <a href="<?php echo the_permalink(get_the_ID());?>" class="btn btn-primary" role="button" style="color:white !important;"><?php _e('Leer más');?></a>
                                                            </div>
                                                        </div>
                                                        
                                                <?php } ?>
                                                      

                                                
                                            </div>
                                        </div>
                                    </div>
                                <?php if($count == 0 || $count % 2 == 0)  {
                                    echo '</div>';
                                } ?>
                                <?php $count++; ?>
                            <?php endwhile; ?>
                            <?php if($count%2 != 1){
                                echo '</div>';
                            }?>
                        
                        <!-- end of the loop -->
                
                <?php wp_reset_postdata(); ?>
            <?php endif;?>
            
            </div>
            <div class="col-12 col-lg-4 mt-4">
    <?php
        global $wpdb;

        $query = aranda_de_duero_content(
            'mc-events',
            '',
            '',
            300
        );
    ?>

    <?php if ( $query->have_posts() ) : ?> 
        <div class="container">
        <?php 
        $array_posts = array();
        $total_posts = 0;

        // Año actual y año siguiente
        $current_year = date("Y");
        $next_year = $current_year + 1;

        while ( $query->have_posts() ) : $query->the_post(); ?>
          
            <?php 
                $array_post = array();

                $datos_evento = get_post_meta(get_the_ID(), '_mc_event_data');
                
                $date_start = $datos_evento[0]['event_begin'];
                $date_end = $datos_evento[0]['event_end'];
                
                $date_start_sto = strtotime($date_start);
                $date_end_sto = strtotime($date_end);

                $today = date("Y-m-d");
                $today_sto = strtotime($today);

                $event_start_year = date("Y", $date_start_sto);

                // Filtro para mostrar eventos que empiezan en el año actual o el siguiente y están activos
                if (($event_start_year == $current_year || $event_start_year == $next_year) &&
                    $today_sto <= $date_end_sto) {
                    $array_post['date_start'] = $date_start;
                    $array_post['date_end'] = $date_end;
                    $array_post['title'] = get_the_title();
                    $array_post['thumbnail'] = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID(), 'thumbnail'));
                    $event_id = get_post_meta(get_the_ID(), '_mc_event_id');

                    $occur_id = $wpdb->get_row("SELECT * FROM ayad_my_calendar_events WHERE occur_event_id =" . $event_id[0])->occur_id;
                                       
                    $array_post['link'] = get_permalink(get_the_ID()) . '?mc_id=' . $occur_id;

                    $array_posts[] = $array_post;     
                }
            ?>
        <?php endwhile; 

            // Ordenar los posts por la fecha de inicio
            $columns_dates = array_column($array_posts, 'date_start');
            array_multisort($columns_dates, SORT_ASC, $array_posts);
        ?>

        <?php 
        $cont_events = 0;
        foreach ($array_posts as $post) { 
            if($cont_events < 4){
                $time = strtotime($post['date_start']);
                $day = date('d', $time);
                $month = date('m', $time);
            ?>
            <div class="home-evento-descripcion row mb-2">
                <div class="col-1 p-0">
                    <div class="home-evento-fecha">
                        <p class="m-0"><?php echo $day.'.';?></p>
                        <p class="m-0"><?php echo $month;?></p>
                        <p class="home-evento-fecha-clock mb-1"><i class="far fa-clock"></i></p>
                    </div>
                </div>
                <div class="col-11 pl-5">
                    <h4><?php echo $post['title']; ?></h4>
                    <p>
                        <img src="<?php echo $post['thumbnail'];?>" alt="<?php echo $post['title']; ?>">
                    </p>
                    <a href="<?php echo $post['link'];?>" style="color:<?php echo get_theme_mod('aranda_de_duero_link_color'); ?>!important;">Más información ></a>
                </div>
            </div>
        <?php 
            }
            $cont_events++;
        } ?>
        </div>
    <?php endif; ?>
</div>


            
            
        </div>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="p-0 col-6 text-center">
                <div class="home-more-news col-8 p-0 mx-auto">
                    <a href="<?php echo get_permalink(get_page_by_title('Noticias')); ?>page/2/" class="home-more-news-button"><p class="mb-0 py-2"><?php _e('Más actualidad');?></p></a>
                </div>
                
            </div>

            <div class="p-0 col-6 text-center">
                <div class="home-more-events col-8 p-0 mx-auto">
                    <a href="/agenda" class="home-more-events-button"><p class="mb-0 py-2"><?php _e('Más eventos');?></p></a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fin noticias  -->
<?php

get_footer();
