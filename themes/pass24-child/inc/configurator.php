<?php
/**
 * Solution Configurator — enqueue + REST endpoint
 *
 * Самостоятельный модуль. Подключается из functions.php через require_once.
 * Регистрирует стили/скрипт только на странице slug=configurator.
 * REST endpoint: POST /wp-json/pass24/v1/configurator-lead
 *
 * @package PASS24_Child
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_page( 'configurator' ) ) {
		return;
	}

	wp_enqueue_style(
		'pass24-configurator',
		PASS24_CHILD_URI . '/assets/css/configurator.css',
		[ 'pass24-design-system' ],
		PASS24_CHILD_VERSION
	);

	wp_enqueue_script(
		'pass24-configurator',
		PASS24_CHILD_URI . '/assets/js/configurator.js',
		[],
		PASS24_CHILD_VERSION,
		true
	);

	wp_localize_script( 'pass24-configurator', 'wpApiSettings', [
		'root'  => esc_url_raw( rest_url() ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
	] );
} );

add_action( 'rest_api_init', function () {
	register_rest_route( 'pass24/v1', '/configurator-lead', [
		'methods'             => 'POST',
		'callback'            => 'pass24_handle_configurator_lead',
		'permission_callback' => '__return_true',
	] );
} );

function pass24_handle_configurator_lead( WP_REST_Request $request ): WP_REST_Response {
	$params           = $request->get_json_params();
	$name             = sanitize_text_field( $params['name'] ?? '' );
	$email            = sanitize_email( $params['email'] ?? '' );
	$phone            = sanitize_text_field( $params['phone'] ?? '' );
	$object_type      = sanitize_text_field( $params['object_type'] ?? '' );
	$recommended_plan = sanitize_text_field( $params['recommended_plan'] ?? '' );

	if ( empty( $email ) && empty( $phone ) ) {
		return new WP_REST_Response( [ 'success' => false, 'message' => 'Email или телефон обязательны' ], 400 );
	}

	// Bitrix24 CRM
	pass24_send_to_bitrix24( [
		'TITLE'              => 'Конфигуратор: ' . ( $name ?: $email ?: $phone ),
		'NAME'               => $name,
		'EMAIL'              => [ [ 'VALUE' => $email, 'VALUE_TYPE' => 'WORK' ] ],
		'PHONE'              => [ [ 'VALUE' => $phone, 'VALUE_TYPE' => 'WORK' ] ],
		'COMMENTS'           => "Конфигуратор: тариф {$recommended_plan}, тип объекта: {$object_type}",
		'SOURCE_ID'          => 'WEB',
		'SOURCE_DESCRIPTION' => 'Конфигуратор решений pass24pro.ru',
	], 'configurator' );

	// Notify AI Sales Factory (mu-plugin hooks into this action)
	do_action( 'pass24_lead_submitted', [
		'source'       => 'configurator',
		'form_id'      => 'configurator',
		'contact_name' => $name,
		'phone'        => $phone,
		'email'        => $email,
		'company_name' => '',
		'metadata'     => [
			'object_type'      => $object_type,
			'recommended_plan' => $recommended_plan,
		],
	] );

	return new WP_REST_Response( [ 'success' => true ], 200 );
}
