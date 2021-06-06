<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$parameter = array(
'parentId' => $this->hidden ? 0 : $this->cid,
'parentContent' => $this->row,
'respondId' => $this->respondId,
'commentPage'=> $this->request->filter('int')->commentPage,
'allowComment' => $this->allow('comment')
);
$this->widget('Story_Comments_Archive', $parameter)->to($comments);
?>
<section id="comments">
<?php if($this->allow('comment')): ?>
<!--评论框-->
<div class="mdui-typo mdui-card mdui-m-y-3 mdui-shadow-1">
<?php $this->header('commentReply=1&description=0&keywords=0&generator=0&template=0&pingback=0&xmlrpc=0&wlw=0&rss2=0&rss1=0&antiSpam&atom'); ?>
<div id="<?php $this->respondId(); ?>" class="mdui-color-grey-100 respond">
<div class="mdui-card-content">
<div class="mdui-card-primary mdui-p-a-0">
<div class="mdui-card-primary-title">发表评论</div>
<div class="mdui-card-primary-subtitle"><?php $this->commentsNum('暂无评论，快来抢沙发吧！','已有 <span class="num">%d</span> 条评论'); ?>
</div>
</div>
<form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" class="mdui-m-a-0">
<?php if($this->user->hasLogin()): ?>
<div class="mdui-card-header mdui-p-x-0">
<img class="mdui-card-header-avatar" src="<?php Tool::avatr($this->user->mail); ?>">
<div class="mdui-card-header-title"><?php $this->user->screenName(); ?></div>
<div class="mdui-card-header-subtitle">以<a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>的身份评论</div>
<!-- <div class="mdui-card-menu">
<a href="<?php $this->options->logoutUrl(); ?>" class="mdui-btn mdui-btn-icon" mdui-tooltip="{content: '退出登录'}"><i class="mdui-icon material-icons mdui-text-color-black">power_settings_new</i></a>
</div> -->
</div>
<?php else: ?>
<div class="mdui-row">
<div class="mdui-textfield mdui-textfield-floating-label mdui-col-md-4">
<i class="mdui-icon material-icons">account_box</i>
<label class="mdui-textfield-label">* 名称</label> 
<input type="text" name="author" class="text mdui-textfield-input" placeholder="" value="<?php $this->remember('author'); ?>" tabindex="2" required>
<div class="mdui-textfield-error">名称不能为空</div>
</div>
<div class="mdui-textfield mdui-textfield-floating-label mdui-col-md-4">
<i class="mdui-icon material-icons">email</i>
<label class="mdui-textfield-label">* 邮箱</label> 
<input type="email" name="mail" class="text mdui-textfield-input" placeholder="" value="<?php $this->remember('mail'); ?>" tabindex="3" required>
<div class="mdui-textfield-error">邮箱不能为空</div>
</div>
<div class="mdui-textfield mdui-textfield-floating-label mdui-col-md-4">
<i class="mdui-icon material-icons">link</i>
<label class="mdui-textfield-label">网址</label> 
<input type="url" name="url" class="text mdui-textfield-input" placeholder="" value="<?php $this->remember('url'); ?>" tabindex="4">
</div>
</div>
<?php endif; ?>
<div class="mdui-textfield mdui-textfield-floating-label moe-comment-input-text mdui-textfield-has-bottom">
<i class="mdui-icon material-icons">textsms</i> 
<label class="mdui-textfield-label">写点什么吧</label> 
<textarea name="text" id="form-textarea" class="mdui-textfield-input" placeholder="" tabindex="1"><?php $this->remember('text'); ?></textarea>
</div>
<div class="mdui-text-right">
<?php $comments->cancelReply(); ?><button type="submit" class="submit mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-m-r-0" tabindex="5">发送</button>
</div>
</form>
</div>
</div>

<!--历史评论-->
<div class="mdui-card-content" <?php if (!$comments->have()): ?>style="padding: 0px;"<?php endif; ?>>
<?php $comments->listComments(array(
'before'=>  '<div class="comments-list">',
'after' =>  '</div>',
'dateFormat'=>  'Y-m-d H:i'
)); ?>
<nav class="mdui-text-center">
<?php $comments->pageNav('<i class="mdui-icon material-icons">chevron_left</i>', '<i class="mdui-icon material-icons">chevron_right</i>', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'mdui-btn-group pagination mdui-m-t-2', 'itemTag' => '','aClass' => 'mdui-btn','textClass' => 'mdui-btn', 'currentClass' => 'mdui-btn mdui-btn-active', 'prevClass' => 'mdui-btn', 'nextClass' => 'mdui-btn')); ?>
</nav>
</div>

</div>
<?php else: ?>
<div class="mdui-chip mdui-m-b-3">
<span class="mdui-chip-icon"><i class="mdui-icon material-icons">speaker_notes_off</i></span><span class="mdui-chip-title">评论已关闭</span>
</div>
<?php endif; ?>
</section>
<script>mdui.$('.master').on('click', function() {mdui.snackbar({message: '这是管理员：<?php echo $comments->author; ?>',position: 'right-top'});});</script>