<?php
/**
 * Exit-intent popup — enqueue + REST endpoint
 *
 * Самостоятельный модуль. Подключается из functions.php через require_once.
 * Регистрирует стили/скрипт на ВСЕХ страницах (попапы нужны везде).
 * REST endpoint: POST /wp-json/pass24/v1/download-lead
 *
 * Варианты попапа:
 *   - /products/* и /solutions/*  → демо-запрос (CTA на /demo/)
 *   - Все остальные страницы      → чек-лист (email-захват)
 *
 * @package PASS24_Child
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'pass24-exit-intent',
		PASS24_CHILD_URI . '/assets/css/exit-intent.css',
		[ 'pass24-design-system' ],
		PASS24_CHILD_VERSION
	);

	wp_enqueue_script(
		'pass24-exit-intent',
		PASS24_CHILD_URI . '/assets/js/exit-intent.js',
		[],
		PASS24_CHILD_VERSION,
		true
	);

	wp_localize_script( 'pass24-exit-intent', 'wpApiSettings', [
		'root'  => esc_url_raw( rest_url() ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
	] );
} );

add_action( 'rest_api_init', function () {
	register_rest_route( 'pass24/v1', '/download-lead', [
		'methods'             => 'POST',
		'callback'            => 'pass24_handle_download_lead',
		'permission_callback' => '__return_true',
	] );
} );

function pass24_handle_download_lead( WP_REST_Request $request ): WP_REST_Response {
	$params = $request->get_json_params();
	$email  = sanitize_email( $params['email'] ?? '' );
	$source = sanitize_text_field( $params['source'] ?? 'exit_intent' );

	if ( empty( $email ) ) {
		return new WP_REST_Response( [ 'success' => false, 'message' => 'Email обязателен' ], 400 );
	}

	// Bitrix24 CRM
	$crm_fields = array_merge( [
		'TITLE'              => ucfirst( str_replace( '_', ' ', $source ) ) . ': ' . $email,
		'EMAIL'              => [ [ 'VALUE' => $email, 'VALUE_TYPE' => 'WORK' ] ],
		'SOURCE_ID'          => 'WEB',
		'SOURCE_DESCRIPTION' => $source . ' pass24pro.ru',
	], pass24_extract_analytics_fields( $params ) );
	pass24_send_to_bitrix24( $crm_fields, $source );

	// Notify AI Sales Factory (mu-plugin hooks into this action)
	do_action( 'pass24_lead_submitted', [
		'source'   => $source,
		'form_id'  => $source,
		'email'    => $email,
		'metadata' => [ 'source' => $source ],
	] );

	return new WP_REST_Response( [ 'success' => true ], 200 );
}
