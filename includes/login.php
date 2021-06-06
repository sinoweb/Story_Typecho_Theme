<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php if(empty($this->user->hasLogin())): ?>
<div id="login-dialog" class="article-card mdui-dialog" style="max-width: 450px;">
<div class="mdui-card-media">
<img src="<?php if (!empty($this->options->loginbg)){$this->options->loginbg();}else{Tool::showThumbnail($this,true);}?>" style="height: 200px;">
<div class="mdui-card-menu">
<button class="mdui-btn mdui-btn-icon" mdui-dialog-close="">
<i class="mdui-icon material-icons">close</i>
</button>
</div>
<div class="mdui-card-media-covered">
<div class="mdui-card-primary mdui-typo mdui-text-truncate mdui-typo-headline">
登录
</div>
</div>
</div>
<div class="mdui-card-content">
<form action="<?php $this->options->loginAction()?>" method="post" name="login" rold="form">
<input type="hidden" name="referer" value="<?php echo Tool::curPageURL(); ?>">
<div class="mdui-textfield mdui-textfield-floating-label mdui-textfield-has-bottom mdui-textfield-not-empty">
<label class="mdui-textfield-label">用户名</label>
<input class="mdui-textfield-input" type="text" id="name" name="name" autocomplete="username" placeholder="" required="">
<div class="mdui-textfield-error">用户名不能为空</div>
</div>
<div class="mdui-textfield mdui-textfield-floating-label mdui-textfield-has-bottom mdui-textfield-not-empty">
<label class="mdui-textfield-label">密码</label>
<input class="mdui-textfield-input" type="password" id="password" name="password" autocomplete="current-password" placeholder="" required="">
<div class="mdui-textfield-error">密码不能为空</div>
</div>
<div class="actions mdui-clearfix">
<label class="mdui-checkbox">
<input type="checkbox" class="custom-control-input" id="checkbox-signin" name="remember" value="1">
<i class="mdui-checkbox-icon"></i>
下次自动登录
</label>
<button type="submit" class="mdui-btn mdui-color-theme action-btn mdui-float-right">登录</button>
</div>
</form>
</div>
</div>
<?php endif; ?>