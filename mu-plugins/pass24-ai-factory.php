<?php
/**
 * Plugin Name: PASS24 AI Sales Factory Integration
 * Description: Регистрирует мета-поля Rank Math в REST API и дублирует лиды в AI Sales Factory.
 * Version:     1.0.0
 *
 * Установка: скопировать в wp-content/mu-plugins/ — активируется автоматически.
 *
 * Требует в wp-config.php:
 *   define( 'AI_FACTORY_URL', 'https://api.pass24.online' );
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// 1. Регистрация мета-полей Rank Math Pro в WordPress REST API
//
//    По умолчанию Rank Math не регистрирует свои мета-поля в REST API,
//    из-за чего pass24-ai-sales/src/core/tools/wordpress.py не может
//    записать rank_math_title / rank_math_description при публикации.
//    Этот блок открывает поля для write-доступа через /wp/v2/posts.
// ---------------------------------------------------------------------------
add_action( 'init', function () {
    $rank_math_fields = [
        'rank_math_title'         => 'SEO-заголовок страницы (Rank Math)',
        'rank_math_description'   => 'SEO мета-описание страницы (Rank Math)',
        'rank_math_focus_keyword' => 'Главное ключевое слово (Rank Math)',
    ];

    foreach ( $rank_math_fields as $key => $description ) {
        register_post_meta( 'post', $key, [
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'description'   => $description,
            'auth_callback' => fn() => current_user_can( 'edit_posts' ),
        ] );
    }
} );

// ---------------------------------------------------------------------------
// 2. Дублирование лидов из Gravity Forms в AI Sales Factory
//
//    Запускается после каждой отправки формы (fire-and-forget, blocking=false).
//    Основной поток Gravity Forms → Flamix → Битрикс24 не затрагивается.
//
//    Маппинг полей формы (настроить под реальные ID в Gravity Forms):
//      Поле 1 → Имя контакта
//      Поле 2 → Телефон
//      Поле 3 → Email
//      Поле 4 → Название компании
//      Поле 5 → Тип объекта (ЖК / КП / БЦ / ...)
//      Поле 6 → Количество точек доступа
//      Поле 7 → Есть ли текущий СКУД
// ---------------------------------------------------------------------------
add_action( 'gform_after_submission', function ( $entry, $form ) {
    $ai_factory_url = defined( 'AI_FACTORY_URL' ) ? AI_FACTORY_URL : getenv( 'AI_FACTORY_URL' );

    if ( empty( $ai_factory_url ) ) {
        return; // AI_FACTORY_URL не задан — пропустить без ошибки
    }

    $lead_data = [
        'source'       => 'website',
        'form_id'      => $form['id'],
        'contact_name' => rgar( $entry, '1' ),
        'phone'        => rgar( $entry, '2' ),
        'email'        => rgar( $entry, '3' ),
        'company_name' => rgar( $entry, '4' ),
        'metadata'     => [
            'object_type'   => rgar( $entry, '5' ),
            'access_points' => rgar( $entry, '6' ),
            'has_skud'      => rgar( $entry, '7' ),
            'utm_source'    => rgar( $entry, 'utm_source' ),
            'utm_medium'    => rgar( $entry, 'utm_medium' ),
            'utm_campaign'  => rgar( $entry, 'utm_campaign' ),
            'page_url'      => rgar( $entry, 'source_url' ),
        ],
    ];

    wp_remote_post(
        trailingslashit( $ai_factory_url ) . 'api/webhooks/lead',
        [
            'body'      => wp_json_encode( $lead_data ),
            'headers'   => [ 'Content-Type' => 'application/json' ],
            'timeout'   => 5,
            'blocking'  => false,   // fire-and-forget: не задерживает ответ пользователю
            'sslverify' => true,
        ]
    );
}, 10, 2 );
