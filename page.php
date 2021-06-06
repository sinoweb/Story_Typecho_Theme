<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div class="mdui-container container">
<div class="mdui-row">
<div class="mdui-col-md-10 mdui-col-offset-md-1">
<article class="mdui-m-y-3 post">
<div class="mdui-card article-card <?php if (!empty($this->options->rgba) && in_array('PageRgba', $this->options->rgba)){echo 'rgba-card';} ?> mdui-shadow-1">
<div class="mdui-card-media">
<div class="mdui-card-primary-title mdui-text-color-white">
<img alt="<?php $this->title(); ?>" src="<?php Tool::showThumbnail($this); ?>">
<div class="mdui-card-media-covered mdui-card-media-covered-gradient">
<div class="mdui-card-primary mdui-typo mdui-typo-headline">
<div class="mdui-card-primary-title">
<?php $this->title(); ?>
</div>
</div>
</div>
</div>
</div>
<div class="mdui-divider"></div>
<div id="post" class="mdui-card-content">
<div class="mdui-typo">
<?php $this->content(); ?>
</div>
</div>
</div>
</article>
<?php if ($this->allow('comment')) $this->need('includes/comments.php'); ?>
</div>
</div>
</div>
<?php $this->need('footer.php'); ?>