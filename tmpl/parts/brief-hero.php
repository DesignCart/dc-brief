<?php
	defined('_JEXEC') or die;
?>
<div class="dc-mod-brief__descriptions-wrapper">
	<div class="dc-mod-brief__descriptions">
		<?php if(!empty($data['module_title'])): ?>
			<h2 class="dc-mod-brief__title">
				<?php echo htmlspecialchars($data['module_title'], ENT_QUOTES, 'UTF-8'); ?>
			</h2>
		<?php endif; ?>

		<?php if(!empty($data['module_desc'])): ?>
			<div class="dc-mod-brief__desc">
				<?php echo htmlspecialchars($data['module_desc'], ENT_QUOTES, 'UTF-8'); ?>
			</div>
		<?php endif; ?>

		<?php if(!empty($data['open_btn_label'])): ?>
			<button type="button" class="dc-mod-brief__open-btn" data-modal_id="<?php echo $uid; ?>-modal">
				<?php echo htmlspecialchars($data['open_btn_label'], ENT_QUOTES, 'UTF-8'); ?>
			</button>
		<?php endif; ?>
	</div>
</div>