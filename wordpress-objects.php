<?php

namespace WordPress_Objects;

include_once( 'wordpress-objects.taxonomy.php' );
include_once( 'wordpress-objects.term.php' );

require_once( dirname( __FILE__ ) . '/inc/class-object-iterator.php' );
require_once( dirname( __FILE__ ) . '/inc/class-base.php' );
require_once( dirname( __FILE__ ) . '/inc/class-user.php' );
require_once( dirname( __FILE__ ) . '/inc/class-post.php' );

/**
 * Check if an array only contains numeric ids
 * @return boolean
 */
function is_array_numerics( $args ) {
	return array_unique( array_map( 'is_numeric', $args ) ) === array( true );
}
