<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer id="footer" class="mdui-card mdui-color-theme<?php if($this->is('post')){echo ' mdui-shadow-0';} ?>">
<div class="mdui-card-content mdui-p-a-3">
<div class="mdui-container mdui-typo">
<div class="mdui-col-xs-12 mdui-text-center">
<?php $this->options->footerText(); ?>
<p class="mdui-m-a-0"><?php echo '<!--'.Tool::china_year(date('Y')).'-->'.' © '.date('Y'); ?> Powered By <a target="_blank" href="http://www.typecho.org">Typecho</a> Theme In <a target="blank" href="<?php Tool::index(''); ?>">Story</a></p>
</div>
</div>
</div>
</footer>