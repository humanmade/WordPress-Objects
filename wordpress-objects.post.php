<?php

class Post {

	public $_post;

	/**
	 * @param int $post_id
	 * @throws Exception
	 */
	public function __construct( $post_id ) {
		
		if ( empty( $post_id ) )
			throw new Exception( '$post_id empty' );

		$this->_post = get_post( $post_id );

		if ( ! $this->_post )
			throw new Exception( 'Post not found' );
	}

	public function __get( $name ) {
	
		if ( in_array( $name, array( 'post_name', 'post_title', 'ID', 'post_author', 'post_type', 'post_status'  ) ) )
			throw new Exception( 'Trying to access wp_post object properties from Post object' );
	}

	public function _refresh_data() {
		clean_post_cache( $this->_post->ID );
		$this->_post = get_post( $this->_post->ID );
	}

	/**
	 * @return int Get the ID of the post
	 */
	public function get_id() {
		return $this->_post->ID;
	}

	/**
	 * Get the parent of the post, if any
	 *
	 * @return Post|null
	 */
	public function get_parent() {

		if ( $this->_post->post_parent )
			return new Post( $this->_post->post_parent );

		return null;
	}

	/**
	 * Get the children of the post (if any)
	 * @return StdClass[]
	 */
	public function get_children() {
		return get_children( 'post_parent=' . $this->get_id() );
	}

	/**
	 * Get the attachments for the post
	 *
	 * @return StdClass[]
	 */
	public function get_attachments() {
		return get_children( 'post_type=attachment&post_parent=' . $this->get_id() );
	}

	/**
	 * Check if the post has a thumbnail
	 *
	 * @return bool
	 */
	public function has_thumbnail() {

		return has_post_thumbnail( $this->get_id() );
	}

	/**
	 * Get the thumbnail HTML for the post
	 *
	 * @param array|string $size
	 * @return string
	 */
	public function get_thumbnail( $size, $attr = '' ) {

		return get_the_post_thumbnail( $this->get_id(), $size, $attr );
	}

	public function get_thumbnail_id() {
		return get_post_thumbnail_id( $this->get_id() );
	}

	/**
	 * Get the date the post was created
	 *
	 * @param string $format
	 * @return string
	 */
	public function get_date( $format = 'U' ) {

		return date( $format, strtotime( $this->_post->post_date_gmt ) );
	}

	/**
	 * Set the post date of the post
	 * @param int $time PHP timestamp
	 */
	public function set_date( $time ) {
		$this->_post->post_data = date( 'Y-m-d H:i:s', $time );

		wp_update_post( array( 'ID' => $this->get_id(), 'post_date' => $this->_post->post_data ) );
	}

	/**
	 * Get the local date the post was created
	 *
	 * @param string $format
	 * @return string
	 */
	public function get_local_date( $format = 'U' ) {

		return date( $format, strtotime( $this->_post->post_date ) );
	}

	public function get_meta( $key, $single = false ) {
		return get_post_meta( $this->get_id(), $key, $single );
	}

	public function update_meta( $key, $value ) {
		return update_post_meta( $this->get_id(), $key, $value );
	}

	public function add_meta( $key, $value ) {
		return add_post_meta( $this->get_id(), $key, $value );
	}

	public function delete_meta( $key, $value = null ) {
		return delete_post_meta( $this->get_id(), $key, $value );
	}

	public function delete() {
		wp_delete_post( $this->get_id(), true );
	}

	public function get_title() {

		return get_the_title( $this->get_id() );
	}

	public function get_post_name() {
		return $this->_post->post_name;
	}

	public function get_content() {

		if ( ! isset( $this->_content ) ) {
			setup_postdata( $this->_post );

			ob_start();
			the_content();

			$this->_content = ob_get_clean();
			wp_reset_postdata();
		}

		return $this->_content;
	}

	public function get_raw_content() {
		return $this->_post->post_content;
	}

	public function get_author() {

		if ( $this->_post->post_author )
			return User::get( $this->_post->post_author );

		return null;
	}

	public function get_permalink() {
		return get_permalink( $this->get_id() );
	}

	public function get_excerpt() {

		if ( ! isset( $this->_excerpt ) ) {
			setup_postdata( $this->_post );

			ob_start();
			the_excerpt();

			$this->_excerpt = ob_get_clean();
			wp_reset_postdata();
		}

		return $this->_excerpt;

	}

	public function get_comment_count() {

	}

	public function get_post_type() {
		return $this->_post->post_type;
	}

	public function get_status() {
		return $this->_post->post_status;
	}

	public function set_status( $status ) {

		$this->_post->post_status = $status;

		wp_update_post( array( 'ID' => $this->get_id(), 'post_status' => $status ) );
	}

	/**
	 * @param $comment_text
	 * @param $user_id
	 * @return int
	 * @throws Exception
	 */
	public function add_comment( $comment_text, $user_id ) {

		if ( empty( $comment_text ) || empty( $user_id) )
			throw new Exception( 'Not enough data' );

		$comment = array( 'comment_post_ID' => $this->get_id(), 'user_id' => $user_id, 'comment_content' => esc_attr( $comment_text ) );

		$result = wp_insert_comment( $comment );

		if ( ! is_numeric( $result ) )
			throw new Exception( 'wp_insert_post failed: ' . $result );

		return $result;
	}

}