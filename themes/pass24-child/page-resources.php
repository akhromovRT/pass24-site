<?php
/**
 * Template: Resources Hub (slug: resources)
 *
 * Хаб-страница со ссылками на инструменты и материалы:
 * ROI-калькулятор, конфигуратор решений, гайды.
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


			<!-- HERO -->
			<section class="p24-section p24-section-dark">
				<div class="p24-container" style="text-align: center;">
					<span class="p24-badge" style="margin-bottom: 20px; display: inline-block;">Бесплатные материалы</span>
					<h1 class="p24-h1" style="color: #ffffff;">Ресурсы PASS24</h1>
					<p class="p24-subtitle" style="color: rgba(255,255,255,0.8); max-width: 620px; margin: 0 auto;">
						Инструменты для расчёта экономии, подбора решения и планирования перехода на облачную СКУД
					</p>
				</div>
			</section>


			<!-- ИНСТРУМЕНТЫ -->
			<section class="p24-section">
				<div class="p24-container">
					<h2 class="p24-h2" style="text-align: center; margin-bottom: 12px;">Онлайн-инструменты</h2>
					<p class="p24-subtitle" style="text-align: center; max-width: 540px; margin: 0 auto 48px;">
						Рассчитайте экономию и подберите оптимальное решение за несколько минут
					</p>

					<div class="p24-grid-2" style="gap: 32px;">

						<!-- ROI-калькулятор -->
						<a href="/roi-calculator/" class="p24-resource-card">
							<div class="p24-resource-card__icon" style="background: var(--p24-primary-light);">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--p24-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<rect x="4" y="2" width="16" height="20" rx="2"/>
									<line x1="8" y1="6" x2="16" y2="6"/>
									<line x1="8" y1="10" x2="12" y2="10"/>
									<line x1="8" y1="14" x2="16" y2="14"/>
									<line x1="8" y1="18" x2="12" y2="18"/>
								</svg>
							</div>
							<div>
								<span class="p24-badge p24-badge-primary" style="margin-bottom: 8px; display: inline-block;">2 минуты</span>
								<h3 class="p24-h3" style="margin-bottom: 8px;">ROI-калькулятор</h3>
								<p style="color: var(--p24-text-secondary); font-size: 0.95rem; line-height: 1.6; margin: 0;">
									Укажите параметры объекта — узнайте экономию от перехода на PASS24 и подходящий тариф
								</p>
							</div>
							<span class="p24-resource-card__arrow">&rarr;</span>
						</a>

						<!-- Конфигуратор -->
						<a href="/configurator/" class="p24-resource-card">
							<div class="p24-resource-card__icon" style="background: rgba(16, 185, 129, 0.1);">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--p24-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="12" r="3"/>
									<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
								</svg>
							</div>
							<div>
								<span class="p24-badge p24-badge-accent" style="margin-bottom: 8px; display: inline-block;">4 шага</span>
								<h3 class="p24-h3" style="margin-bottom: 8px;">Конфигуратор решений</h3>
								<p style="color: var(--p24-text-secondary); font-size: 0.95rem; line-height: 1.6; margin: 0;">
									Ответьте на 4 вопроса об объекте — получите рекомендацию по тарифу и составу решения
								</p>
							</div>
							<span class="p24-resource-card__arrow">&rarr;</span>
						</a>

					</div>
				</div>
			</section>


			<!-- ГАЙДЫ -->
			<section class="p24-section p24-section-gray">
				<div class="p24-container">
					<h2 class="p24-h2" style="text-align: center; margin-bottom: 12px;">Руководства</h2>
					<p class="p24-subtitle" style="text-align: center; max-width: 540px; margin: 0 auto 48px;">
						Практические чек-листы для руководителей УК и служб эксплуатации
					</p>

					<div class="p24-grid-2" style="gap: 32px;">

						<!-- Гайд: Как выбрать СКУД -->
						<a href="/guide-choose-skud/" class="p24-resource-card">
							<div class="p24-resource-card__icon" style="background: var(--p24-primary-light);">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--p24-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
									<polyline points="14 2 14 8 20 8"/>
									<line x1="16" y1="13" x2="8" y2="13"/>
									<line x1="16" y1="17" x2="8" y2="17"/>
									<polyline points="10 9 9 9 8 9"/>
								</svg>
							</div>
							<div>
								<span class="p24-badge" style="margin-bottom: 8px; display: inline-block;">Чек-лист</span>
								<h3 class="p24-h3" style="margin-bottom: 8px;">Как выбрать облачную СКУД: 15 пунктов</h3>
								<p style="color: var(--p24-text-secondary); font-size: 0.95rem; line-height: 1.6; margin: 0;">
									Практический чек-лист для руководителей ЖК, КП и бизнес-центров
								</p>
							</div>
							<span class="p24-resource-card__arrow">&rarr;</span>
						</a>

						<!-- Гайд: Модернизация -->
						<a href="/guide-modernize/" class="p24-resource-card">
							<div class="p24-resource-card__icon" style="background: rgba(16, 185, 129, 0.1);">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--p24-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="23 4 23 10 17 10"/>
									<path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
								</svg>
							</div>
							<div>
								<span class="p24-badge" style="margin-bottom: 8px; display: inline-block;">Пошаговый план</span>
								<h3 class="p24-h3" style="margin-bottom: 8px;">Модернизация СКУД для управляющей компании</h3>
								<p style="color: var(--p24-text-secondary); font-size: 0.95rem; line-height: 1.6; margin: 0;">
									Пошаговый план перехода на облачное управление доступом без остановки объектов
								</p>
							</div>
							<span class="p24-resource-card__arrow">&rarr;</span>
						</a>

					</div>
				</div>
			</section>


			<!-- CTA -->
			<section class="p24-section">
				<div class="p24-container" style="text-align: center;">
					<h2 class="p24-h2" style="margin-bottom: 12px;">Нужна консультация?</h2>
					<p class="p24-subtitle" style="max-width: 500px; margin: 0 auto 32px;">
						Покажем систему на демо за 15 минут и ответим на все вопросы
					</p>
					<a href="/demo/" class="p24-btn p24-btn-primary p24-btn-lg">Запросить демо</a>
				</div>
			</section>


		</main>
	</div>
</div>

<?php get_footer(); ?>
