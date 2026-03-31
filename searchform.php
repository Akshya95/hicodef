<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="visually-hidden"><?php _e( 'Search for:', 'compassion-ngo' ); ?></span>
        <input type="search"
               class="search-field"
               placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'compassion-ngo' ); ?>"
               value="<?php echo get_search_query(); ?>"
               name="s"
               style="padding-right:3rem;">
    </label>
    <button type="submit" class="btn btn-primary" style="margin-top:.5rem;width:100%;">
        <?php _e( 'Search', 'compassion-ngo' ); ?>
    </button>
</form>
