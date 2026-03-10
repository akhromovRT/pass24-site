<?php
/**
 * ROI Calculator — enqueue + REST endpoint
 *
 * Самостоятельный модуль. Подключается из functions.php через require_once.
 * Регистрирует стили/скрипт только на странице slug=roi-calculator.
 * REST endpoint: POST /wp-json/pass24/v1/roi-lead
 *
 * @package PASS24_Child
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_page( 'roi-calculator' ) ) {
		return;
	}

	wp_enqueue_style(
		'pass24-roi-calculator',
		PASS24_CHILD_URI . '/assets/css/roi-calculator.css',
		[ 'pass24-design-system' ],
		PASS24_CHILD_VERSION
	);

	wp_enqueue_script(
		'pass24-roi-calculator',
		PASS24_CHILD_URI . '/assets/js/roi-calculator.js',
		[],
		PASS24_CHILD_VERSION,
		true
	);

	wp_localize_script( 'pass24-roi-calculator', 'wpApiSettings', [
		'root'  => esc_url_raw( rest_url() ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
	] );
} );

add_action( 'rest_api_init', function () {
	register_rest_route( 'pass24/v1', '/roi-lead', [
		'methods'             => 'POST',
		'callback'            => 'pass24_handle_roi_lead',
		'permission_callback' => '__return_true',
	] );
} );

function pass24_handle_roi_lead( WP_REST_Request $request ): WP_REST_Response {
	$params      = $request->get_json_params();
	$name        = sanitize_text_field( $params['name'] ?? '' );
	$email       = sanitize_email( $params['email'] ?? '' );
	$phone       = sanitize_text_field( $params['phone'] ?? '' );
	$object_type = sanitize_text_field( $params['object_type'] ?? '' );
	$plan        = sanitize_text_field( $params['recommended_plan'] ?? '' );
	$savings     = intval( $params['monthly_savings'] ?? 0 );

	if ( empty( $email ) && empty( $phone ) ) {
		return new WP_REST_Response( [ 'success' => false, 'message' => 'Email или телефон обязательны' ], 400 );
	}

	// Bitrix24 CRM
	$b24_url = defined( 'PASS24_BITRIX24_WEBHOOK_URL' ) ? PASS24_BITRIX24_WEBHOOK_URL : '';
	if ( $b24_url ) {
		wp_remote_post( $b24_url . 'crm.lead.add.json', [
			'timeout' => 5,
			'body'    => [
				'fields[TITLE]'              => 'ROI-калькулятор: ' . ( $name ?: $email ),
				'fields[NAME]'               => $name,
				'fields[EMAIL][0][VALUE]'    => $email,
				'fields[PHONE][0][VALUE]'    => $phone,
				'fields[COMMENTS]'           => "ROI: тариф {$plan}, экономия {$savings} руб/мес, тип: {$object_type}",
				'fields[SOURCE_ID]'          => 'WEB',
				'fields[SOURCE_DESCRIPTION]' => 'ROI-калькулятор pass24pro.ru',
				'fields[SCORE]'              => 20,
			],
		] );
	}

	// AI Sales Factory — fire-and-forget
	$ai_url = defined( 'AI_FACTORY_URL' ) ? AI_FACTORY_URL : '';
	if ( $ai_url ) {
		wp_remote_post( rtrim( $ai_url, '/' ) . '/webhooks/lead-form', [
			'timeout'  => 0.01,
			'blocking' => false,
			'body'     => wp_json_encode( [
				'source'       => 'roi_calculator',
				'form_id'      => 'roi-calculator',
				'contact_name' => $name,
				'email'        => $email,
				'phone'        => $phone,
				'metadata'     => [
					'object_type'      => $object_type,
					'recommended_plan' => $plan,
					'monthly_savings'  => $savings,
					'score_boost'      => 20,
				],
			] ),
			'headers'  => [ 'Content-Type' => 'application/json' ],
		] );
	}

	return new WP_REST_Response( [ 'success' => true ], 200 );
}
