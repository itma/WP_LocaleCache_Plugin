<?php
/**
 * @package Locale Cache
 * @version 1.7.2
 */
/*
Plugin Name: Locale cache
Plugin URI: https://github.com/itma/WP_LocaleCache_Plugin
Description: Locale cache
Author: Andrzej Bernat
Version: 1.0.0
Author URI: http://itma.pl/
*/

define( 'l10n_CACHE_PREFIX', 'wp_l10n_' );

/**
 * Load a .mo file from file previously processed by 
 * the {@link https://developer.wordpress.org/reference/functions/load_textdomain/
 * load_textdomain() function}.
 * 
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 * @return bool True on success, false on failure.
 */
function load_textdomain_from_cache( $domain ) {
	return get_transient( l10n_CACHE_PREFIX . $domain );
}

/**
 * Cache a .mo file via transient API as previously processed by 
 * the {@link https://developer.wordpress.org/reference/functions/load_textdomain/
 * load_textdomain() function}.
 * 
 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
 * @param string $moobject A MO object
 * @return bool True on success, false on failure.
 */
function cache_textdomain( $domain, $moobject ) {
	return set_transient( l10n_CACHE_PREFIX . $domain, $moobject, DAY_IN_SECONDS );
}

add_filter( 'locale_load_domain', function( $domain ) {
    $cachedTextDomain = load_textdomain_from_cache( $domain );
    if ( $cachedTextDomain ) {
        return $cachedTextDomain;
    }
    return false;
}, 10);

/**
 * 
 */
add_action('locale_imported', function( $domain, $mo ) {
    cache_textdomain( $domain, $mo );
}, 10, 2);
