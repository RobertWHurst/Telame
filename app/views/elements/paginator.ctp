<div class="page_next_prev">
	<span class="page_prev">
		<?php echo $this->Paginator->prev(__('previous', true)); ?>
	</span>
	<span class="page_numbers">
		<?php echo $this->Paginator->numbers(); ?>
	</span>
	<span class="page_next">
		<?php echo $this->Paginator->next(__('next', true)); ?>
	</span>
</div>
<div class="page_number">
	<p><?php echo __('currently_on_page_', true) . $this->Paginator->counter(); ?></p>
</div>