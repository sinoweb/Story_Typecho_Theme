<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div class="mdui-container container">
<div class="mdui-row">
<div class="mdui-col-md-10 mdui-col-offset-md-1">
<div class="mdui-p-a-2 mdui-m-y-3 mdui-text-center not-found<?php if($this->options->bg){?> mdui-card<?php }else{$raised=' mdui-btn-raised';} ?><?php if ($this->options->bg && !empty($this->options->tools) && in_array('PageRgba', $this->options->tools)){echo ' rgba-card';} ?>">
<img src="<?php Tool::indexTheme('assets/img/butterfly.webp'); ?>" alt="404 NOT FOUND">
<div class="mdui-typo-headline-opacity mdui-p-a-1">抱歉，没有内容！</div>
<div class="mdui-card-primary-subtitle mdui-p-a-1">您检索的内容不存在或已被删除，点击下方按钮返回首页。</div>
<div class="mdui-card-actions">
<a href="<?php Tool::index(''); ?>" class="mdui-btn mdui-color-theme<?php echo $raised; ?>">
<i class="mdui-icon material-icons">reply_all</i> 回到首页</a>
</div>
</div>
</div>
</div>
</div>
<?php $this->need('footer.php');?>