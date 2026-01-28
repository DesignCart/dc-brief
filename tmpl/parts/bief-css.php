<?php
	defined('_JEXEC') or die;

	$cssVars = [
	'--brief-mod-bg' => $params->get('mod_bg', '#fff'),
	'--brief-mod-title-color' => $params->get('mod_title_color', '#111'),
	'--brief-mod-desc-color' => $params->get('mod_desc_color', '#444'),
	'--brief-mod-title-size' => (int) $params->get('mod_title_size', 20) . 'px',
	'--brief-mod-desc-size' => (int) $params->get('mod_desc_size', 14) . 'px',

	'--brief-open-btn-bg' => $params->get('open_btn_bg', '#111'),
	'--brief-open-btn-bg-hover' => $params->get('open_btn_bg_hover', '#000'),
	'--brief-open-btn-color' => $params->get('open_btn_color', '#fff'),
	'--brief-open-btn-color-hover' => $params->get('open_btn_color_hover', '#fff'),
	'--brief-open-btn-size' => (int) $params->get('open_btn_size', 14) . 'px',

	// modal
	'--brief-modal-bg' => $params->get('modal_bg', '#fff'),
	'--brief-modal-title-color' => $params->get('modal_title_color', '#111'),
	'--brief-modal-desc-color' => $params->get('modal_desc_color', '#444'),
	'--brief-modal-title-size' => (int) $params->get('modal_title_size', 18) . 'px',
	'--brief-modal-desc-size' => (int) $params->get('modal_desc_size', 14) . 'px',

	// form
	'--brief-label-color' => $params->get('label_color', '#111'),
	'--brief-label-size' => (int) $params->get('label_size', 13) . 'px',
	'--brief-input-bg' => $params->get('input_bg', '#fff'),
	'--brief-input-border' => $params->get('input_border', '#ccc'),
	'--brief-input-text-color' => $params->get('input_text_color', '#111'),
	'--brief-input-text-size' => (int) $params->get('input_text_size', 14) . 'px',
	'--brief-input-radius' => (int) $params->get('input_radius', 8) . 'px',
	'--brief-input-py' => (int) $params->get('input_padding_y', 10) . 'px',
	'--brief-input-px' => (int) $params->get('input_padding_x', 12) . 'px',
	];

	$styleAttr = '';
	foreach ($cssVars as $k => $v) {
		$styleAttr .= $k . ':' . htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8') . ';';
	}
?>

<style>
	.mod-brief { background: var(--brief-mod-bg); }
	.mod-brief__title { color: var(--brief-mod-title-color); font-size: var(--brief-mod-title-size); }
	.mod-brief__desc { color: var(--brief-mod-desc-color); font-size: var(--brief-mod-desc-size); }

	.mod-brief__open-btn {
	background: var(--brief-open-btn-bg);
	color: var(--brief-open-btn-color);
	font-size: var(--brief-open-btn-size);
	}
	.mod-brief__open-btn:hover {
	background: var(--brief-open-btn-bg-hover);
	color: var(--brief-open-btn-color-hover);
	}
</style>