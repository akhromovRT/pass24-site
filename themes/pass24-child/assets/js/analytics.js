/**
 * PASS24 Global Analytics — UTM cookies + shared helper for all forms
 *
 * Загружается на ВСЕХ страницах.
 * 1) Парсит UTM-параметры из URL, сохраняет в cookies на 30 дней.
 * 2) Экспортирует window.P24Analytics — глобальный helper для форм:
 *    - getUTM()                → объект UTM из cookies
 *    - getMetrikaClientID(cb)  → Yandex Metrika ClientID (async)
 *    - enrichFormData(data, cb)→ добавляет UTM + ClientID + page_url + referrer в данные формы
 *
 * @package PASS24_Child
 */
(function () {
	'use strict';

	var UTM_PARAMS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
	var COOKIE_DAYS = 30;
	var METRIKA_COUNTER = 108384915;
	var CLIENT_ID_TIMEOUT = 2000;

	function getUrlParam(name) {
		var params = new URLSearchParams(window.location.search);
		return params.get(name);
	}

	function setCookie(name, value, days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		document.cookie = name + '=' + encodeURIComponent(value) +
			'; expires=' + date.toUTCString() +
			'; path=/; SameSite=Lax';
	}

	function getCookie(name) {
		var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
		return match ? decodeURIComponent(match[2]) : '';
	}

	// Save UTM params from URL to cookies (30 days)
	var hasUtm = false;
	UTM_PARAMS.forEach(function (param) {
		var value = getUrlParam(param);
		if (value) {
			setCookie(param, value, COOKIE_DAYS);
			hasUtm = true;
		}
	});

	if (hasUtm) {
		setCookie('utm_landing', encodeURIComponent(window.location.pathname), COOKIE_DAYS);
	}

	// --- Global analytics helper for all forms ---
	window.P24Analytics = {
		getUTM: function () {
			var result = {};
			UTM_PARAMS.forEach(function (p) {
				var v = getCookie(p);
				if (v) result[p] = v;
			});
			return result;
		},

		getMetrikaClientID: function (cb) {
			if (!window.ym) { cb(null); return; }
			var done = false;
			var timer = setTimeout(function () {
				if (!done) { done = true; cb(null); }
			}, CLIENT_ID_TIMEOUT);
			window.ym(METRIKA_COUNTER, 'getClientID', function (cid) {
				if (!done) { done = true; clearTimeout(timer); cb(cid || null); }
			});
		},

		enrichFormData: function (data, cb) {
			var utm = window.P24Analytics.getUTM();
			for (var k in utm) { data[k] = utm[k]; }
			data.page_url = data.page_url || window.location.href;
			data.referrer = data.referrer || document.referrer;

			window.P24Analytics.getMetrikaClientID(function (cid) {
				if (cid) data.ym_client_id = cid;
				cb(data);
			});
		}
	};
})();
