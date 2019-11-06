<?php
/**
 * Class file for the Core_Sitemaps_Provider class.
 * This class is a base class for other sitemap providers to extend and contains shared functionality.
 *
 * @package Core_Sitemaps
 */

/**
 * Class Core_Sitemaps_Provider
 */
class Core_Sitemaps_Provider {
	/**
	 * Registry instance
	 *
	 * @var Core_Sitemaps_Registry
	 */
	public $registry;
	/**
	 * Post Type name
	 *
	 * @var string
	 */
	protected $post_type = '';

	/**
	 * Sitemap name
	 * Used for building sitemap URLs.
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Setup a link to the registry.
	 *
	 * @param Core_Sitemaps_Registry $instance Registry instance.
	 */
	public function set_registry( $instance ) {
		$this->registry = $instance;
	}

	/**
	 * Get content for a page.
	 *
	 * @param string $post_type Name of the post_type.
	 * @param int    $page_num Page of results.
	 *
	 * @return int[]|WP_Post[] Query result.
	 */
	public function get_content_per_page( $post_type, $page_num = 1 ) {
		$query = new WP_Query();

		return $query->query(
			array(
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'post_type'      => $post_type,
				'posts_per_page' => CORE_SITEMAPS_POSTS_PER_PAGE,
				'paged'          => $page_num,
			)
		);
	}

	/**
	 * Builds the URL for the sitemaps.
	 *
	 * @return string the sitemap index url.
	 */
	public function get_sitemap_url( $name ) {
		global $wp_rewrite;

		if ( $name === 'index' ) {
			$url = home_url( '/sitemap.xml' );

			if ( ! $wp_rewrite->using_permalinks() ) {
				$url = add_query_arg( 'sitemap', 'index', home_url( '/' ) );
			}
		} else {
			$url = home_url( sprintf( '/sitemap-%1$s.xml', $name ) );

			if ( ! $wp_rewrite->using_permalinks() ) {
				$url = add_query_arg( 'sitemap', $name, home_url( '/' ) );
			}
		}

		return $url;
	}
}
