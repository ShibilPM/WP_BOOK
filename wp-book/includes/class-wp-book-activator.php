<?php

/**
 * Fired during plugin activation
 *
 * @link       http://shibil.com
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 * @author     Shibil <shibilpm3232@gmail.com>
 */
class Wp_Book_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'bookmeta';

		$charset_collate = $wpdb->get_charset_collate();

		$max_index_length = 191;

		$sql = "CREATE TABLE $table_name (
			meta_id bigint(20) unsigned NOT NULL auto_increment,
			book_id bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY book (book_id),
			KEY meta_key (meta_key($max_index_length))
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta( $sql );


	}

}
