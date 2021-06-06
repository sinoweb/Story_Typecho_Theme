<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="search-dialog" class="mdui-dialog">
<div class="mdui-card-content">
<div class="mdui-toolbar">
<span class="mdui-typo-title">搜索</span>
<div class="mdui-toolbar-spacer"></div>
<a href="javascript:;" class="mdui-btn mdui-btn-icon" mdui-dialog-close="">
<i class="mdui-icon material-icons">close</i>
</a>
</div>
<div class="mdui-m-x-2 mdui-m-b-2">
<form class="mdui-textfield" method="post" action="<?php Tool::indexHome('/'); ?>">
<input type="text" name="s" class="form-control mdui-textfield-input" placeholder="输入一些关键字..." aria-label="Search">
<button type="submit" class="mdui-btn mdui-btn-block mdui-color-theme mdui-m-t-1">搜索</button>
</form>
</div>
</div>
</div>