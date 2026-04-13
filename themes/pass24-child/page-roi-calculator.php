<?php
/**
 * Template: ROI Calculator (slug: roi-calculator)
 *
 * Калькулятор ROI для PASS24. Помогает потенциальным клиентам
 * рассчитать экономию от внедрения системы и рекомендует тариф.
 * Email-gate собирает лид после показа результатов.
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
					<span class="p24-badge" style="margin-bottom: 20px; display: inline-block;">Бесплатный инструмент</span>
					<h1 class="p24-h1" style="color: #ffffff;">Рассчитайте экономию<br>от PASS24 за 2 минуты</h1>
					<p class="p24-subtitle" style="color: rgba(255,255,255,0.8); max-width: 580px; margin: 0 auto;">
						Укажите параметры вашего объекта — калькулятор покажет сколько вы сэкономите
						на администрировании доступа и какой тариф подходит именно вам.
					</p>
				</div>
			</section>


			<!-- ============================================================
			     КАЛЬКУЛЯТОР
			     ============================================================ -->

			<section class="p24-section">
				<div class="p24-container">

					<div class="p24-roi-layout">

						<!-- ЛЕВАЯ КОЛОНКА: ВВОД ДАННЫХ -->
						<div class="p24-roi-inputs" id="roiInputs">
							<h2 class="p24-h3" style="margin-bottom: 1.5rem;">Параметры вашего объекта</h2>

							<div class="p24-form-group">
								<label class="p24-form-label" for="roiObjectType">Тип объекта</label>
								<select class="p24-form-select" id="roiObjectType" name="roiObjectType">
									<option value="zhk">Жилой комплекс (ЖК)</option>
									<option value="kp">Коттеджный посёлок (КП)</option>
									<option value="bc">Бизнес-центр (БЦ)</option>
									<option value="office">Офис / предприятие</option>
									<option value="uk">Управляющая компания (УК)</option>
								</select>
							</div>

							<div class="p24-form-group">
								<label class="p24-form-label" for="roiPoints">Количество точек доступа</label>
								<select class="p24-form-select" id="roiPoints" name="roiPoints">
									<option value="5">До 5 точек (небольшой объект)</option>
									<option value="20" selected>5–20 точек (средний объект)</option>
									<option value="50">20–50 точек (крупный объект)</option>
									<option value="100">Более 50 точек (мультиобъект)</option>
								</select>
							</div>

							<div class="p24-form-group">
								<label class="p24-form-label" for="roiCurrentCost">
									Текущие расходы на СКУД / охрану, руб/мес
								</label>
								<input
									class="p24-form-input"
									type="number"
									id="roiCurrentCost"
									name="roiCurrentCost"
									value="50000"
									min="0"
									step="1000"
									placeholder="50000"
								>
							</div>

							<div class="p24-form-group">
								<label class="p24-form-label" for="roiAdminHours">
									Часов администратора на управление доступом в месяц
								</label>
								<input
									class="p24-form-input"
									type="number"
									id="roiAdminHours"
									name="roiAdminHours"
									value="20"
									min="0"
									step="1"
									placeholder="20"
								>
							</div>

							<button class="p24-btn p24-btn-primary" id="roiCalcBtn" style="width: 100%; margin-top: 0.5rem;">
								Рассчитать экономию
							</button>
						</div><!-- /.p24-roi-inputs -->


						<!-- ПРАВАЯ КОЛОНКА: РЕЗУЛЬТАТЫ -->
						<div class="p24-roi-results" id="roiResults" style="display: none;">
							<h2 class="p24-h3" style="margin-bottom: 1rem;">Результаты расчёта</h2>

							<!-- Рекомендуемый тариф -->
							<div class="p24-roi-plan-badge">
								<span style="color: var(--p24-text-secondary); font-size: 0.875rem;">Рекомендуемый тариф:</span>
								<span class="p24-h4" id="roiPlanBadge" style="margin: 0;"></span>
							</div>

							<!-- Ключевые метрики -->
							<div class="p24-roi-metrics">
								<div class="p24-roi-metric p24-roi-metric--accent">
									<div class="p24-roi-metric__label">Экономия в месяц</div>
									<div class="p24-roi-metric__value" id="roiMonthlySavings"></div>
								</div>
								<div class="p24-roi-metric">
									<div class="p24-roi-metric__label">Экономия в год</div>
									<div class="p24-roi-metric__value" id="roiYearlySavings"></div>
								</div>
								<div class="p24-roi-metric">
									<div class="p24-roi-metric__label">Часов высвобождается</div>
									<div class="p24-roi-metric__value" id="roiAdminHoursSaved"></div>
								</div>
							</div>

							<!-- TCO сравнение за 3 года -->
							<div class="p24-roi-tco">
								<div class="p24-roi-tco__title">Совокупная стоимость за 3 года (TCO)</div>
								<div class="p24-roi-tco__cols">
									<div class="p24-roi-tco__col p24-roi-tco__col--current">
										<div class="p24-roi-tco__col-label">Текущее решение</div>
										<div class="p24-roi-tco__col-value" id="roiTcoCurrent"></div>
									</div>
									<div class="p24-roi-tco__col p24-roi-tco__col--pass24">
										<div class="p24-roi-tco__col-label">PASS24</div>
										<div class="p24-roi-tco__col-value" id="roiTcoPass24"></div>
									</div>
								</div>
							</div>

							<!-- Email gate -->
							<div class="p24-roi-email-gate" id="roiEmailForm">
								<p class="p24-roi-email-gate__title">
									Получите персональное предложение и полный отчёт на email
								</p>

								<div class="p24-form-group">
									<input
										class="p24-form-input"
										type="text"
										id="roiName"
										name="roiName"
										placeholder="Ваше имя"
									>
								</div>
								<div class="p24-form-group">
									<input
										class="p24-form-input"
										type="email"
										id="roiEmail"
										name="roiEmail"
										placeholder="Email *"
									>
								</div>
								<div class="p24-form-group">
									<input
										class="p24-form-input"
										type="tel"
										id="roiPhone"
										name="roiPhone"
										placeholder="Телефон"
									>
								</div>

								<button class="p24-btn p24-btn-accent" id="roiSubmitBtn" style="width: 100%;">
									Получить предложение
								</button>

								<label class="p24-form-consent">
									<input type="checkbox" name="consent" id="roiConsent" required>
									<span>Я&nbsp;даю <a href="/privacy/" target="_blank">согласие на&nbsp;обработку персональных данных</a></span>
								</label>
							</div>

							<!-- Успех после отправки -->
							<div class="p24-roi-email-success" id="roiEmailSuccess" style="display: none;">
								<div class="p24-success-icon" aria-hidden="true">&#10003;</div>
								<p class="p24-h4" style="margin: 0.75rem 0 0.25rem;">Отлично! Заявка принята</p>
								<p style="color: var(--p24-text-secondary); font-size: 0.9rem;">
									Менеджер свяжется с вами в течение рабочего дня и подготовит персональное предложение.
								</p>
								<a href="/demo/" class="p24-btn p24-btn-primary" style="margin-top: 1rem; display: inline-block;">
									Записаться на демо
								</a>
							</div>

							<button class="p24-btn p24-btn-secondary" id="roiRecalcBtn" style="width: 100%; margin-top: 1rem;">
								Пересчитать
							</button>
						</div><!-- /.p24-roi-results -->

					</div><!-- /.p24-roi-layout -->

				</div>
			</section>


			<!-- ============================================================
			     ПОЧЕМУ PASS24 ВЫГОДНЕЕ
			     ============================================================ -->

			<section class="p24-section p24-section-gray">
				<div class="p24-container">
					<div class="p24-section-header" style="text-align: center;">
						<h2 class="p24-h2">Почему PASS24 выгоднее</h2>
						<p class="p24-subtitle">Три причины, по которым управляющие компании переходят на PASS24</p>
					</div>

					<div class="p24-grid-3">

						<div class="p24-card">
							<div style="margin-bottom: 1rem;">
								<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--p24-primary)" stroke-width="1.5">
									<path d="M12 2L2 7l10 5 10-5-10-5z"/>
									<path d="M2 17l10 5 10-5"/>
									<path d="M2 12l10 5 10-5"/>
								</svg>
							</div>
							<h3 class="p24-h4">Облако без замены оборудования</h3>
							<p style="color: var(--p24-text-secondary); font-size: 0.9rem;">
								PASS24 работает с существующими шлагбаумами, домофонами и камерами.
								Запуск за 1 час без капитальных вложений в железо.
							</p>
						</div>

						<div class="p24-card">
							<div style="margin-bottom: 1rem;">
								<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--p24-primary)" stroke-width="1.5">
									<circle cx="12" cy="12" r="10"/>
									<polyline points="12 6 12 12 16 14"/>
								</svg>
							</div>
							<h3 class="p24-h4">Автоматизация рутины</h3>
							<p style="color: var(--p24-text-secondary); font-size: 0.9rem;">
								Выдача пропусков, ведение журнала, уведомления жителей — всё автоматически.
								Администратор высвобождает до 70% времени на управление доступом.
							</p>
						</div>

						<div class="p24-card">
							<div style="margin-bottom: 1rem;">
								<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--p24-primary)" stroke-width="1.5">
									<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
								</svg>
							</div>
							<h3 class="p24-h4">Прозрачная аналитика</h3>
							<p style="color: var(--p24-text-secondary); font-size: 0.9rem;">
								Отчёты о посещаемости, нарушениях и загруженности в реальном времени.
								Принимайте решения на основе данных, а не ощущений.
							</p>
						</div>

					</div>
				</div>
			</section>


			<!-- ============================================================
			     CTA
			     ============================================================ -->

			<section class="p24-section p24-section-dark" style="text-align: center;">
				<div class="p24-container">
					<h2 class="p24-h2" style="color: #ffffff;">Убедитесь на практике — бесплатный пилот 14 дней</h2>
					<p class="p24-subtitle" style="color: rgba(255,255,255,0.8); max-width: 560px; margin: 0 auto 2rem;">
						Полный доступ к системе, обучение команды, персональная поддержка.
					</p>
					<a href="/demo/" class="p24-btn p24-btn-accent p24-btn-lg">Запустить пилот бесплатно</a>
				</div>
			</section>


		</main>
	</div>
</div>

<?php
get_footer();
