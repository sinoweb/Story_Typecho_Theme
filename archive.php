<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php if ($this->have()): ?>
<?php $this->need('header.php'); ?>
<div class="mdui-container container">
<div class="mdui-row">
<div class="mdui-col-md-10 mdui-col-offset-md-1">
<div class="mdui-valign mdui-typo mdui-m-t-3">
<h1 class="mdui-center mdui-text-center mdui-m-a-0"><?php $this->archiveTitle('%s','',''); ?><br><small><?php
if (!empty($this->getDescription()) && $this->is('category')) {
	echo $this->getDescription();
}else{
	Tool::archive_dep($this); 
}
?></small></h1>
</div>
<?php while($this->next()): ?>
<?php $this->need('includes/main.php'); ?>
<?php endwhile; ?>
<?php $this->need('includes/pagenav.php'); ?>
</div>
</div>
</div>
<?php $this->need('footer.php'); ?>
<?php else: ?>
<?php $this->need('404.php'); ?>
<?php endif; ?>