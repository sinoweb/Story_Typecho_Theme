<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
// 看不见错误就是没有错误
error_reporting(0);

// 获取默认时区
date_default_timezone_get(); 

// 功能
require_once 'libs/Tool.php';
Tool::requireFile(__DIR__ .'/libs/', 'php');

// 组件
Tool::requireFile(__DIR__ .'/libs/Widget/', 'php');
Tool::requireFile(__DIR__ .'/libs/Form/', 'php');

// 重写 Markdown 函数
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = ['Contents', 'contentEx'];
Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = ['Contents', 'excerptEx'];
// 自定义加载
Typecho_Plugin::factory('Widget_Archive')->header = ['Add_Config', 'header'];
Typecho_Plugin::factory('Widget_Archive')->footer = ['Add_Config', 'footer'];
Typecho_Plugin::factory('admin/write-post.php')->bottom = ['Add_Config', 'Button'];
Typecho_Plugin::factory('admin/write-page.php')->bottom = ['Add_Config', 'Button'];

/**
 * 主题初始化
 * 
 */
function themeInit($archive) {
	// 强制开启反垃圾保护来兼容ajax评论
	Helper::options()->commentsAntiSpam = true;
	// 强制用户文章最新评论显示在文章首页
	Helper::options()->commentsPageDisplay = 'first';
	// 将较新的评论显示在前面
	Helper::options()->commentsOrder= 'DESC';
	// 突破评论回复楼层限制
	Helper::options()->commentsMaxNestingLevels = 999;
	// 为文章或页面、post操作，且包含参数`themeAction=comment`(自定义)
	if($archive->is('single') && $archive->request->isPost() && $archive->request->is('themeAction=comment')){
		// 为添加评论的操作时
		Story_Comment::ajaxComment($archive);
	}
}

/**
 * 自定义加载
 * 
 */
class Add_Config
{
	/**
	 * 添加额外编辑器按钮
	 * 
	 */
	public static function Button()
	{
		echo '<script type="text/javascript" src="'.$GLOBALS['dir'].'/libs/libs.js"></script>';
	}
	
	/**
	 * 加载在头部
	 * 
	 * @return Widget_Archive
	 */
	public static function header($archive)
	{
		Typecho_Widget::widget('Widget_Options')->add_head();
	}
	
	/**
	 * 加载在尾部
	 * 
	 * @return Widget_Archive
	 */
	public static function footer($archive)
	{
		if ($archive->is('single')) {
			echo '<script>var url="'.$archive->permalink.'"</script>';
		}
		echo '<script src="'.$GLOBALS['dir'].'/assets/js/mian.js"></script>';
		echo '<script src="'.$GLOBALS['dir'].'/assets/js/ajax.js"></script>';
		Typecho_Widget::widget('Widget_Options')->add_body();
	}
}
?>