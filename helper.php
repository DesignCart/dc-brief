<?php
	defined('_JEXEC') or die;

	use Joomla\CMS\Factory;
	use Joomla\CMS\Session\Session;
	use Joomla\Registry\Registry;
	use Joomla\CMS\Mailer\MailerFactoryInterface;

	class ModDcBriefHelper{
		public static function getData(Registry $params): array{
			return [
				// Content (moduł)
				'module_title'   => (string) $params->get('module_title', ''),
				'module_desc'    => (string) $params->get('module_desc', ''),
				'open_btn_label' => (string) $params->get('open_btn_label', ''),

				// Modal (teksty)
				'modal_title' => (string) $params->get('modal_title', ''),
				'modal_desc'  => (string) $params->get('modal_desc', ''),
				'btn_cancel'  => (string) $params->get('btn_cancel', ''),
				'btn_submit'  => (string) $params->get('btn_submit', ''),
				'input_error'  => (string) $params->get('input_error', ''),

				// Formularz dynamiczny
				'groups' => $params->get('groups', []),

				// Mail
				'mail_to'      => (string) $params->get('mail_to', ''),
				'mail_subject' => (string) $params->get('mail_subject', ''),
				'mail_intro'   => (string) $params->get('mail_intro', ''),
				'mail_success' => (string) $params->get('mail_success', ''),
				'mail_error'   => (string) $params->get('mail_error', ''),

				// reCAPTCHA v3 (twoja własna integracja)
				'captcha_v3_enable'    => (int) $params->get('captcha_v3_enable', 1),
				'captcha_v3_sitekey'   => (string) $params->get('captcha_v3_sitekey', ''),
				'captcha_v3_secret'    => (string) $params->get('captcha_v3_secret', ''),
				'captcha_v3_action'    => (string) $params->get('captcha_v3_action', 'dc_brief_submit'),
				'captcha_v3_threshold' => (float) $params->get('captcha_v3_threshold', 0.5),
			];
		}

		public static function sendBriefAjax(){
			$app = Factory::getApplication();

			// CSRF
			if (!Session::checkToken('post')) {
				return ['ok' => false, 'message' => 'Invalid token'];
			}

			$input = $app->input;

			// MAIL SETTINGS PRZYCHODZĄ Z JS (jak w starym: quiz_html/brief_html)
			$mailTo      = trim($input->getString('mail_to', ''));
			$subject     = trim($input->getString('mail_subject', ''));
			$mailIntro   = $input->get('mail_intro', '', 'RAW');

			// FORM DATA
			$groupsJson  = $input->getString('brief_groups', '');
			$groups      = json_decode($groupsJson, true);
			if (!is_array($groups)) $groups = [];

			if ($mailTo === '' || $subject === '') {
				return ['ok' => false, 'message' => 'Mail settings missing'];
			}

			// BODY: intro + dane formularza (bez stałych tekstów)
			$bodyParts = [];

			if ($mailIntro !== '') {
				$bodyParts[] = $mailIntro; // RAW z JS
			}

			foreach ($groups as $g) {
				$gTitle = trim((string) ($g['title'] ?? ''));
				$gDesc  = trim((string) ($g['description'] ?? ''));
				$fields = $g['fields'] ?? [];

				if ($gTitle === '' && $gDesc === '' && (!is_array($fields) || empty($fields))) {
					continue;
				}

				$bodyParts[] = '<hr>';

				if ($gTitle !== '') {
					$bodyParts[] = '<h3>' . htmlspecialchars($gTitle, ENT_QUOTES, 'UTF-8') . '</h3>';
				}

				if ($gDesc !== '') {
					$bodyParts[] = '<p>' . nl2br(htmlspecialchars($gDesc, ENT_QUOTES, 'UTF-8')) . '</p>';
				}

				if (is_array($fields) && !empty($fields)) {
					$bodyParts[] = '<table cellpadding="8" cellspacing="0" border="1" style="border-collapse:collapse;width:100%"><tbody>';

					foreach ($fields as $f) {
						$label = trim((string) ($f['label'] ?? ''));
						$value = $f['value'] ?? '';

						if (is_array($value)) $value = implode(', ', array_map('strval', $value));
						else $value = (string) $value;

						$label = trim($label);
						$value = trim((string) $value);

						if ($label === '' && $value === '') continue;

						$bodyParts[] =
							'<tr>' .
								'<td style="width:35%;vertical-align:top;"><strong>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</strong></td>' .
								'<td style="vertical-align:top;">' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</td>' .
							'</tr>';
					}

					$bodyParts[] = '</tbody></table>';
				}
			}

			$body = implode("\n", $bodyParts);

			$mailer = Factory::getMailer();
			$config = Factory::getConfig();

			$mailer->isHtml(true);
			$mailer->setSubject($subject);
			$mailer->setBody($body);

			$mailer->setSender([$config->get('mailfrom'), $config->get('fromname')]);
			$mailer->addRecipient($mailTo);

			$sent = $mailer->Send();

			if ($sent !== true) {
				return ['ok' => false, 'message' => 'Mail send failed'];
			}

			return ['ok' => true];
		}

}
