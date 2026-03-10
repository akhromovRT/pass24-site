<?php
/**
 * Template: Solution Configurator (slug: configurator)
 *
 * 4-шаговый визард подбора тарифа PASS24 по параметрам объекта.
 * Собирает лид после показа рекомендованного плана.
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

			<section class="p24-section p24-section-dark">
				<div class="p24-container" style="text-align: center;">
					<span class="p24-badge" style="margin-bottom: 20px; display: inline-block;">2 минуты</span>
					<h1 class="p24-h1" style="color: #ffffff;">Конфигуратор решений PASS24</h1>
					<p class="p24-subtitle" style="color: rgba(255,255,255,0.8); max-width: 580px; margin: 0 auto;">
						Ответьте на 4 вопроса об объекте — и мы подберём оптимальный тариф
						и покажем, что именно вы получите.
					</p>
				</div>
			</section>


			<!-- ============================================================
			     КОНФИГУРАТОР
			     ============================================================ -->

			<section class="p24-section">
				<div class="p24-container">

					<div class="p24-cfg-wrapper">

						<!-- ПРОГРЕСС -->
						<div class="p24-cfg-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25">
							<div class="p24-cfg-progress__fill" id="cfgProgressFill" style="width: 25%;"></div>
						</div>
						<p class="p24-cfg-progress__label" id="cfgProgressLabel">Шаг 1 из 4</p>


						<!-- ШАГ 1: Тип объекта -->
						<div class="p24-cfg-step" id="cfgStep1">
							<h2 class="p24-h3">Какой у вас объект?</h2>

							<div class="p24-cfg-options" data-field="objectType" role="group" aria-label="Тип объекта">

								<label class="p24-cfg-option">
									<input type="radio" name="objectType" value="zhk">
									<span class="p24-cfg-option__label">Жилой комплекс (ЖК)</span>
								</label>

								<label class="p24-cfg-option">
									<input type="radio" name="objectType" value="kp">
									<span class="p24-cfg-option__label">Коттеджный посёлок (КП)</span>
								</label>

								<label class="p24-cfg-option">
									<input type="radio" name="objectType" value="bc">
									<span class="p24-cfg-option__label">Бизнес-центр (БЦ)</span>
								</label>

								<label class="p24-cfg-option">
									<input type="radio" name="objectType" value="office">
									<span class="p24-cfg-option__label">Офис / предприятие</span>
								</label>

								<label class="p24-cfg-option">
									<input type="radio" name="objectType" value="parking">
									<span class="p24-cfg-option__label">Парковка</span>
								</label>

								<label class="p24-cfg-option">
									<input type="radio" name="objectType" value="uk">
									<span class="p24-cfg-option__label">Управляющая компания (УК)</span>
								</label>

							</div>

							<div class="p24-cfg-nav">
								<button class="p24-btn p24-btn-primary p24-cfg-next" data-next="2" disabled>
									Далее
								</button>
							</div>
						</div><!-- /#cfgStep1 -->


						<!-- ШАГ 2: Количество точек доступа -->
						<div class="p24-cfg-step" id="cfgStep2" hidden>
							<h2 class="p24-h3">Сколько точек доступа?</h2>

							<div class="p24-cfg-options" data-field="accessPoints" role="group" aria-label="Количество точек доступа">

								<label class="p24-cfg-option">
									<input type="radio" name="accessPoints" value="5">
									<span class="p24-cfg-option__label">До 5 точек</span>
								</label>

								<label class="p24-cfg-option">
									<input type="radio" name="accessPoints" value="20">
									<span class="p24-cfg-option__label">5–20 точек</span>
								</label>

								<label class="p24-cfg-option">
									<input type="radio" name="accessPoints" value="50">
									<span class="p24-cfg-option__label">20–50 точек</span>
								</label>

								<label class="p24-cfg-option">
									<input type="radio" name="accessPoints" value="100">
									<span class="p24-cfg-option__label">Более 50 точек</span>
								</label>

							</div>

							<div class="p24-cfg-nav">
								<button class="p24-btn p24-btn-secondary p24-cfg-back" data-back="1">
									Назад
								</button>
								<button class="p24-btn p24-btn-primary p24-cfg-next" data-next="3" disabled>
									Далее
								</button>
							</div>
						</div><!-- /#cfgStep2 -->


						<!-- ШАГ 3: Методы доступа -->
						<div class="p24-cfg-step" id="cfgStep3" hidden>
							<h2 class="p24-h3">Методы доступа</h2>
							<p style="color: var(--p24-text-secondary); margin-bottom: 1.25rem;">Выберите все, которые нужны (можно несколько)</p>

							<div class="p24-cfg-options p24-cfg-options--multi" data-field="methods" role="group" aria-label="Методы доступа">

								<label class="p24-cfg-option">
									<input type="checkbox" name="methods" value="app">
									<span class="p24-cfg-option__label">Мобильное приложение</span>
								</label>

								<label class="p24-cfg-option">
									<input type="checkbox" name="methods" value="qr">
									<span class="p24-cfg-option__label">QR-код</span>
								</label>

								<label class="p24-cfg-option">
									<input type="checkbox" name="methods" value="card">
									<span class="p24-cfg-option__label">Карта / брелок</span>
								</label>

								<label class="p24-cfg-option">
									<input type="checkbox" name="methods" value="bio">
									<span class="p24-cfg-option__label">Биометрия</span>
								</label>

								<label class="p24-cfg-option">
									<input type="checkbox" name="methods" value="lpr">
									<span class="p24-cfg-option__label">Распознавание номеров (LPR)</span>
								</label>

							</div>

							<div class="p24-cfg-nav">
								<button class="p24-btn p24-btn-secondary p24-cfg-back" data-back="2">
									Назад
								</button>
								<button class="p24-btn p24-btn-primary p24-cfg-next" data-next="4">
									Далее
								</button>
							</div>
						</div><!-- /#cfgStep3 -->


						<!-- ШАГ 4: Интеграции -->
						<div class="p24-cfg-step" id="cfgStep4" hidden>
							<h2 class="p24-h3">Нужны интеграции?</h2>
							<p style="color: var(--p24-text-secondary); margin-bottom: 1.25rem;">Выберите все, которые планируете использовать</p>

							<div class="p24-cfg-options p24-cfg-options--multi" data-field="integrations" role="group" aria-label="Интеграции">

								<label class="p24-cfg-option">
									<input type="checkbox" name="integrations" value="none">
									<span class="p24-cfg-option__label">Интеграции не нужны</span>
								</label>

								<label class="p24-cfg-option">
									<input type="checkbox" name="integrations" value="cctv">
									<span class="p24-cfg-option__label">Видеонаблюдение (CCTV)</span>
								</label>

								<label class="p24-cfg-option">
									<input type="checkbox" name="integrations" value="intercom">
									<span class="p24-cfg-option__label">Домофон</span>
								</label>

								<label class="p24-cfg-option">
									<input type="checkbox" name="integrations" value="parking">
									<span class="p24-cfg-option__label">Парковочная система</span>
								</label>

								<label class="p24-cfg-option">
									<input type="checkbox" name="integrations" value="skud">
									<span class="p24-cfg-option__label">СКУД (сторонняя)</span>
								</label>

							</div>

							<div class="p24-cfg-nav">
								<button class="p24-btn p24-btn-secondary p24-cfg-back" data-back="3">
									Назад
								</button>
								<button class="p24-btn p24-btn-primary" id="cfgShowResults">
									Показать результат
								</button>
							</div>
						</div><!-- /#cfgStep4 -->


						<!-- РЕЗУЛЬТАТЫ -->
						<div class="p24-cfg-results" id="cfgResults" hidden>

							<div class="p24-cfg-results__plan">
								<p class="p24-cfg-plan-hint">Рекомендуемый тариф</p>
								<p class="p24-cfg-plan-name" id="cfgPlanName"></p>
								<p class="p24-cfg-plan-price" id="cfgPlanPrice"></p>
							</div>

							<ul class="p24-cfg-feature-list" id="cfgResultFeatures"></ul>

							<!-- Контактная форма -->
							<div class="p24-cfg-contact" id="cfgContactForm">
								<p class="p24-h4" style="margin-bottom: 1rem;">Получите персональное предложение</p>

								<div class="p24-cfg-contact-form">
									<input
										class="p24-form-input"
										type="text"
										id="cfgName"
										name="cfgName"
										placeholder="Ваше имя"
									>
									<input
										class="p24-form-input"
										type="tel"
										id="cfgPhone"
										name="cfgPhone"
										placeholder="Телефон *"
									>
									<input
										class="p24-form-input"
										type="email"
										id="cfgEmail"
										name="cfgEmail"
										placeholder="Email"
									>

									<button class="p24-btn p24-btn-primary" id="cfgSubmitBtn" style="width: 100%;">
										Получить предложение
									</button>

									<p class="p24-form-privacy">
										Нажимая кнопку, вы соглашаетесь с <a href="/privacy/" style="color: var(--p24-primary);">политикой конфиденциальности</a>
									</p>
								</div>
							</div>

							<!-- Успех -->
							<div class="p24-cfg-contact-success" id="cfgContactSuccess" hidden>
								<p class="p24-h4" style="margin: 0 0 0.5rem;">Заявка принята!</p>
								<p style="color: var(--p24-text-secondary);">
									Менеджер свяжется с вами в течение рабочего дня.
								</p>
								<a href="/demo/" class="p24-btn p24-btn-primary" style="margin-top: 1rem; display: inline-block;">
									Записаться на демо
								</a>
							</div>

							<button class="p24-btn p24-btn-secondary" id="cfgRestartBtn" style="width: 100%; margin-top: 1.5rem;">
								Начать заново
							</button>

						</div><!-- /#cfgResults -->

					</div><!-- /.p24-cfg-wrapper -->

				</div>
			</section>


		</main>
	</div>
</div>

<?php
get_footer();
