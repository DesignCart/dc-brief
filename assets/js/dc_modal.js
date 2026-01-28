(function () {
	"use strict";

	function openModal(modal) {
		if (!modal) return;
		modal.classList.add("is-open");
		document.documentElement.classList.add("dc-brief-lock");
	}

	function closeModal(modal) {
		if (!modal) return;
		modal.classList.remove("is-open");
		if (!document.querySelector(".dc-brief__modal.is-open")) {
			document.documentElement.classList.remove("dc-brief-lock");
		}
	}

	function getRootFromModal(modal) {
		// zakładamy: modal jest w wrapperze modułu z id=$uid
		return modal.closest("[id]") || null;
	}

	// OPEN: klik w przycisk z data-modal_id
	document.addEventListener("click", function (e) {
		const btn = e.target?.closest?.(".dc-mod-brief__open-btn");
		if (!btn) return;

		e.preventDefault();

		const modalId = btn.getAttribute("data-modal_id");
		const modal = modalId ? document.getElementById(modalId) : null;

		if (!modal) {
			alert("Brief modal not found.");
			return;
		}

		openModal(modal);
	});

	// CLOSE: backdrop / X / cancel (nie trzeba znać $uid)
	document.addEventListener("click", function (e) {
		const t = e.target;

		// backdrop
		const backdrop = t?.closest?.('.dc-brief__backdrop[data-close="1"]');
		if (backdrop) {
			e.preventDefault();
			closeModal(backdrop.closest(".dc-brief__modal"));
			return;
		}

		// X close
		const x = t?.closest?.(".dc-brief__close");
		if (x) {
			e.preventDefault();
			closeModal(x.closest(".dc-brief__modal"));
			return;
		}

		// cancel close
		const cancel = t?.closest?.(".dc-brief-modal__btn-cancel");
		if (cancel) {
			e.preventDefault();
			closeModal(cancel.closest(".dc-brief__modal"));
			return;
		}
	});

	// ESC: zamyka aktualnie otwarty modal (pierwszy znaleziony)
	document.addEventListener("keydown", function (e) {
		if (e.key !== "Escape") return;
		const modal = document.querySelector(".dc-brief__modal.is-open");
		if (modal) closeModal(modal);
	});

	// SUBMIT: validate -> collect -> send
	document.addEventListener("click", function (e) {
		const btn = e.target?.closest?.(".dc-brief-modal__btn-submit");
		if (!btn) return;

		e.preventDefault();

		const modal = btn.closest(".dc-brief__modal");
		if (!modal) {
			alert("Brief modal not found.");
			return;
		}

		const root = getRootFromModal(modal);
		if (!root) {
			alert("Brief module root not found.");
			return;
		}

		// form po data-form (pewne)
		const formId = btn.getAttribute("data-form");
		const form = formId ? document.getElementById(formId) : modal.querySelector("form");

		if (!form) {
			alert("Brief form not found.");
			return;
		}

		if (typeof window.dc_validateBrief === "function") {
			const ok = window.dc_validateBrief(root);
			if (!ok) return;
		}

		if (typeof window.dc_getBrief === "function") {
			window.dc_getBrief(root);
		}

		if (typeof window.dc_sendBrief === "function") {
			window.dc_sendBrief(e, root);
			return;
		}

		alert("Send function is not available.");
	});
})();
