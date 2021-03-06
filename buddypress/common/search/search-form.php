<?php
/**
 * BP Object search form
 *
 * @since 3.0.0
 * @version 3.1.0
 */
?>

<div class="<?php bp_nouveau_search_container_class(); ?> bp-search" data-bp-search="<?php bp_nouveau_search_object_data_attr() ;?>">
	<form action="" method="get" class="bp-dir-search-form" id="<?php bp_nouveau_search_selector_id( 'search-form' ); ?>" role="search">

		<label for="<?php bp_nouveau_search_selector_id( 'search' ); ?>" class="bp-screen-reader-text"><?php bp_nouveau_search_default_text( '', false ); ?></label>

		<input id="<?php bp_nouveau_search_selector_id( 'search' ); ?>" name="<?php bp_nouveau_search_selector_name(); ?>" type="search"  placeholder="<?php bp_nouveau_search_default_text(); ?>" />

		<input type="submit" id="search-submit" value="<?php esc_attr_e( 'Search', '__x__' ); ?>" />

	</form>
</div>
