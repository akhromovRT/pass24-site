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

define( 'PASS24_CHILD_VERSION', '1.0.0' );
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

// Убрать query strings из статики (?ver=) для лучшего кэширования CDN
add_filter( 'script_loader_src', 'pass24_remove_version_query', 15 );
add_filter( 'style_loader_src', 'pass24_remove_version_query', 15 );

function pass24_remove_version_query( string $src ): string {
	if ( strpos( $src, '?ver=' ) !== false ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}

/* --------------------------------------------------------------------------
   4. Поддержка Bricks Builder
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
