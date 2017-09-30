<?php
/**
* Plugin Name: Groups Sandbox
* Plugin URI:
* Description: A sandbox plugin for examples and useful code related to <a href=" https://wordpress.org/plugins/groups">Groups</a>.
* Version: 1.0.0
* Author: itthinx
* Author URI: http://www.itthinx.com
* Donate-Link: http://www.itthinx.com
* License: GPLv3
*/

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Groups Sandbox plugin class.
 */
class Groups_Sandbox {

	/**
	 * Adds our plugins_loaded action handler.
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'plugins_loaded' ) );
	}

	/**
	 * Hooked on the plugins_loaded action.
	 */
	public static function plugins_loaded() {
		if ( defined( 'GROUPS_CORE_VERSION' ) ) {
			add_shortcode( 'groups_sandbox_posts', array( __CLASS__, 'groups_sandbox_posts' ) );
		}
	}

	/**
	 * [groups_sandbox_posts] shortcode handler.
	 *
	 * @param array $atts shortcode attributes
	 * @param string $content surrounded content (not used)
	 *
	 * @return string rendered HTML
	 */
	public static function groups_sandbox_posts( $atts, $content = '' ) {

		$result = '';

		$atts = shortcode_atts(
			array(
				'group'            => '',
				'numberposts'      => -1,
				'order'            => 'asc',
				'orderby'          => 'title',
				'post_type'        => 'post',
				'post_status'      => 'publish',
				'suppress_filters' => false
			),
			$atts
		);

		$group_ids        = array();
		$groups           = array_map( 'trim', explode( ',', $atts['group'] ) );
		$numberposts      = intval( $atts['numberposts'] );
		$order            = trim( $atts['order'] );
		$orderby          = trim( $atts['orderby'] );
		$post_type        = array_map( 'trim', explode( ',', $atts['post_type'] ) );
		$post_status      = array_map( 'trim', explode( ',', $atts['post_status'] ) );
		$suppress_filters =
			$atts['suppress_filters'] === true ||
			strtolower( trim( $atts['suppress_filters'] ) ) === 'true' ||
			strtolower( trim( $atts['suppress_filters'] ) ) === 'yes';

		foreach( $groups as $group ) {
			$group_object = null;
			if ( is_numeric( $group ) ) {
				$group_object = Groups_Group::read( intval( $group ) );
			}
			if ( ! $group_object ) {
				$group_object = Groups_Group::read_by_name( $group );
			}
			if ( $group_object ) {
				$group_ids[] = $group_object->group_id;
			}
		}

		$args = array(
			'field'            => 'id',
			'numberposts'      => $numberposts,
			'order'            => $order,
			'orderby'          => $orderby,
			'post_type'        => $post_type,
			'post_status'      => $post_status,
			'suppress_filters' => $suppress_filters
		);

		if ( count( $group_ids ) > 0 ) {
			if ( count( $group_ids ) > 1 ) {
				$args['meta_query'] = array(
					array(
						'key'     => Groups_Post_Access::POSTMETA_PREFIX . Groups_Post_Access::READ,
						'value'   => $group_ids,
						'compare' => 'IN'
					)
				);
			} else {
				$args['meta_key']   = Groups_Post_Access::POSTMETA_PREFIX . Groups_Post_Access::READ;
				$args['meta_value'] = array_shift( $group_ids );
			}
		}

		$post_ids = get_posts( $args );
		if ( count( $post_ids ) > 0 ) {
			$entries = array();
			foreach( $post_ids as $post_id ) {
				$url       = get_permalink( $post_id );
				$title     = get_the_title( $post_id );
				$entries[] = apply_filters(
					'groups_sandbox_posts_item',
					sprintf(
						'<li class="groups-sandbox-posts-item"><a href="%s" title="%s">%s</a></li>',
						esc_url( $url ),
						esc_attr( $title ),
						esc_html( $title )
					),
					$args,
					$post_ids
				);
			}
			$result =
				apply_filters( 'groups_sandbox_posts_prefix', '<ul class="groups-sandbox-posts">', $args, $post_ids ) .
				implode( apply_filters( 'groups_sandbox_posts_separator', "\n", $args, $post_ids ), $entries ) .
				apply_filters( 'groups_sandbox_posts_suffix', '</ul>', $args, $post_ids );
		}
		return $result;
	}
}
Groups_Sandbox::init();
