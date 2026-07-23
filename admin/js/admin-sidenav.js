/**
 * Toggle do menu lateral do admin (desktop + mobile).
 * Substitui o comportamento incompleto do Soft UI free (só g-sidenav-pinned).
 */
(function () {
	'use strict';

	var STORAGE_KEY = 'adm_sidenav_state';
	var MQ_DESKTOP = '(min-width: 1200px)';

	function isDesktop() {
		return window.matchMedia(MQ_DESKTOP).matches;
	}

	function ensureBackdrop() {
		var el = document.getElementById('admSidenavBackdrop');
		if (el) {
			return el;
		}
		el = document.createElement('div');
		el.id = 'admSidenavBackdrop';
		el.className = 'adm-sidenav-backdrop';
		el.setAttribute('aria-hidden', 'true');
		document.body.appendChild(el);
		el.addEventListener('click', function () {
			closeMobile();
		});
		return el;
	}

	function syncBackdrop() {
		var body = document.body;
		var backdrop = ensureBackdrop();
		var openMobile = !isDesktop() && body.classList.contains('g-sidenav-pinned');
		backdrop.classList.toggle('is-visible', openMobile);
		backdrop.setAttribute('aria-hidden', openMobile ? 'false' : 'true');
	}

	function persist() {
		var body = document.body;
		var state;
		if (isDesktop()) {
			state = body.classList.contains('g-sidenav-hidden') ? 'hidden' : 'show';
		} else {
			state = body.classList.contains('g-sidenav-pinned') ? 'pinned' : 'closed';
		}
		try {
			localStorage.setItem(STORAGE_KEY, state);
		} catch (e) { /* ignore */ }
	}

	function applyState(state) {
		var body = document.body;
		if (isDesktop()) {
			body.classList.remove('g-sidenav-pinned');
			if (state === 'hidden') {
				body.classList.add('g-sidenav-hidden');
			} else {
				body.classList.remove('g-sidenav-hidden');
			}
		} else {
			body.classList.remove('g-sidenav-hidden');
			if (state === 'pinned') {
				body.classList.add('g-sidenav-pinned');
			} else {
				body.classList.remove('g-sidenav-pinned');
			}
		}
		syncBackdrop();
	}

	function restore() {
		var stored = null;
		try {
			stored = localStorage.getItem(STORAGE_KEY);
		} catch (e) { /* ignore */ }

		if (isDesktop()) {
			applyState(stored === 'hidden' ? 'hidden' : 'show');
		} else {
			// mobile: começa fechado, a menos que o usuário tenha deixado aberto
			applyState(stored === 'pinned' ? 'pinned' : 'closed');
		}
	}

	function closeMobile() {
		document.body.classList.remove('g-sidenav-pinned');
		syncBackdrop();
		persist();
	}

	function toggle() {
		var body = document.body;
		if (isDesktop()) {
			body.classList.toggle('g-sidenav-hidden');
			body.classList.remove('g-sidenav-pinned');
		} else {
			body.classList.toggle('g-sidenav-pinned');
			body.classList.remove('g-sidenav-hidden');
		}
		syncBackdrop();
		persist();
	}

	function rewire(id) {
		var btn = document.getElementById(id);
		if (!btn || !btn.parentNode) {
			return;
		}
		// remove listeners do Soft UI
		var clone = btn.cloneNode(true);
		btn.parentNode.replaceChild(clone, btn);
		clone.addEventListener('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			toggle();
		});
	}

	function init() {
		rewire('iconNavbarSidenav');
		rewire('iconSidenav');
		ensureBackdrop();
		restore();

		window.addEventListener('resize', function () {
			// ao mudar breakpoint, normaliza classes
			var stored = null;
			try {
				stored = localStorage.getItem(STORAGE_KEY);
			} catch (e) { /* ignore */ }
			if (isDesktop()) {
				applyState(stored === 'hidden' ? 'hidden' : 'show');
			} else {
				applyState(stored === 'pinned' ? 'pinned' : 'closed');
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

	// Soft UI carrega no fim do body; garante rewire depois dele
	window.addEventListener('load', function () {
		rewire('iconNavbarSidenav');
		rewire('iconSidenav');
	});
})();
