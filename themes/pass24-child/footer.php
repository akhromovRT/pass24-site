<?php
/**
 * PASS24 Child Theme — footer.php
 *
 * Кастомный footer: навигация, контакты, соцсети, юридическая информация.
 *
 * @package PASS24_Child
 */

defined( 'ABSPATH' ) || exit;

// Закрывающие теги </main>, </div>, </div> уже в шаблонах (front-page.php, page-pricing.php).
// footer.php закрывает .site.grid-container (из header.php) и выводит <footer>.
?>

</div><!-- .site.grid-container (opened in header.php) -->

<?php do_action( 'generate_before_footer' ); ?>

<footer class="p24-footer" itemscope itemtype="https://schema.org/WPFooter">

	<!-- Основной блок footer -->
	<div class="p24-footer__main">
		<div class="p24-container">
			<div class="p24-footer__grid">

				<!-- Колонка 1: О компании -->
				<div class="p24-footer__col p24-footer__col--about">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="p24-footer__logo">
						PASS24.online
					</a>
					<p class="p24-footer__desc">
						Облачная система контроля и управления доступом. 300+ объектов по всей России.
					</p>
					<div class="p24-footer__socials">
						<a href="https://t.me/pass24" target="_blank" rel="noopener noreferrer" aria-label="Telegram">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" fill="currentColor"/></svg>
						</a>
						<a href="https://wa.me/74951234567" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91C21.95 6.45 17.5 2 12.04 2zm5.82 14.01c-.24.68-1.41 1.27-1.95 1.35-.5.07-1.14.1-1.83-.12-.42-.13-.96-.31-1.65-.61-2.88-1.24-4.76-4.15-4.9-4.34-.14-.19-1.13-1.5-1.13-2.87 0-1.37.72-2.04.97-2.32.25-.28.55-.35.74-.35h.53c.17 0 .4-.07.63.48.24.56.81 1.98.88 2.12.07.14.12.31.02.5-.1.19-.14.31-.28.48-.14.17-.3.37-.42.5-.14.14-.29.3-.12.58.17.28.74 1.22 1.58 1.97 1.09.97 2.01 1.27 2.29 1.41.28.14.44.12.6-.07.17-.19.71-.83.9-1.12.19-.28.38-.24.63-.14.26.1 1.63.77 1.91.91.28.14.47.21.53.33.07.12.07.68-.17 1.36z" fill="currentColor"/></svg>
						</a>
						<a href="https://vk.com/pass24" target="_blank" rel="noopener noreferrer" aria-label="ВКонтакте">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5.01 13.49h-1.3c-.49 0-.64-.39-1.52-1.28-.77-.74-1.1-.84-1.29-.84-.26 0-.34.07-.34.43v1.17c0 .31-.1.49-1.09.49-1.6 0-3.37-.97-4.62-2.79C5.42 10.56 5 8.73 5 8.41c0-.19.07-.36.43-.36h1.3c.32 0 .44.14.56.49.62 1.79 1.66 3.36 2.09 3.36.16 0 .23-.07.23-.48V9.85c-.05-.83-.49-.9-.49-1.2 0-.15.12-.3.32-.3h2.04c.27 0 .36.14.36.46v2.58c0 .27.12.36.2.36.16 0 .29-.09.58-.39 1.41-1.28 1.89-3.37 1.89-3.37.1-.19.27-.36.58-.36h1.3c.39 0 .48.2.39.46-.17.92-1.82 3.13-1.82 3.13-.15.24-.2.34 0 .61.14.19.63.61.95 1 .66.7 1.16 1.29 1.29 1.7.14.41-.07.62-.46.62z" fill="currentColor"/></svg>
						</a>
					</div>
				</div>

				<!-- Колонка 2: Продукты -->
				<div class="p24-footer__col">
					<h4 class="p24-footer__heading">Продукты</h4>
					<ul class="p24-footer__links">
						<li><a href="/products/bureau/">Бюро пропусков</a></li>
						<li><a href="/products/lpr/">Распознавание номеров</a></li>
						<li><a href="/products/digital-key/">Цифровой ключ</a></li>
						<li><a href="/products/parking/">Парковка</a></li>
						<li><a href="/products/analytics/">Аналитика</a></li>
					</ul>
				</div>

				<!-- Колонка 3: Решения -->
				<div class="p24-footer__col">
					<h4 class="p24-footer__heading">Решения</h4>
					<ul class="p24-footer__links">
						<li><a href="/solutions/residential-complex/">Жилые комплексы</a></li>
						<li><a href="/solutions/cottage-village/">Коттеджные посёлки</a></li>
						<li><a href="/solutions/business-center/">Бизнес-центры</a></li>
						<li><a href="/solutions/logistics/">Логистика</a></li>
						<li><a href="/solutions/production/">Производство</a></li>
					</ul>
				</div>

				<!-- Колонка 4: Компания -->
				<div class="p24-footer__col">
					<h4 class="p24-footer__heading">Компания</h4>
					<ul class="p24-footer__links">
						<li><a href="/about/">О компании</a></li>
						<li><a href="/blog/">Блог</a></li>
						<li><a href="/pricing/">Тарифы</a></li>
						<li><a href="/integrations/">Интеграции</a></li>
						<li><a href="/demo/">Запросить демо</a></li>
					</ul>
				</div>

				<!-- Колонка 5: Контакты -->
				<div class="p24-footer__col">
					<h4 class="p24-footer__heading">Контакты</h4>
					<ul class="p24-footer__links p24-footer__contacts">
						<li>
							<a href="tel:+74951234567">+7 (495) XXX-XX-XX</a>
						</li>
						<li>
							<a href="mailto:info@pass24.online">info@pass24.online</a>
						</li>
						<li>
							Москва, Россия
						</li>
					</ul>
					<a href="/demo/" class="p24-btn p24-btn-primary p24-btn-sm p24-footer__cta">
						Запросить демо
					</a>
				</div>

			</div>
		</div>
	</div>

	<!-- Нижняя полоса -->
	<div class="p24-footer__bottom">
		<div class="p24-container">
			<div class="p24-footer__bottom-inner">
				<div class="p24-footer__copy">
					&copy; <?php echo date( 'Y' ); ?> PASS24.online. Все права защищены.
				</div>
				<div class="p24-footer__legal">
					<a href="/privacy/">Политика конфиденциальности</a>
					<a href="/terms/">Условия использования</a>
					<span class="p24-footer__registry">Реестр российского ПО</span>
				</div>
			</div>
		</div>
	</div>

</footer>

<?php do_action( 'generate_after_footer' ); ?>

<?php wp_footer(); ?>

</body>
</html>
