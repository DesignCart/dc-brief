<?php
	defined('_JEXEC') or die;
?>
<div id="<?php echo $uid; ?>-modal" class="dc-brief__modal dc-brief-modal__dialog" role="dialog" aria-modal="true">
	<div class="dc-brief__backdrop" data-close="1"></div>
	<button type="button" class="dc-brief__close" aria-label="Close" title="Close"><i class="fa fa-times" ></i></button>
	<div class="dc-brief__panel" role="document">
		<div class="dc-brief__content">
			<div class="dc-brief__content-inner">
				<div class="dc-brief__body">
                    <?php if(!empty($data['modal_title'])): ?>
                        <h3 class="dc-brief-modal__title">
                            <?php echo htmlspecialchars($data['modal_title'], ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                    <?php endif; ?>

                    <?php if(!empty($data['modal_desc'])): ?>
                        <div class="dc-brief-modal__desc">
                            <?php echo htmlspecialchars($data['modal_desc'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

					<?php require __DIR__ . '/brief-form.php'; ?>

                    <div class="dc-brief__modal-footer">
                        <button type="button" class="dc-brief-modal__btn-cancel">
   						    <?php echo htmlspecialchars($data['btn_cancel'], ENT_QUOTES, 'UTF-8'); ?>
					    </button>

                        <button type="button" class="dc-brief-modal__btn-submit" data-modal="<?= $uid ?>_form">
   						    <?php echo htmlspecialchars($data['btn_submit'], ENT_QUOTES, 'UTF-8'); ?>
					    </button>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>