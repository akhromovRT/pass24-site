/**
 * PASS24 Segment Personalization — подмена Hero + CTA по UTM-сегменту
 *
 * Работает на главной странице. Читает ?segment= из URL или cookie.
 * Подменяет заголовок, подзаголовок и CTA под сегмент клиента.
 * Дефолтный контент (general) остаётся для SEO и при отсутствии сегмента.
 *
 * Все тексты хардкодные (не пользовательский ввод) — XSS невозможен.
 *
 * Использование в рекламе:
 *   pass24pro.ru/?segment=zhk
 *   pass24pro.ru/?segment=kp
 *   pass24pro.ru/?segment=bc
 *   pass24pro.ru/?segment=logistics
 *
 * @package PASS24_Child
 */
(function () {
	'use strict';

	var SEGMENTS = {
		zhk: {
			title: 'Облачная СКУД для жилых комплексов',
			subtitle: 'Жители создают пропуска за 5 секунд в приложении. Охрана перестаёт тратить время на звонки \u2014 минус 70% нагрузки. Подключение за 1 час. Без замены оборудования.',
			cta_primary: 'Попробовать бесплатно для ЖК',
			cta_secondary: 'Демо для управляющих компаний',
			checklist: ['\u221270% звонков на охрану', 'Пропуск за 5 секунд', 'От 14 990 \u20BD/мес']
		},
		kp: {
			title: 'Автоматический въезд в коттеджный посёлок',
			subtitle: 'Распознавание номеров за 0,5 сек \u2014 шлагбаум открывается без охранника. Цифровые ключи вместо копируемых карт. Гостевые пропуска через Telegram. Пилот 2 недели бесплатно.',
			cta_primary: 'Попробовать бесплатно для КП',
			cta_secondary: 'Посмотреть кейс Агаларов Эстейт',
			cta_secondary_href: '/cases/agalarov/',
			checklist: ['Проезд за 0,5 сек', '0 копий карт', 'Без охранника на КПП']
		},
		bc: {
			title: 'Контроль доступа для бизнес-центра',
			subtitle: 'Турникеты + QR + мобильные ключи в одной системе. Каждый арендатор управляет своими сотрудниками, вы видите всё. Подключение за 1 час, от 10 990 \u20BD/мес.',
			cta_primary: 'Попробовать бесплатно для БЦ',
			cta_secondary: 'Демо для бизнес-центров',
			checklist: ['Мульти-арендатор', 'QR + NFC + BLE', 'От 10 990 \u20BD/мес']
		},
		logistics: {
			title: 'Единый контроль доступа для логистики',
			subtitle: 'Автоматический учёт транспорта на всех КПП \u2014 камеры распознают номера 24/7. Электронные пропуска для подрядчиков с автоистечением. MLP управляет 14 комплексами через одну панель.',
			cta_primary: 'Попробовать бесплатно для склада',
			cta_secondary: 'Посмотреть кейс MLP',
			cta_secondary_href: '/cases/mlp/',
			checklist: ['14 комплексов в одной панели', 'Учёт транспорта 24/7', 'Временные пропуска']
		}
	};

	var COOKIE_NAME = 'p24_segment';
	var COOKIE_DAYS = 30;

	function getUrlParam(name) {
		return new URLSearchParams(window.location.search).get(name);
	}

	function getCookie(name) {
		var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
		return match ? decodeURIComponent(match[2]) : '';
	}

	function setCookie(name, value, days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 86400000));
		document.cookie = name + '=' + encodeURIComponent(value) +
			'; expires=' + date.toUTCString() +
			'; path=/; SameSite=Lax';
	}

	function applySegment(seg) {
		var data = SEGMENTS[seg];
		if (!data) return;

		var h1 = document.querySelector('#hero .p24-h1');
		var subtitle = document.querySelector('#hero .p24-subtitle');
		var ctaPrimary = document.querySelector('#hero .p24-btn-primary');
		var ctaSecondary = document.querySelector('#hero .p24-btn-secondary');
		var checklist = document.querySelector('#hero .p24-cta-checklist');

		if (h1) h1.textContent = data.title;
		if (subtitle) subtitle.textContent = data.subtitle;
		if (ctaPrimary) ctaPrimary.textContent = data.cta_primary;
		if (ctaSecondary) {
			ctaSecondary.textContent = data.cta_secondary;
			if (data.cta_secondary_href) {
				ctaSecondary.href = data.cta_secondary_href;
			}
		}
		if (checklist && data.checklist) {
			while (checklist.firstChild) { checklist.removeChild(checklist.firstChild); }
			data.checklist.forEach(function (item) {
				var span = document.createElement('span');
				span.className = 'p24-cta-checklist__item';
				span.textContent = item;
				checklist.appendChild(span);
			});
		}
	}

	// Main: read segment from URL param or cookie
	var segment = getUrlParam('segment') || getCookie(COOKIE_NAME);

	if (segment && SEGMENTS[segment]) {
		setCookie(COOKIE_NAME, segment, COOKIE_DAYS);
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', function () { applySegment(segment); });
		} else {
			applySegment(segment);
		}
	}
})();
