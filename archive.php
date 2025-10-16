<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aranda_de_Duero
 */

get_header();
?>

<div class="container mt-4">
		<div class="row">
			<div class="col-lg-3 pt-4">
				<?php dynamic_sidebar(get_post_type());?>
			</div>
			<div class="col-lg-9 pt-4">
				<main id="primary" class="site-main">
				
					<?php 
						$query = aranda_de_duero_content(
							get_post_type(),
							'publish_date',
							'DESC',
							'9'
						);
			
					?>

					<?php if ( $query->have_posts() ) : ?> 
                        <div class="col-lg-8 col-sm-12">
                            <div class="row">
                                <ul>
                                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                        <li>
                                            <div>                                            
                                                <p><?php echo get_the_date()?></p>
                                                <a href="<?php the_permalink()?>" target="_blank"><p><?php the_title()?></p></a>
                                            </div>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                       
                    <?php endif;?>
				</main>
			</div>
		</div>
</div> 

	

<?php
// get_sidebar();
get_footer();
