<?php
	defined('_JEXEC') or die;

	use Joomla\CMS\Helper\ModuleHelper;
	use Joomla\CMS\Uri\Uri;
	use Joomla\CMS\Factory;

	require_once __DIR__ . '/helper.php';

	$doc = Factory::getDocument();

	// --- CSS ---
	$cssUrl  = Uri::root(true) . '/modules/mod_dc_brief/assets/css/dc_brief.css';
	$cssPath = JPATH_ROOT . '/modules/mod_dc_brief/assets/css/dc_brief.css';
	$cssVer  = is_file($cssPath) ? (string) filemtime($cssPath) : '1';
	$doc->addStyleSheet($cssUrl . '?v=' . $cssVer);

	// --- JS bundle (core + modal + mail) ---
	$cacheDir = JPATH_ROOT . '/modules/mod_dc_brief/cache';
	$cacheUrl = Uri::root(true) . '/modules/mod_dc_brief/cache';

	if (!is_dir($cacheDir)) {
		@mkdir($cacheDir, 0755, true);
	}

	// źródła
	$srcFiles = [
		JPATH_ROOT . '/modules/mod_dc_brief/assets/js/dc_brief.js',
		JPATH_ROOT . '/modules/mod_dc_brief/assets/js/dc_modal.js',
		JPATH_ROOT . '/modules/mod_dc_brief/assets/js/dc_mail.js',
	];

	// nazwa bundla (stała, bo to 1 moduł)
	$bundlePath = $cacheDir . '/dc_brief.bundle.js';
	$bundleUrl  = $cacheUrl . '/dc_brief.bundle.js';

	// sprawdź czy trzeba przebudować
	$needRebuild = !is_file($bundlePath);
	$bundleMtime = $needRebuild ? 0 : (int) filemtime($bundlePath);

	foreach ($srcFiles as $f) {
		if (is_file($f) && (int) filemtime($f) > $bundleMtime) {
			$needRebuild = true;
			break;
		}
	}

	// rebuild jeśli trzeba
	if ($needRebuild) {
		$parts = [];
		$parts[] = "/* DC BRIEF BUNDLE - generated: " . gmdate('c') . " */\n";

		foreach ($srcFiles as $f) {
			if (!is_file($f)) continue;

			$parts[] = "\n/* ===== " . basename($f) . " (mtime: " . gmdate('c', (int) filemtime($f)) . ") ===== */\n";
			$parts[] = file_get_contents($f) ?: '';
			$parts[] = "\n";
		}

		$tmp = $bundlePath . '.tmp';
		file_put_contents($tmp, implode('', $parts), LOCK_EX);
		@rename($tmp, $bundlePath);
	}

	// wersjonowanie
	$bundleVer = is_file($bundlePath) ? (string) filemtime($bundlePath) : '1';
	$doc->addScript($bundleUrl . '?v=' . $bundleVer, ['defer' => true]);

	$data = ModDcBriefHelper::getData($params);
	require ModuleHelper::getLayoutPath('mod_dc_brief', $params->get('layout', 'default'));
