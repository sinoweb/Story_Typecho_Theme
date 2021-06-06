<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<header class="mdui-appbar mdui-headroom <?php if ($this->options->topbar == 1): ?>mdui-appbar-fixed<?php endif; ?><?php if ($this->options->topbar == 0): ?>mdui-appbar-fixed mdui-appbar-scroll-hide<?php endif; ?> <?php if (empty($this->options->primaryColor)){echo 'mdui-color-white';}?>">
<div class="mdui-toolbar mdui-p-x-5 mdui-color-theme">
<button class="mdui-btn mdui-btn-icon" mdui-drawer="{target:'#left-drawer',overlay:true,swipe:true}"><i class="mdui-icon material-icons">menu</i></button>
<a href="<?php Tool::index(''); ?>" class="mdui-typo-title"><?php $this->options->title(); ?></a>
<div class="mdui-toolbar-spacer"></div>
<button class="mdui-btn mdui-btn-icon" mdui-dialog="{target: '#search-dialog'}"><i class="mdui-icon material-icons">search</i></button>
<?php if (($this->options->night_js == 0) || ($this->options->night_js == 1)): ?>
<button class="mdui-btn mdui-btn-icon" id="darktoggle_btn" onclick="darkToggle()"><i class="mdui-icon material-icons" id="darktoggle_icon">brightness_4</i></button>
<?php endif; ?>
<?php if($this->user->hasLogin()): ?>
<button class="mdui-btn mdui-btn-icon" mdui-menu="{target: '#settings',align:'right',covered:false}"><i class="mdui-icon material-icons">settings</i></button>
<ul class="mdui-menu" id="settings">
<li class="mdui-menu-item"><a href="<?php $this->options->adminUrl(); ?>" target="_blank"><i class="mdui-menu-item-icon mdui-icon material-icons">settings_applications</i>进入后台</a></li>
<li class="mdui-menu-item"><a href="<?php $this->options->adminUrl(); ?>options-theme.php" target="_blank"><i class="mdui-menu-item-icon mdui-icon material-icons">color_lens</i>主题设置</a></li>
<li class="mdui-menu-item"><a href="<?php $this->options->adminUrl(); ?>manage-posts.php" target="_blank"><i class="mdui-menu-item-icon mdui-icon material-icons">edit</i>管理文章</a></li>
<li class="mdui-menu-item"><a href="<?php $this->options->logoutUrl(); ?>"><i class="mdui-menu-item-icon mdui-icon material-icons">settings_ethernet</i>退出登录</a></li>
</ul>
<?php else: ?>
<button class="mdui-btn mdui-btn-icon" mdui-dialog="{target: '#login-dialog'}"><i class="mdui-icon material-icons">account_circle</i></button>
<?php endif; ?>
</div>
</header>