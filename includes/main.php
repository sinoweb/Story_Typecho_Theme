<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<article class="mdui-m-y-3">
<div class="mdui-card article-card <?php if (!empty($this->options->rgba) && in_array('IndexRgba', $this->options->rgba)){echo 'rgba-card';} ?> mdui-shadow-1">
<div class="mdui-card-media">
<a class="mdui-card-primary-title mdui-text-color-white" href="<?php $this->permalink() ?>">
<img alt="<?php $this->title(); ?>" src="<?php Tool::showThumbnail($this); ?>">
<div class="mdui-card-media-covered">
<div class="mdui-card-primary mdui-typo">
<?php $this->title(); ?>
</div>
</div>
</a>
<?php Tool::ifPost_img($this); ?>
</div>
<div class="mdui-card-content">
<P class="mdui-m-a-0 h-3x">
<?php $this->excerpt(200, ''); ?>
</P>
</div>
<div class="mdui-divider"></div>
<div class="mdui-toolbar">
<div class="mdui-avatar">
<img class="mdui-card-header-avatar" src="<?php Tool::avatr($this->author->mail); ?>">
<span class="mdui-card-header-title mdui-typo">
<a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a>
</span>
<span class="mdui-card-header-subtitle"><time datetime="<?php $this->date('c'); ?>" itemprop="datePublished"><?php $this->date('Y-m-j'); ?></time></span>
</div>
<div class="mdui-toolbar-spacer"></div>
<div class="mdui-actions">
<a href="<?php $this->permalink() ?>" class="mdui-btn mdui-color-theme-accent">阅读全文</a>
</div>
</div>
</div>
</article>