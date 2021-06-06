<?php
/**
 * 使用<a target="_blank" href="https://www.mdui.org/"> MDUI </a>框架搭建
 * 一款基于Google Material Design风格设计的单栏主题
 * @package Story
 * @author 小人物
 * @version 1.0
 * @link https://rainyew.com
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<div class="mdui-container container">
<div class="mdui-row">
<div class="mdui-col-md-10 mdui-col-offset-md-1">
<?php if ($this->_currentPage<2 && !empty($this->options->indexBox)): ?>
<?php $this->need('includes/box.php'); ?>
<?php endif; while($this->next()): ?>
<?php $this->need('includes/main.php'); ?>
<?php endwhile; ?>
<?php $this->need('includes/pagenav.php'); ?>
</div>
</div>
</div>
<?php $this->need('footer.php'); ?>