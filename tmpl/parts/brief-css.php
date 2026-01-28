<?php
	defined('_JEXEC') or die;

	$cssVars = [
		/* Module */
		'--brief-mod-bg' => (string) $params->get('mod_bg', '#ffffff'),
		'--brief-mod-title-color' => (string) $params->get('mod_title_color', '#111111'),
		'--brief-mod-desc-color' => (string) $params->get('mod_desc_color', '#444444'),
		'--brief-mod-title-size' => (int) $params->get('mod_title_size', 20) . 'px',
		'--brief-mod-desc-size' => (int) $params->get('mod_desc_size', 14) . 'px',

		/* Open button */
		'--brief-open-btn-bg' => (string) $params->get('open_btn_bg', '#111111'),
		'--brief-open-btn-bg-hover' => (string) $params->get('open_btn_bg_hover', '#000000'),
		'--brief-open-btn-color' => (string) $params->get('open_btn_color', '#ffffff'),
		'--brief-open-btn-color-hover' => (string) $params->get('open_btn_color_hover', '#ffffff'),
		'--brief-open-btn-size' => (int) $params->get('open_btn_size', 14) . 'px',

		/* Modal */
		'--brief-modal-bg' => (string) $params->get('modal_bg', '#ffffff'),
		'--brief-modal-title-color' => (string) $params->get('modal_title_color', '#111111'),
		'--brief-modal-desc-color' => (string) $params->get('modal_desc_color', '#444444'),
		'--brief-modal-title-size' => (int) $params->get('modal_title_size', 18) . 'px',
		'--brief-modal-desc-size' => (int) $params->get('modal_desc_size', 14) . 'px',

		/* Modal buttons: Cancel */
		'--brief-cancel-btn-bg' => (string) $params->get('cancel_btn_bg', '#e5e5e5'),
		'--brief-cancel-btn-bg-hover' => (string) $params->get('cancel_btn_bg_hover', '#d5d5d5'),
		'--brief-cancel-btn-color' => (string) $params->get('cancel_btn_color', '#111111'),
		'--brief-cancel-btn-color-hover' => (string) $params->get('cancel_btn_color_hover', '#111111'),
		'--brief-cancel-btn-size' => (int) $params->get('cancel_btn_size', 14) . 'px',

		/* Modal buttons: Submit */
		'--brief-submit-btn-bg' => (string) $params->get('submit_btn_bg', '#111111'),
		'--brief-submit-btn-bg-hover' => (string) $params->get('submit_btn_bg_hover', '#000000'),
		'--brief-submit-btn-color' => (string) $params->get('submit_btn_color', '#ffffff'),
		'--brief-submit-btn-color-hover' => (string) $params->get('submit_btn_color_hover', '#ffffff'),
		'--brief-submit-btn-size' => (int) $params->get('submit_btn_size', 14) . 'px',

		/* Form */
		'--brief-label-color' => (string) $params->get('label_color', '#111111'),
		'--brief-label-size' => (int) $params->get('label_size', 13) . 'px',
		'--brief-input-bg' => (string) $params->get('input_bg', '#ffffff'),
		'--brief-input-border' => (string) $params->get('input_border', '#cccccc'),
		'--brief-input-text-color' => (string) $params->get('input_text_color', '#111111'),
		'--brief-input-text-size' => (int) $params->get('input_text_size', 14) . 'px',
		'--brief-input-radius' => (int) $params->get('input_radius', 8) . 'px',
		'--brief-input-py' => (int) $params->get('input_padding_y', 10) . 'px',
		'--brief-input-px' => (int) $params->get('input_padding_x', 12) . 'px',
	];

	$styleAttr = '';
	foreach ($cssVars as $k => $v) {
		$styleAttr .= $k . ':' . htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8') . ';';
	}

	$uidSafe = htmlspecialchars((string) ($uid ?? ''), ENT_QUOTES, 'UTF-8');
	if ($uidSafe === '') {
		// Fallback (nie powinno się zdarzyć, ale niech nie rozwali widoku)
		$uidSafe = 'dcb_unknown';
	}
	?>
	
	<style>
		
		/* Scoped styles per module instance */
		#<?= $uidSafe ?>.dc-mod-brief { background: var(--brief-mod-bg); }

		#<?= $uidSafe ?> .dc-mod-brief__title { color: var(--brief-mod-title-color); font-size: var(--brief-mod-title-size); }
		#<?= $uidSafe ?> .dc-mod-brief__desc { color: var(--brief-mod-desc-color); font-size: var(--brief-mod-desc-size); }

		#<?= $uidSafe ?> .dc-mod-brief__open-btn { background: var(--brief-open-btn-bg); color: var(--brief-open-btn-color); font-size: var(--brief-open-btn-size) !important; }
		#<?= $uidSafe ?> .dc-mod-brief__open-btn:hover { background: var(--brief-open-btn-bg-hover); color: var(--brief-open-btn-color-hover); }

		/* Modal */
		/*#<?= $uidSafe ?> .dc-brief-modal__dialog { background: var(--brief-modal-bg); }*/
		#<?= $uidSafe ?> .dc-brief-modal__title { color: var(--brief-modal-title-color); font-size: var(--brief-modal-title-size); }
		#<?= $uidSafe ?> .dc-brief-modal__desc { color: var(--brief-modal-desc-color); font-size: var(--brief-modal-desc-size); }

		#<?= $uidSafe ?> .dc-brief-modal__btn-cancel { background: var(--brief-cancel-btn-bg); color: var(--brief-cancel-btn-color); font-size: var(--brief-cancel-btn-size); }
		#<?= $uidSafe ?> .dc-brief-modal__btn-cancel:hover { background: var(--brief-cancel-btn-bg-hover); color: var(--brief-cancel-btn-color-hover); }

		#<?= $uidSafe ?> .dc-brief-modal__btn-submit { background: var(--brief-submit-btn-bg); color: var(--brief-submit-btn-color); font-size: var(--brief-submit-btn-size); }
		#<?= $uidSafe ?> .dc-brief-modal__btn-submit:hover { background: var(--brief-submit-btn-bg-hover); color: var(--brief-submit-btn-color-hover); }

		/* Form */
		#<?= $uidSafe ?> .dc-brief-form label { color: var(--brief-label-color); font-size: var(--brief-label-size); }

		#<?= $uidSafe ?> .dc-brief-form input[type="text"],
		#<?= $uidSafe ?> .dc-brief-form input[type="email"],
		#<?= $uidSafe ?> .dc-brief-form input[type="number"],
		#<?= $uidSafe ?> .dc-brief-form input[type="tel"],
		#<?= $uidSafe ?> .dc-brief-form input[type="url"],
		#<?= $uidSafe ?> .dc-brief-form select,
		#<?= $uidSafe ?> .dc-brief-form textarea {
			background: var(--brief-input-bg);
			border: 1px solid var(--brief-input-border);
			color: var(--brief-input-text-color);
			font-size: var(--brief-input-text-size);
			border-radius: var(--brief-input-radius);
			padding: var(--brief-input-py) var(--brief-input-px);
			width: 100%;
			box-sizing: border-box;
		}

		#<?= $uidSafe ?> .dc-brief-form textarea { min-height: 120px; resize: vertical; }
	</style>
