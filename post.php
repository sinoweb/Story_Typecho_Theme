<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div class="mdui-container container">
<div class="mdui-row">
<div class="mdui-col-md-10 mdui-col-offset-md-1">
<article class="mdui-m-y-3 post">
<div class="mdui-card article-card<?php if (!empty($this->options->rgba) && in_array('PostRgba', $this->options->rgba)){echo ' rgba-card';} ?> mdui-shadow-1">
<div class="mdui-card-media">
<div class="mdui-card-primary-title mdui-text-color-white">
<img alt="<?php $this->title(); ?>" src="<?php Tool::showThumbnail($this); ?>">
<div class="mdui-card-media-covered">
<div class="mdui-card-primary mdui-typo mdui-typo-headline">
<div class="mdui-card-primary-title">
<?php $this->title(); ?>
</div>
<div class="mdui-card-primary-subtitle">
<span><?php Tool::get_post_view($this); ?> 次浏览</span> | <span class="to-comment"><?php $this->commentsNum(); ?> 条评论</span>
</div>
</div>
</div>
</div>
</div>
<div class="mdui-toolbar">
<div class="avatar">
<img class="mdui-card-header-avatar" src="<?php Tool::avatr($this->author->mail); ?>">
<span class="mdui-card-header-title mdui-typo">
<a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a>
</span>
<span class="mdui-card-header-subtitle">
<time datetime="<?php $this->date('Y-m-j h:i'); ?>" itemprop="datePublished"><?php $this->date('Y-m-j'); ?></time>
</span>
</div>
<div class="mdui-toolbar-spacer"></div>
<div class="toolbar-menu">
<button class="mdui-btn mdui-btn-icon" mdui-tooltip="{content: '分类'}" mdui-menu="{target: '#Category',align:'right',covered:false}"><i class="mdui-icon material-icons">more_vert</i></button>
<ul class="mdui-menu" id="Category">
<li class="mdui-menu-item mdui-text-center"><?php $this->category('</li><li class="mdui-menu-item mdui-text-center">', true, '无分类'); ?></li>
</ul>
</div>
</div>
<div class="mdui-divider"></div>
<div id="post" class="mdui-card-content">
<div class="mdui-typo">
<?php $this->content(); ?>
</div>
</div>
<div class="mdui-clearfix mdui-p-a-2 mdui-color-grey-200">
<?php $tags = Contents::getTags($this->cid);
$tag_html = '<div class="mdui-chip mdui-m-r-1"><span class="mdui-chip-icon"><i class="mdui-icon material-icons">bookmark</i></span><span class="mdui-chip-title">';
if (count($tags) > 0) {
	foreach ($tags as $tag) {
		echo $tag_html.'<a href="'.$tag['permalink'].'" rel="tag">'.$tag['name'].'</a></span></div>';
	}
}else{
	echo $tag_html.'<span>无标签</span></span></div>';
}
?>
</div>
</div>
</article>
<?php $this->need('includes/comments.php'); ?>
</div>
</div>
</div>
<?php $prev = Contents::thePrev($this); $next = Contents::theNext($this); ?>
<?php if(!empty($prev) || !empty($next)): ?>
<footer id="nav-direction" class="mdui-color-grey-200"<?php if (!empty($this->options->tools) && in_array('PostRgba', $this->options->tools)){echo ' style="opacity: .8;"';} ?>>
<div class="mdui-container">
<div class="mdui-row mdui-typo mdui-p-y-1">
<?php if(!empty($prev)): ?>
<a href="<?php $prev->permalink();?>" class="<?php if(empty($next)){echo 'mdui-col-xs-12';}else{echo 'mdui-col-xs-2 mdui-col-sm-6';$hidden='mdui-hidden-xs-down';} ?> mdui-text-left">
<div class="mdui-card-content mdui-text-color-black">
<i class="mdui-icon material-icons">arrow_back</i>
<span class="<?php echo $hidden ;?> mdui-typo-body-1-opacity">上一篇</span>
<div class="<?php echo $hidden ;?> mdui-typo-title mdui-text-truncate"><?php $prev->title();?></div>
</div>
</a>
<?php endif; ?>
<?php if(!empty($next)): ?>
<a href="<?php $next->permalink();?>" class="<?php if(empty($prev)){echo 'mdui-col-xs-12';}else{echo 'mdui-col-xs-10 mdui-col-sm-6';} ?> mdui-text-right">
<div class="mdui-card-content mdui-text-color-black">
<span class="mdui-typo-body-1-opacity">下一篇</span>
<i class="mdui-icon material-icons">arrow_forward</i>
<div class="mdui-typo-title mdui-text-truncate"><?php $next->title();?></div>
</div>
</a>
<?php endif; ?>
</div>
</div>
</footer>
<?php endif; ?>
<?php $this->need('footer.php'); ?>