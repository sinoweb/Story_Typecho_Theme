<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<aside class="mdui-drawer mdui-color-white mdui-drawer-full-height sidebar mdui-drawer-close" id="left-drawer">
<div class="mdui-list mdui-text-color-theme-text">
<li class="mdui-subheader">Category</li>
<a href="<?php Tool::index(''); ?>" class="mdui-list-item">
<i class="mdui-list-item-icon mdui-icon material-icons">near_me</i>
<div class="mdui-list-item-content">首页</div>
</a>
<?php $this->widget('Widget_Metas_Category_List')->to($categorys); ?>
<?php while($categorys->next()): ?>
<?php if ($categorys->levels === 0): ?>
<?php $children = $categorys->getAllChildren($categorys->mid); ?>
<?php if (empty($children)) { ?>
<a href="<?php $categorys->permalink(); ?>" class="mdui-list-item">
<i class="mdui-list-item-icon mdui-icon material-icons"><?php Tool::Categorys_icon(); ?></i>
<div class="mdui-list-item-content"><?php $categorys->name(); ?></div>
</a>
<?php } else { ?>
<div class="mdui-collapse" mdui-collapse="{ accordion: true}">
<div class="mdui-collapse-item">
<div class="mdui-collapse-item-header mdui-list-item">
<i class="mdui-list-item-icon mdui-icon material-icons"><?php Tool::Categorys_icon(); ?></i>
<div class="mdui-list-item-content"><?php $categorys->name(); ?></div>
<i class="mdui-collapse-item-arrow mdui-list-item-icon mdui-icon material-icons ">keyboard_arrow_down</i>
</div>
<div class="mdui-collapse-item-body">
<ul class="mdui-list mdui-list-dense">
<?php foreach ($children as $mid) { ?>
<?php $child = $categorys->getCategory($mid); ?>
<li class="mdui-list-item">
<a href="<?php echo $child['permalink'] ?>" class="mdui-list-item-content mdui-text-color-theme-secondary"><?php echo $child['name']; ?></a>
</li>
<?php } ?>
</ul>
</div>
</div>
</div>
<?php } ?>
<?php endif; ?>
<?php endwhile; ?>
<li class="mdui-subheader">Pages</li>
<?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
<?php while($pages->next()): ?>
<a href="<?php $pages->permalink(); ?>" class="mdui-list-item">
<i class="mdui-list-item-icon mdui-icon material-icons"><?php Tool::Pages_icon(); ?></i>
<div class="mdui-list-item-content"><?php $pages->title(); ?></div>
</a>
<?php endwhile; ?>
<?php if (!empty($this->options->customUrl)): ?>
<li class="mdui-subheader">Custom</li>
<?php Tool::sidebarNav(); ?>
<?php endif; ?>
</div>
</aside>