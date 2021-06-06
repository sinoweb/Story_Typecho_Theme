<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$content = $this->options->indexBox;
$left_img = explode("$",$content)[0];
$right_img = explode("$",$content)[1];
?>
<div class="mdui-row">
<div class="mdui-col-sm-8">
<article class="mdui-m-t-3">
<div class="mdui-card article-card <?php if (!empty($this->options->tools) && in_array('IndexRgba', $this->options->tools)){echo 'rgba-card';} ?> mdui-shadow-1">
<div class="mdui-card-media">
<div class="mdui-card-primary-title">
<img src="<?php if (!empty($left_img)){echo $left_img;}else{Tool::showThumbnail($this,true);}?>" alt="<?php $this->options->title(); ?>">
</div>
<div class="mdui-card-media-covered mdui-card-media-covered-gradient">
<div class="mdui-card-primary">
<div class="mdui-card-primary-title"><?php Tool::My_dep(); ?></div>
</div>
</div>
</div>
<div class="mdui-card-header">
<img class="mdui-card-header-avatar" src="<?php Tool::avatr(Tool::getUserMail(1)); ?>" alt="<?php $this->author(); ?>">
<div class="mdui-card-header-title" style="line-height: 40px;"><?php $this->author(); ?></div>
</div>
</div>
</article>
</div>
<div class="mdui-col-sm-4">
<article class="mdui-m-t-3">
<div class="mdui-card article-card <?php if (!empty($this->options->tools) && in_array('IndexRgba', $this->options->tools)){echo 'rgba-card';} ?> mdui-shadow-1">
<div class="mdui-card-media">
<div class="mdui-card-primary-title">
<img src="<?php if (!empty($right_img)){echo $right_img;}else{Tool::showThumbnail($this);}?>" alt="<?php $this->author(); ?>">
</div>
</div>
<div class="mdui-card-header">
<div class="mdui-card-primary-title"><?php $this->options->title(); ?></div>
<div class="mdui-card-menu">
<button mdui-menu="{target:'#test',position:'top',align:'right',covered: false}" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">rss_feed</i></button>
<ul id="test" class="mdui-menu">
<li class="mdui-menu-item"><a target="_blank" href="<?php $this->options->feedUrl(); ?>atom/">Atom Feed</a></li>
<li class="mdui-menu-item"><a target="_blank" href="<?php $this->options->feedUrl(); ?>">文章RSS订阅</a></li>
<li class="mdui-menu-item"><a target="_blank" href="<?php $this->options->commentsFeedUrl(); ?>">评论RSS订阅</a></li>
</ul>
</div>
</div>
</div>
</div>
</article>
</div>