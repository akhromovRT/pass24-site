<?php
/**
 * Template: Contacts / Контакты
 *
 * Страница контактов: адрес, телефон, email, мессенджеры, форма обратной связи, карта.
 * Используется для /about/contacts/ (slug: contacts).
 *
 * @package PASS24_Child
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'generate_show_sidebar', '__return_false', 99 );

get_header();
?>

<div class="site-content" id="content">
	<div class="content-area" style="width: 100%;">
		<main class="site-main">


			<!-- ============================================================
			     HERO
			     ============================================================ -->

			<section class="p24-contacts-hero">
				<div class="p24-container" style="max-width: 700px; text-align: center;">
					<h1 class="p24-h1" style="margin-bottom: 16px;">Свяжитесь с&nbsp;нами</h1>
					<p class="p24-subtitle" style="max-width: 100%; margin: 0 auto;">
						Ответим на&nbsp;вопросы, подберём решение под ваш объект и&nbsp;организуем демонстрацию
					</p>
				</div>
			</section>


			<!-- ============================================================
			     КОНТАКТЫ + ФОРМА
			     ============================================================ -->

			<section class="p24-section p24-contacts-main">
				<div class="p24-container">
					<div class="p24-contacts-layout">

						<!-- Левая колонка: контактная информация -->
						<div class="p24-contacts-info">

							<div class="p24-contacts-block">
								<h3 class="p24-contacts-block__heading">Телефон</h3>
								<a href="tel:+74951234567" class="p24-contacts-block__value p24-contacts-block__value--lg">
									+7 (495) XXX-XX-XX
								</a>
								<p class="p24-small">Пн — Пт, 9:00 — 18:00 (МСК)</p>
							</div>

							<div class="p24-contacts-block">
								<h3 class="p24-contacts-block__heading">Email</h3>
								<a href="mailto:info@pass24.online" class="p24-contacts-block__value">
									info@pass24.online
								</a>
							</div>

							<div class="p24-contacts-block">
								<h3 class="p24-contacts-block__heading">Мессенджеры</h3>
								<div class="p24-contacts-messengers">
									<a href="https://t.me/pass24" target="_blank" rel="noopener noreferrer" class="p24-contacts-messenger">
										<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
										Telegram
									</a>
									<a href="https://wa.me/74951234567" target="_blank" rel="noopener noreferrer" class="p24-contacts-messenger">
										<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91C21.95 6.45 17.5 2 12.04 2zm5.82 14.01c-.24.68-1.41 1.27-1.95 1.35-.5.07-1.14.1-1.83-.12-.42-.13-.96-.31-1.65-.61-2.88-1.24-4.76-4.15-4.9-4.34-.14-.19-1.13-1.5-1.13-2.87 0-1.37.72-2.04.97-2.32.25-.28.55-.35.74-.35h.53c.17 0 .4-.07.63.48.24.56.81 1.98.88 2.12.07.14.12.31.02.5-.1.19-.14.31-.28.48-.14.17-.3.37-.42.5-.14.14-.29.3-.12.58.17.28.74 1.22 1.58 1.97 1.09.97 2.01 1.27 2.29 1.41.28.14.44.12.6-.07.17-.19.71-.83.9-1.12.19-.28.38-.24.63-.14.26.1 1.63.77 1.91.91.28.14.47.21.53.33.07.12.07.68-.17 1.36z"/></svg>
										WhatsApp
									</a>
								</div>
							</div>

							<div class="p24-contacts-block">
								<h3 class="p24-contacts-block__heading">Офис</h3>
								<p class="p24-contacts-block__value">Москва, Россия</p>
							</div>

							<div class="p24-contacts-block">
								<a href="/demo/" class="p24-btn p24-btn-primary" style="width: 100%; justify-content: center;">
									Запросить демонстрацию
								</a>
							</div>

						</div>


						<!-- Правая колонка: форма обратной связи -->
						<div class="p24-contacts-form-col">

							<div class="p24-contacts-form" id="p24-contacts-form">
								<h2 class="p24-h3" style="margin-bottom: 4px;">Напишите нам</h2>
								<p class="p24-small" style="margin-bottom: 24px;">Ответим в&nbsp;течение 2&nbsp;часов в&nbsp;рабочее время</p>

								<?php
								if ( function_exists( 'gravity_form' ) && class_exists( 'GFAPI' ) && GFAPI::get_form( 2 ) ) :
									gravity_form( 2, false, false, false, null, true, 0, true );
								else :
								?>

								<form id="p24-contact-form-el">
									<?php wp_nonce_field( 'pass24_contact', 'p24_contact_nonce' ); ?>

									<div class="p24-contacts-field">
										<label for="p24c-name">Имя <span class="p24-demo-required">*</span></label>
										<input type="text" id="p24c-name" name="name" placeholder="Алексей" required autocomplete="given-name">
									</div>

									<div class="p24-contacts-field-row">
										<div class="p24-contacts-field">
											<label for="p24c-email">Email <span class="p24-demo-required">*</span></label>
											<input type="email" id="p24c-email" name="email" placeholder="name@company.ru" required autocomplete="email">
										</div>
										<div class="p24-contacts-field">
											<label for="p24c-phone">Телефон</label>
											<input type="tel" id="p24c-phone" name="phone" placeholder="+7 (___) ___-__-__" autocomplete="tel">
										</div>
									</div>

									<div class="p24-contacts-field">
										<label for="p24c-subject">Тема</label>
										<select id="p24c-subject" name="subject">
											<option value="" disabled selected>Выберите тему</option>
											<option value="demo">Запрос демонстрации</option>
											<option value="pricing">Вопрос по тарифам</option>
											<option value="technical">Технический вопрос</option>
											<option value="partnership">Партнёрство</option>
											<option value="other">Другое</option>
										</select>
									</div>

									<div class="p24-contacts-field">
										<label for="p24c-message">Сообщение <span class="p24-demo-required">*</span></label>
										<textarea id="p24c-message" name="message" rows="4" placeholder="Опишите ваш вопрос или задачу" required></textarea>
									</div>

									<button type="submit" class="p24-btn p24-btn-primary p24-contacts-submit" style="width: 100%; justify-content: center;">
										Отправить сообщение
									</button>

									<p class="p24-small" style="text-align: center; margin-top: 12px; color: var(--p24-text-secondary);">
										Нажимая кнопку, вы&nbsp;соглашаетесь с&nbsp;<a href="/privacy/" style="color: var(--p24-primary);">политикой конфиденциальности</a>
									</p>
								</form>

								<!-- Success state -->
								<div class="p24-contacts-success" id="p24-contacts-success" style="display: none;">
									<div style="font-size: 48px; margin-bottom: 12px;">&#9989;</div>
									<h3 class="p24-h3" style="margin-bottom: 8px;">Сообщение отправлено</h3>
									<p class="p24-small">Мы ответим в&nbsp;течение 2&nbsp;часов в&nbsp;рабочее время</p>
								</div>

								<?php endif; ?>
							</div>

						</div>

					</div>
				</div>
			</section>


			<!-- ============================================================
			     КАРТА
			     ============================================================ -->

			<section class="p24-contacts-map">
				<div class="p24-contacts-map__embed">
					<!-- Яндекс.Карта или Google Maps embed — заменить на реальный код -->
					<div class="p24-contacts-map__placeholder">
						<div style="font-size: 48px; margin-bottom: 12px;">&#128205;</div>
						<p style="color: var(--p24-text-secondary);">Карта будет добавлена после указания точного адреса офиса</p>
					</div>
				</div>
			</section>


		</main>
	</div>
</div>

<?php
get_footer();
