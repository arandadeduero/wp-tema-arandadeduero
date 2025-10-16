<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<label for="s">
		<span class="screen-reader-text"><?php _e('Buscar');?></span>
    </label>
		<input type="search" class="search-field" placeholder="<?php _e('Buscar');?>" value="<?php echo get_search_query() ?>" name="s" id="s">
	
		<input type="submit" class="search-submit" value="<?php _e('Buscar');?>">
</form>