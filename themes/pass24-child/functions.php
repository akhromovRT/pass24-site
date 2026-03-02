<?php
/**
 * PASS24 Child Theme — functions.php
 *
 * Дочерняя тема GeneratePress для pass24.online.
 * Загрузка дизайн-системы, шрифтов, очистка WordPress.
 *
 * @package PASS24_Child
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'PASS24_CHILD_VERSION', '1.1.0' );
define( 'PASS24_CHILD_DIR', get_stylesheet_directory() );
define( 'PASS24_CHILD_URI', get_stylesheet_directory_uri() );

/* --------------------------------------------------------------------------
   1. Подключение стилей и шрифтов
   -------------------------------------------------------------------------- */

add_action( 'wp_enqueue_scripts', 'pass24_enqueue_assets' );

function pass24_enqueue_assets(): void {
	// Родительская тема GeneratePress
	wp_enqueue_style(
		'generatepress-parent',
		get_template_directory_uri() . '/style.css',
		[],
		wp_get_theme( 'generatepress' )->get( 'Version' )
	);

	// Child theme style.css (заголовок темы)
	wp_enqueue_style(
		'pass24-child',
		PASS24_CHILD_URI . '/style.css',
		[ 'generatepress-parent' ],
		PASS24_CHILD_VERSION
	);

	// Дизайн-система
	wp_enqueue_style(
		'pass24-design-system',
		PASS24_CHILD_URI . '/assets/css/design-system.css',
		[ 'pass24-child' ],
		PASS24_CHILD_VERSION
	);

	// Inter (Google Fonts) — display=swap для производительности
	wp_enqueue_style(
		'pass24-font-inter',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		[],
		null
	);

	// Стили секций (табы, карусель, маркиз логотипов, таблица сравнения)
	wp_enqueue_style(
		'pass24-sections',
		PASS24_CHILD_URI . '/assets/css/sections.css',
		[ 'pass24-design-system' ],
		PASS24_CHILD_VERSION
	);

	// Header (мобильное меню, sticky shadow)
	wp_enqueue_script(
		'pass24-header',
		PASS24_CHILD_URI . '/assets/js/header.js',
		[],
		PASS24_CHILD_VERSION,
		true
	);

	// Sticky CTA-бар
	wp_enqueue_script(
		'pass24-sticky-cta',
		PASS24_CHILD_URI . '/assets/js/sticky-cta.js',
		[],
		PASS24_CHILD_VERSION,
		true
	);

	// Интерактивные компоненты (табы, карусель, ротатор отзывов)
	wp_enqueue_script(
		'pass24-components',
		PASS24_CHILD_URI . '/assets/js/components.js',
		[],
		PASS24_CHILD_VERSION,
		true
	);

	// Страница тарифов — загружать только на /pricing/
	if ( is_page( 'pricing' ) || is_page( 'tarify' ) ) {
		wp_enqueue_style(
			'pass24-pricing',
			PASS24_CHILD_URI . '/assets/css/pricing.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);

		wp_enqueue_script(
			'pass24-pricing',
			PASS24_CHILD_URI . '/assets/js/pricing.js',
			[],
			PASS24_CHILD_VERSION,
			true
		);
	}

	// Страница демо — загружать только на /demo/
	if ( is_page( 'demo' ) ) {
		wp_enqueue_style(
			'pass24-demo',
			PASS24_CHILD_URI . '/assets/css/demo.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);

		wp_enqueue_script(
			'pass24-demo',
			PASS24_CHILD_URI . '/assets/js/demo.js',
			[],
			PASS24_CHILD_VERSION,
			true
		);

		// WP REST API nonce для AJAX-отправки формы
		wp_localize_script( 'pass24-demo', 'wpApiSettings', [
			'root'  => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		] );
	}
}

/* --------------------------------------------------------------------------
   2. Preconnect для Google Fonts (ускоряет загрузку шрифтов)
   -------------------------------------------------------------------------- */

add_filter( 'wp_resource_hints', 'pass24_resource_hints', 10, 2 );

function pass24_resource_hints( array $urls, string $relation_type ): array {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = [
			'href'        => 'https://fonts.googleapis.com',
			'crossorigin' => true,
		];
		$urls[] = [
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => true,
		];
	}
	return $urls;
}

/* --------------------------------------------------------------------------
   3. Очистка WordPress (производительность)
   Perfmatters может дублировать эти настройки — отключить здесь или там.
   -------------------------------------------------------------------------- */

add_action( 'init', 'pass24_cleanup_wp' );

function pass24_cleanup_wp(): void {
	// Эмодзи WordPress — не нужны
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	// RSS-ссылки в <head> — не нужны
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	// Версия WordPress в <head> — безопасность
	remove_action( 'wp_head', 'wp_generator' );

	// RSD и wlwmanifest — легаси
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );

	// Shortlink
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}

// Отключить эмодзи в TinyMCE
add_filter( 'tiny_mce_plugins', function ( array $plugins ): array {
	return array_diff( $plugins, [ 'wpemoji' ] );
} );

// Cache busting: наши файлы → filemtime, WP core → скрыть версию
add_filter( 'script_loader_src', 'pass24_smart_version', 15 );
add_filter( 'style_loader_src', 'pass24_smart_version', 15 );

function pass24_smart_version( string $src ): string {
	if ( strpos( $src, '?ver=' ) === false ) {
		return $src;
	}

	// Наши файлы темы — filemtime для автоматического cache bust
	if ( strpos( $src, '/pass24-child/' ) !== false ) {
		$file = str_replace(
			PASS24_CHILD_URI,
			PASS24_CHILD_DIR,
			strtok( $src, '?' )
		);
		if ( file_exists( $file ) ) {
			return add_query_arg( 'v', filemtime( $file ), remove_query_arg( 'ver', $src ) );
		}
	}

	// Все остальные — убрать ?ver= (скрыть версию WP)
	return remove_query_arg( 'ver', $src );
}

/* --------------------------------------------------------------------------
   4. Body-класс с slug страницы (для CSS-таргетинга)
   -------------------------------------------------------------------------- */

add_filter( 'body_class', 'pass24_page_slug_body_class' );

function pass24_page_slug_body_class( array $classes ): array {
	if ( is_page() ) {
		$classes[] = 'page-slug-' . get_post_field( 'post_name', get_queried_object_id() );
	}
	return $classes;
}

/* --------------------------------------------------------------------------
   5. Поддержка Bricks Builder
   -------------------------------------------------------------------------- */

// Добавить CSS-переменные дизайн-системы в редактор Bricks
add_action( 'wp_head', 'pass24_bricks_editor_vars' );

function pass24_bricks_editor_vars(): void {
	if ( ! function_exists( 'bricks_is_builder' ) ) {
		return;
	}
	if ( bricks_is_builder() ) {
		echo '<link rel="stylesheet" href="' . esc_url( PASS24_CHILD_URI . '/assets/css/design-system.css' ) . '">';
	}
}

/* --------------------------------------------------------------------------
   5. Настройки изображений
   -------------------------------------------------------------------------- */

add_action( 'after_setup_theme', 'pass24_theme_setup' );

function pass24_theme_setup(): void {
	// WebP-загрузки
	add_filter( 'upload_mimes', function ( array $mimes ): array {
		$mimes['webp'] = 'image/webp';
		$mimes['avif'] = 'image/avif';
		return $mimes;
	} );

	// Кастомные размеры изображений
	add_image_size( 'pass24-card', 600, 400, true );        // Карточки кейсов/продуктов
	add_image_size( 'pass24-hero', 1200, 630, true );        // Hero и OG-изображения
	add_image_size( 'pass24-logo', 200, 80, false );         // Логотипы клиентов
}

/* --------------------------------------------------------------------------
   7. REST API — обработка формы демо-запроса
   -------------------------------------------------------------------------- */

add_action( 'rest_api_init', 'pass24_register_demo_endpoint' );

function pass24_register_demo_endpoint(): void {
	register_rest_route( 'pass24/v1', '/demo-request', [
		'methods'             => 'POST',
		'callback'            => 'pass24_handle_demo_request',
		'permission_callback' => '__return_true',
	] );
}

function pass24_handle_demo_request( WP_REST_Request $request ): WP_REST_Response {
	$params = $request->get_json_params();

	$name          = sanitize_text_field( $params['name'] ?? '' );
	$phone         = sanitize_text_field( $params['phone'] ?? '' );
	$email         = sanitize_email( $params['email'] ?? '' );
	$object_type   = sanitize_text_field( $params['object_type'] ?? '' );
	$access_points = sanitize_text_field( $params['access_points'] ?? '' );
	$has_skud      = sanitize_text_field( $params['has_skud'] ?? '' );
	$call_time     = sanitize_text_field( $params['call_time'] ?? '' );
	$comment       = sanitize_textarea_field( $params['comment'] ?? '' );
	$is_partial    = ! empty( $params['partial'] );

	// Validate required fields
	if ( empty( $name ) || empty( $phone ) || empty( $email ) ) {
		return new WP_REST_Response( [ 'error' => 'Missing required fields' ], 400 );
	}

	// UTM parameters
	$utm = [];
	foreach ( [ 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ] as $key ) {
		if ( ! empty( $params[ $key ] ) ) {
			$utm[ $key ] = sanitize_text_field( $params[ $key ] );
		}
	}

	// Build comment for CRM
	$crm_comment = '';
	if ( $object_type ) {
		$crm_comment .= "Тип объекта: {$object_type}\n";
	}
	if ( $access_points ) {
		$crm_comment .= "Точек доступа: {$access_points}\n";
	}
	if ( $has_skud ) {
		$crm_comment .= 'Текущий СКУД: ' . ( 'yes' === $has_skud ? 'Да' : 'Нет' ) . "\n";
	}
	if ( $call_time ) {
		$crm_comment .= "Время звонка: {$call_time}\n";
	}
	if ( $comment ) {
		$crm_comment .= "Комментарий: {$comment}\n";
	}
	if ( $is_partial ) {
		$crm_comment .= "\n[Частичная заявка — шаг 1]\n";
	}

	// Send to Bitrix24 if webhook is configured
	$bitrix_url = defined( 'PASS24_BITRIX_WEBHOOK' ) ? PASS24_BITRIX_WEBHOOK : '';
	if ( $bitrix_url ) {
		$lead_data = [
			'fields' => [
				'TITLE'    => ( $is_partial ? '[Partial] ' : '' ) . 'Демо: ' . $name,
				'NAME'     => $name,
				'PHONE'    => [ [ 'VALUE' => $phone, 'VALUE_TYPE' => 'WORK' ] ],
				'EMAIL'    => [ [ 'VALUE' => $email, 'VALUE_TYPE' => 'WORK' ] ],
				'COMMENTS' => $crm_comment,
				'SOURCE_ID' => 'WEB',
			],
		];

		// Add UTM fields
		foreach ( $utm as $key => $value ) {
			$lead_data['fields'][ 'UTM_' . strtoupper( str_replace( 'utm_', '', $key ) ) ] = $value;
		}

		wp_remote_post( $bitrix_url . 'crm.lead.add.json', [
			'body'    => wp_json_encode( $lead_data ),
			'headers' => [ 'Content-Type' => 'application/json' ],
			'timeout' => 10,
		] );
	}

	// Send email notification as fallback
	$admin_email = get_option( 'admin_email' );
	$subject     = ( $is_partial ? '[Partial] ' : '' ) . 'Заявка на демо: ' . $name;
	$body        = "Имя: {$name}\nТелефон: {$phone}\nEmail: {$email}\n\n{$crm_comment}";

	wp_mail( $admin_email, $subject, $body );

	return new WP_REST_Response( [ 'success' => true ], 200 );
}
