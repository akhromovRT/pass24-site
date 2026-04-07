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

	// Phone input mask (+7 auto-fill for all tel inputs)
	wp_enqueue_script(
		'pass24-phone-mask',
		PASS24_CHILD_URI . '/assets/js/phone-mask.js',
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

	// Страница «О компании» — загружать только на /about/
	if ( is_page( 'about' ) ) {
		wp_enqueue_style(
			'pass24-about',
			PASS24_CHILD_URI . '/assets/css/about.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Страница контактов — загружать только на /about/contacts/
	if ( is_page( 'contacts' ) ) {
		wp_enqueue_style(
			'pass24-contacts',
			PASS24_CHILD_URI . '/assets/css/contacts.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);

		wp_enqueue_script(
			'pass24-contacts',
			PASS24_CHILD_URI . '/assets/js/contacts.js',
			[],
			PASS24_CHILD_VERSION,
			true
		);

		wp_localize_script( 'pass24-contacts', 'wpApiSettings', [
			'root'  => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		] );
	}

	// Страницы продуктов /products/*
	if ( is_page() && pass24_is_child_of_page( 'products' ) ) {
		wp_enqueue_style(
			'pass24-products',
			PASS24_CHILD_URI . '/assets/css/products.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Hub-страница продуктов /products/
	if ( is_page( 'products' ) ) {
		wp_enqueue_style(
			'pass24-products',
			PASS24_CHILD_URI . '/assets/css/products.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Страницы решений /solutions/*
	if ( is_page() && pass24_is_child_of_page( 'solutions' ) ) {
		wp_enqueue_style(
			'pass24-solutions',
			PASS24_CHILD_URI . '/assets/css/solutions.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);

		wp_enqueue_script(
			'pass24-solutions',
			PASS24_CHILD_URI . '/assets/js/solutions.js',
			[],
			PASS24_CHILD_VERSION,
			true
		);

		wp_localize_script( 'pass24-solutions', 'wpApiSettings', [
			'root'  => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		] );
	}

	// Hub-страница решений /solutions/
	if ( is_page( 'solutions' ) ) {
		wp_enqueue_style(
			'pass24-solutions',
			PASS24_CHILD_URI . '/assets/css/solutions.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Страница интеграций /integrations/
	if ( is_page( 'integrations' ) ) {
		wp_enqueue_style(
			'pass24-integrations',
			PASS24_CHILD_URI . '/assets/css/integrations.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Страницы кейсов /cases/*
	if ( is_page( 'cases' ) || ( is_page() && pass24_is_child_of_page( 'cases' ) ) ) {
		wp_enqueue_style(
			'pass24-cases',
			PASS24_CHILD_URI . '/assets/css/cases.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Блог
	if ( is_singular( 'post' ) || is_home() || is_category() || is_tag() || is_archive() ) {
		wp_enqueue_style(
			'pass24-blog',
			PASS24_CHILD_URI . '/assets/css/blog.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Printable guides /resources/guide-*
	if ( is_page( 'guide-choose-skud' ) || is_page( 'guide-modernize' ) ) {
		wp_enqueue_style(
			'pass24-guide',
			PASS24_CHILD_URI . '/assets/css/guide.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Resources hub /resources/
	if ( is_page( 'resources' ) ) {
		wp_enqueue_style(
			'pass24-resources',
			PASS24_CHILD_URI . '/assets/css/resources.css',
			[ 'pass24-design-system' ],
			PASS24_CHILD_VERSION
		);
	}

	// Global analytics — UTM cookies (all pages)
	wp_enqueue_script(
		'pass24-analytics',
		PASS24_CHILD_URI . '/assets/js/analytics.js',
		[],
		PASS24_CHILD_VERSION,
		true
	);
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
		$post_id = get_queried_object_id();
		$classes[] = 'page-slug-' . get_post_field( 'post_name', $post_id );

		// Добавить класс родительской страницы (page-parent-products, page-parent-solutions)
		$parent_id = wp_get_post_parent_id( $post_id );
		if ( $parent_id ) {
			$classes[] = 'page-parent-' . get_post_field( 'post_name', $parent_id );
		}
	}
	return $classes;
}

/**
 * Проверяет, является ли текущая страница дочерней для указанного slug.
 */
function pass24_is_child_of_page( string $parent_slug ): bool {
	$parent_id = wp_get_post_parent_id( get_queried_object_id() );
	if ( ! $parent_id ) {
		return false;
	}
	return get_post_field( 'post_name', $parent_id ) === $parent_slug;
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
   6. Structured Data — JSON-LD Schema.org
   -------------------------------------------------------------------------- */

add_action( 'wp_head', 'pass24_schema_jsonld' );

function pass24_schema_jsonld(): void {
	$schema = [
		'@context'  => 'https://schema.org',
		'@graph'    => [
			[
				'@type'               => 'Organization',
				'name'                => 'PASS24.online',
				'url'                 => 'https://pass24pro.ru',
				'telephone'           => '+74954144455',
				'email'               => 'info@pass24.online',
				'description'         => 'Облачная система контроля доступа для жилых комплексов, бизнес-центров и коттеджных посёлков',
				'address'             => [
					'@type'           => 'PostalAddress',
					'addressCountry'  => 'RU',
					'addressLocality' => 'Москва',
				],
				'sameAs'              => [
					'https://t.me/pass24online',
				],
			],
			[
				'@type'               => 'SoftwareApplication',
				'name'                => 'PASS24.online',
				'description'         => 'Облачная система контроля доступа (СКУД) — управление шлагбаумами, домофонами и калитками через мобильное приложение',
				'url'                 => 'https://pass24pro.ru',
				'applicationCategory' => 'SecurityApplication',
				'operatingSystem'     => 'Cloud, iOS, Android',
				'offers'              => [
					'@type'         => 'AggregateOffer',
					'priceCurrency' => 'RUB',
					'lowPrice'      => '990',
					'highPrice'     => '14990',
					'offerCount'    => '4',
				],
			],
		],
	];

	echo '<script type="application/ld+json">' .
		wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) .
		"</script>\n";
}

/* --------------------------------------------------------------------------
   7. REST API — обработка формы демо-запроса
   -------------------------------------------------------------------------- */

add_action( 'rest_api_init', 'pass24_register_rest_endpoints' );

function pass24_register_rest_endpoints(): void {
	register_rest_route( 'pass24/v1', '/demo-request', [
		'methods'             => 'POST',
		'callback'            => 'pass24_handle_demo_request',
		'permission_callback' => '__return_true',
	] );

	register_rest_route( 'pass24/v1', '/contact', [
		'methods'             => 'POST',
		'callback'            => 'pass24_handle_contact_form',
		'permission_callback' => '__return_true',
	] );

	register_rest_route( 'pass24/v1', '/health', [
		'methods'             => 'GET',
		'callback'            => 'pass24_health_check',
		'permission_callback' => '__return_true',
	] );
}

/**
 * Extract UTM + Yandex ClientID from form submission params.
 * Returns array of CRM-ready fields to merge into crm.lead.add payload.
 */
function pass24_extract_analytics_fields( array $params ): array {
	$fields = [];

	// UTM parameters → built-in Bitrix24 lead fields
	foreach ( [ 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ] as $key ) {
		if ( ! empty( $params[ $key ] ) ) {
			$fields[ 'UTM_' . strtoupper( str_replace( 'utm_', '', $key ) ) ] = sanitize_text_field( $params[ $key ] );
		}
	}

	// Yandex Metrika ClientID → B242YA custom field
	if ( ! empty( $params['ym_client_id'] ) ) {
		$fields['UF_CRM_YA_CID'] = sanitize_text_field( $params['ym_client_id'] );
		// Also set counter ID so B242YA can match
		$fields['UF_CRM_YA_COUNTER_ID'] = '108384915';
	}

	return $fields;
}

/**
 * Send data to Bitrix24 CRM via webhook.
 * Creates a LEAD (crm.lead.add) — website forms are unqualified,
 * leads get converted to deals in the right pipeline (Облако/Оборудование) manually.
 * Logs all results to error_log for debugging.
 *
 * @param array $fields CRM entity fields.
 * @param string $form_id Identifier for logging.
 * @return array{ok: bool, method: string, error: string}
 */
function pass24_send_to_bitrix24( array $fields, string $form_id = '' ): array {
	$bitrix_url = defined( 'PASS24_BITRIX_WEBHOOK' ) ? PASS24_BITRIX_WEBHOOK : '';

	if ( empty( $bitrix_url ) ) {
		error_log( "[PASS24 CRM] PASS24_BITRIX_WEBHOOK not defined — skipping CRM for form '{$form_id}'" );
		return [ 'ok' => false, 'method' => 'none', 'error' => 'PASS24_BITRIX_WEBHOOK not defined' ];
	}

	$bitrix_url = trailingslashit( $bitrix_url );
	$payload    = [ 'fields' => $fields ];
	$post_args  = [
		'body'    => wp_json_encode( $payload ),
		'headers' => [ 'Content-Type' => 'application/json' ],
		'timeout' => 15,
	];

	$response = wp_remote_post( $bitrix_url . 'crm.lead.add.json', $post_args );

	if ( is_wp_error( $response ) ) {
		error_log( "[PASS24 CRM] crm.lead.add WP error for '{$form_id}': " . $response->get_error_message() );
		return [ 'ok' => false, 'method' => 'crm.lead.add', 'error' => $response->get_error_message() ];
	}

	$body   = json_decode( wp_remote_retrieve_body( $response ), true );
	$status = wp_remote_retrieve_response_code( $response );

	if ( ! empty( $body['result'] ) ) {
		error_log( "[PASS24 CRM] crm.lead.add OK for '{$form_id}': lead ID = {$body['result']}" );
		return [ 'ok' => true, 'method' => 'crm.lead.add', 'error' => '' ];
	}

	$err_msg = $body['error_description'] ?? $body['error'] ?? "HTTP {$status}";
	error_log( "[PASS24 CRM] crm.lead.add failed for '{$form_id}': {$err_msg}" );
	return [ 'ok' => false, 'method' => 'crm.lead.add', 'error' => $err_msg ];
}

/**
 * Health check endpoint — verifies Bitrix24 webhook and AI Factory connectivity.
 * GET /wp-json/pass24/v1/health
 */
function pass24_health_check(): WP_REST_Response {
	$checks = [];

	// Check PASS24_BITRIX_WEBHOOK
	$bitrix_url = defined( 'PASS24_BITRIX_WEBHOOK' ) ? PASS24_BITRIX_WEBHOOK : '';
	if ( empty( $bitrix_url ) ) {
		$checks['bitrix24'] = [ 'status' => 'error', 'message' => 'PASS24_BITRIX_WEBHOOK not defined in wp-config.php' ];
	} else {
		// Test webhook with a profile call (read-only, no side effects)
		$response = wp_remote_get( trailingslashit( $bitrix_url ) . 'profile.json', [ 'timeout' => 10 ] );
		if ( is_wp_error( $response ) ) {
			$checks['bitrix24'] = [ 'status' => 'error', 'message' => 'Connection failed: ' . $response->get_error_message() ];
		} else {
			$body = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( ! empty( $body['result'] ) ) {
				$checks['bitrix24'] = [ 'status' => 'ok', 'message' => 'Webhook works, user: ' . ( $body['result']['LAST_NAME'] ?? 'unknown' ) ];
			} else {
				$err = $body['error_description'] ?? $body['error'] ?? 'Unknown error';
				$checks['bitrix24'] = [ 'status' => 'error', 'message' => 'Webhook returned error: ' . $err ];
			}
		}
	}

	// Check AI_FACTORY_URL
	$ai_url = defined( 'AI_FACTORY_URL' ) ? AI_FACTORY_URL : '';
	if ( empty( $ai_url ) ) {
		$checks['ai_factory'] = [ 'status' => 'warning', 'message' => 'AI_FACTORY_URL not defined' ];
	} else {
		$checks['ai_factory'] = [ 'status' => 'ok', 'message' => 'Configured: ' . $ai_url ];
	}

	$all_ok = ! in_array( 'error', array_column( $checks, 'status' ), true );

	return new WP_REST_Response( [
		'status' => $all_ok ? 'healthy' : 'unhealthy',
		'checks' => $checks,
		'time'   => gmdate( 'c' ),
	], $all_ok ? 200 : 503 );
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

	// Send to Bitrix24
	$crm_fields = [
		'TITLE'     => ( $is_partial ? '[Partial] ' : '' ) . 'Демо: ' . $name,
		'NAME'      => $name,
		'PHONE'     => [ [ 'VALUE' => $phone, 'VALUE_TYPE' => 'WORK' ] ],
		'EMAIL'     => [ [ 'VALUE' => $email, 'VALUE_TYPE' => 'WORK' ] ],
		'COMMENTS'  => $crm_comment,
		'SOURCE_ID' => 'WEB',
	];
	$crm_fields = array_merge( $crm_fields, pass24_extract_analytics_fields( $params ) );

	$crm_result = pass24_send_to_bitrix24( $crm_fields, 'demo_request' );

	// Send email notification as fallback
	$admin_email = get_option( 'admin_email' );
	$subject     = ( $is_partial ? '[Partial] ' : '' ) . 'Заявка на демо: ' . $name;
	$body        = "Имя: {$name}\nТелефон: {$phone}\nEmail: {$email}\n\n{$crm_comment}";
	if ( ! $crm_result['ok'] ) {
		$body .= "\n[CRM ошибка: {$crm_result['error']}]\n";
	}

	wp_mail( $admin_email, $subject, $body );

	// Notify AI Sales Factory (mu-plugin hooks into this action)
	do_action( 'pass24_lead_submitted', [
		'source'       => 'website',
		'form_id'      => 'demo_request',
		'contact_name' => $name,
		'phone'        => $phone,
		'email'        => $email,
		'company_name' => '',
		'metadata'     => array_merge( [
			'object_type'   => $object_type,
			'access_points' => $access_points,
			'has_skud'      => $has_skud,
			'call_time'     => $call_time,
			'comment'       => $comment,
			'is_partial'    => $is_partial,
			'page_url'      => sanitize_url( $params['page_url'] ?? '' ),
		], $utm ),
	] );

	return new WP_REST_Response( [ 'success' => true, 'crm' => $crm_result['ok'] ], 200 );
}

/* --------------------------------------------------------------------------
   8. REST API — обработка контактной формы
   -------------------------------------------------------------------------- */

function pass24_handle_contact_form( WP_REST_Request $request ): WP_REST_Response {
	$params = $request->get_json_params();

	$name    = sanitize_text_field( $params['name'] ?? '' );
	$email   = sanitize_email( $params['email'] ?? '' );
	$phone   = sanitize_text_field( $params['phone'] ?? '' );
	$subject = sanitize_text_field( $params['subject'] ?? 'Другое' );
	$message = sanitize_textarea_field( $params['message'] ?? '' );

	if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
		return new WP_REST_Response( [ 'error' => 'Missing required fields' ], 400 );
	}

	$subjects_map = [
		'demo'        => 'Запрос демонстрации',
		'pricing'     => 'Вопрос по тарифам',
		'technical'   => 'Технический вопрос',
		'partnership' => 'Партнёрство',
		'other'       => 'Другое',
	];
	$subject_label = $subjects_map[ $subject ] ?? $subject;

	// Send to Bitrix24
	$crm_fields = [
		'TITLE'     => 'Контакт: ' . $subject_label . ' — ' . $name,
		'NAME'      => $name,
		'EMAIL'     => [ [ 'VALUE' => $email, 'VALUE_TYPE' => 'WORK' ] ],
		'COMMENTS'  => "Тема: {$subject_label}\n\n{$message}",
		'SOURCE_ID' => 'WEB',
	];
	if ( $phone ) {
		$crm_fields['PHONE'] = [ [ 'VALUE' => $phone, 'VALUE_TYPE' => 'WORK' ] ];
	}
	$crm_fields = array_merge( $crm_fields, pass24_extract_analytics_fields( $params ) );

	$crm_result = pass24_send_to_bitrix24( $crm_fields, 'contact_form' );

	// Email notification
	$admin_email  = get_option( 'admin_email' );
	$mail_subject = 'Контактная форма: ' . $subject_label . ' — ' . $name;
	$mail_body    = "Имя: {$name}\nEmail: {$email}\n";
	if ( $phone ) {
		$mail_body .= "Телефон: {$phone}\n";
	}
	$mail_body .= "Тема: {$subject_label}\n\nСообщение:\n{$message}";
	if ( ! $crm_result['ok'] ) {
		$mail_body .= "\n[CRM ошибка: {$crm_result['error']}]\n";
	}

	wp_mail( $admin_email, $mail_subject, $mail_body );

	// Notify AI Sales Factory (mu-plugin hooks into this action)
	do_action( 'pass24_lead_submitted', [
		'source'       => 'website',
		'form_id'      => 'contact_form',
		'contact_name' => $name,
		'phone'        => $phone,
		'email'        => $email,
		'company_name' => '',
		'metadata'     => [
			'subject' => $subject_label,
			'message' => $message,
			'page_url' => sanitize_url( wp_get_referer() ?: '' ),
		],
	] );

	return new WP_REST_Response( [ 'success' => true, 'crm' => $crm_result['ok'] ], 200 );
}

// Block 4.1 — ROI Calculator
require_once PASS24_CHILD_DIR . '/inc/roi-calculator.php';

// Block 4.2 — Solution Configurator
require_once PASS24_CHILD_DIR . '/inc/configurator.php';

// Block 4.5 — Exit-intent popups (also registers /download-lead endpoint for 4.4)
require_once PASS24_CHILD_DIR . '/inc/exit-intent.php';

/* --------------------------------------------------------------------------
   Yandex.Metrika — второй счётчик (собственный, с целями)
   -------------------------------------------------------------------------- */

add_action( 'wp_head', 'pass24_metrika_counter', 5 );

function pass24_metrika_counter(): void {
	?>
	<!-- Yandex.Metrika counter 108384915 -->
	<script type="text/javascript">
	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
	m[i].l=1*new Date();
	for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r)return;}
	k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	(window,document,"script","https://mc.yandex.ru/metrika/tag.js","ym");
	ym(108384915,"init",{clickmap:true,trackLinks:true,accurateTrackBounce:true,webvisor:true});
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/108384915" style="position:absolute;left:-9999px;" alt=""/></div></noscript>
	<!-- /Yandex.Metrika counter -->
	<?php
}

/* --------------------------------------------------------------------------
   B242YA — Bitrix24 ↔ Yandex Metrika bridge (сквозная аналитика)
   Автоматически передаёт ClientID в CRM-формы виджета и Open Lines.
   Для кастомных REST API форм ClientID передаётся через P24Analytics.
   -------------------------------------------------------------------------- */

add_action( 'wp_head', 'pass24_b242ya_script', 6 );

function pass24_b242ya_script(): void {
	?>
	<!-- B242YA: Bitrix24 + Yandex Metrika end-to-end analytics -->
	<script>
	(function(w,d,u){
	var s=d.createElement('script');s.defer=false;s.async=false;s.id='b242ya-script';s.src=u+'?'+(Date.now()/60000|0);
	var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
	})(window,document,'https://67p.b242ya.ru/static/js/b242ya.js');
	var b242yaScript = document.querySelector('#b242ya-script');
	b242yaScript.addEventListener('load', function() {
	B242YAInit({
		portal:'https://pass24pro.bitrix24.ru/',
		pid:'6e4f0ee8a158f9b5be4c66e7f71289d0'
	});
	});
	</script>
	<?php
}
