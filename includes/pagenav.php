<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php if ($this->parameter->pageSize > 1 ): ?>
<?php if ($this->options->pagenav == 1): ?>
<div class="mdui-text-color-theme-accent mdui-m-b-3">
<nav class="page-nav mdui-row">
<div class="mdui-col-xs-6">
<?php $this->pageLink('<span class="mdui-btn mdui-color-theme-accent mdui-m-r-1">上一页</span>'); ?>
<?php $this->pageLink('<span class="mdui-btn mdui-color-theme-accent">下一页</span>','next'); ?>
</div>
<div class="mdui-col-xs-6">
<div class="mdui-typo mdui-text-right"><span mdui-tooltip="{content:'共有<?php echo ceil($this->getTotal());?>篇文章'}">页码 <?php if($this->_currentPage>1) echo $this->_currentPage;  else echo 1;?> / <?php echo ceil($this->getTotal() / $this->parameter->pageSize); ?></span></div>
</div>
</nav>
</div>
<?php endif; ?>
<?php if ($this->options->pagenav == 0): ?>
<nav class="mdui-m-y-3 mdui-text-center">
<?php $this->pageNav('<i class="mdui-icon material-icons">chevron_left</i>', '<i class="mdui-icon material-icons">chevron_right</i>', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'mdui-btn-group pagination', 'itemTag' => '','aClass' => 'mdui-btn','textClass' => 'mdui-btn', 'currentClass' => 'mdui-btn mdui-btn-active', 'prevClass' => 'mdui-btn', 'nextClass' => 'mdui-btn')); ?>
</nav>
<?php endif; ?>
<?php endif; ?>