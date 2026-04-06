/**
 * PASS24 Phone Input Mask
 *
 * Auto-formats phone inputs as +7 (999) 999-99-99.
 * - Applies to all input[type="tel"] on the page.
 * - Pre-fills +7 on focus if empty.
 * - User can delete +7 to enter a different country code.
 * - No external dependencies.
 *
 * @package PASS24_Child
 */

(function () {
	'use strict';

	var MASK = '+7 (___) ___-__-__';
	var PREFIX = '+7';

	function getDigits(value) {
		return value.replace(/\D/g, '');
	}

	function formatPhone(digits) {
		// If starts with 7 or 8, treat as Russian number
		if (digits.length === 0) return '';

		// Normalize: if starts with 8, replace with 7
		if (digits.charAt(0) === '8' && digits.length > 1) {
			digits = '7' + digits.substring(1);
		}

		// If doesn't start with 7, prepend 7
		if (digits.charAt(0) !== '7' && digits.length <= 11) {
			digits = '7' + digits;
		}

		// Cap at 11 digits (Russian number)
		if (digits.charAt(0) === '7') {
			digits = digits.substring(0, 11);
		}

		var result = '+' + digits.charAt(0);

		if (digits.length > 1) {
			result += ' (' + digits.substring(1, Math.min(4, digits.length));
		}
		if (digits.length >= 4) {
			result += ') ';
		}
		if (digits.length > 4) {
			result += digits.substring(4, Math.min(7, digits.length));
		}
		if (digits.length > 7) {
			result += '-' + digits.substring(7, Math.min(9, digits.length));
		}
		if (digits.length > 9) {
			result += '-' + digits.substring(9, Math.min(11, digits.length));
		}

		return result;
	}

	function getCursorPosition(digits, prevDigits, prevPos) {
		// Count how many digits are before the cursor in the old value
		var digitsBefore = 0;
		for (var i = 0; i < prevPos && i < prevDigits.length; i++) {
			if (/\d/.test(prevDigits.charAt(i))) {
				digitsBefore++;
			}
		}

		// Find the position in formatted string that has the same number of digits before it
		var formatted = formatPhone(digits);
		var count = 0;
		for (var j = 0; j < formatted.length; j++) {
			if (/\d/.test(formatted.charAt(j))) {
				count++;
			}
			if (count >= digitsBefore) {
				return j + 1;
			}
		}

		return formatted.length;
	}

	function handleInput(e) {
		var input = e.target;
		var selStart = input.selectionStart || 0;
		var prevValue = input.value;
		var digits = getDigits(prevValue);

		var formatted = formatPhone(digits);
		if (formatted !== prevValue) {
			input.value = formatted;
			var newPos = getCursorPosition(digits, prevValue, selStart);
			input.setSelectionRange(newPos, newPos);
		}
	}

	function handleFocus(e) {
		var input = e.target;
		if (!input.value) {
			input.value = PREFIX + ' (';
			// Place cursor after +7 (
			setTimeout(function () {
				input.setSelectionRange(4, 4);
			}, 0);
		}
	}

	function handleBlur(e) {
		var input = e.target;
		// If only prefix remains, clear the field
		var digits = getDigits(input.value);
		if (digits.length <= 1) {
			input.value = '';
		}
	}

	function handleKeyDown(e) {
		var input = e.target;
		// Allow backspace to clear fully
		if (e.key === 'Backspace' && getDigits(input.value).length <= 1) {
			input.value = '';
			e.preventDefault();
		}
	}

	function handlePaste(e) {
		var input = e.target;
		var paste = (e.clipboardData || window.clipboardData).getData('text');
		var digits = getDigits(paste);

		if (digits.length > 0) {
			e.preventDefault();
			input.value = formatPhone(digits);
			var len = input.value.length;
			input.setSelectionRange(len, len);
		}
	}

	function initMask(input) {
		input.addEventListener('input', handleInput);
		input.addEventListener('focus', handleFocus);
		input.addEventListener('blur', handleBlur);
		input.addEventListener('keydown', handleKeyDown);
		input.addEventListener('paste', handlePaste);
		input.setAttribute('maxlength', '18');

		// Format existing value if present
		if (input.value) {
			var digits = getDigits(input.value);
			if (digits.length > 0) {
				input.value = formatPhone(digits);
			}
		}
	}

	// Init on DOM ready
	function init() {
		var inputs = document.querySelectorAll('input[type="tel"]');
		for (var i = 0; i < inputs.length; i++) {
			initMask(inputs[i]);
		}

		// Watch for dynamically added phone inputs
		if (window.MutationObserver) {
			var observer = new MutationObserver(function (mutations) {
				mutations.forEach(function (mutation) {
					mutation.addedNodes.forEach(function (node) {
						if (node.nodeType !== 1) return;
						if (node.matches && node.matches('input[type="tel"]')) {
							initMask(node);
						}
						var children = node.querySelectorAll ? node.querySelectorAll('input[type="tel"]') : [];
						for (var j = 0; j < children.length; j++) {
							initMask(children[j]);
						}
					});
				});
			});
			observer.observe(document.body, { childList: true, subtree: true });
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

})();
