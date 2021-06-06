<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $get_dp = Contents::excerpt($this->getDescription(),200);?>
<?php if(!empty($this->options->favicon)){$favicon = $this->options->favicon();}else{$favicon = '/favicon.ico';}?>
<?php $meta_image = Tool::showThumbnail($this,false,true);?>
<!DOCTYPE html>
<html lang="zh">
<head>
<!-- Meta -->
<meta charset="<?php $this->options->charset(); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta itemprop="name" content="<?php if ($this->is('index')) : ?><?php $this->options->title(); ?><?php else: ?><?php $this->archiveTitle(array('category'=>_t('分类 %s 下的文章'),'search'=>_t('包含关键字 %s 的文章'),'tag' =>_t('标签 %s 下的文章'),'author'=>_t('%s 发布的文章')), '', ''); ?><?php endif; ?>">
<meta name="author" content="<?php $this->author(); ?>">
<meta name="keywords" content="<?php $k=$this->fields->k;if(empty($k) || !$this->is('single')){echo $this->keywords();}else{ echo $k;};?>" >
<meta name="description" content="<?php $d=$this->fields->d;if(empty($d) || !$this->is('single')){if($get_dp){echo $get_dp;}}else{ echo $d;};?>">
<meta property="og:title" content="<?php Contents::title($this); ?>">
<meta property="og:description" content="<?php $d=$this->fields->d;if(empty($d) || !$this->is('single')){if($get_dp){echo $get_dp;}}else{ echo $d;};?>">
<meta property="og:site_name" content="<?php Contents::title($this); ?>">
<meta property="og:type" content="<?php if($this->is('post') || $this->is('page')) echo 'article'; else echo 'website'; ?>">
<meta property="og:url" content="<?php if($this->is('single')){$this->permalink();}else{$this->options->rootUrl();} ?>">
<meta property="og:image" content="<?php if($this->is('single')){echo $meta_image;}else{echo $favicon;} ?>">
<meta property="article:published_time" content="<?php echo date('c', $this->created); ?>">
<meta property="article:modified_time" content="<?php echo date('c', $this->modified); ?>">
<meta name="twitter:title" content="<?php Contents::title($this); ?>">
<meta name="twitter:description" content="<?php $d=$this->fields->d;if(empty($d) || !$this->is('single')){if($get_dp){echo $get_dp;}}else{ echo $d;};?>">
<meta name="twitter:card" content="summary">
<meta name="twitter:image" content="<?php if($this->is('single')){echo $meta_image;}else{echo $favicon;} ?>">
<!-- Favicon -->
<link rel="icon" sizes="16x16" href="<?php echo $favicon; ?>">
<!-- 通过自有函数输出HTML头部信息 -->
<?php $this->header('keywords=&description=&commentReply=&'); ?>
<!-- Title -->
<title><?php if($this->_currentPage>1) echo '第 '.$this->_currentPage.' 页 - '; ?><?php Contents::title($this); ?></title>
<!-- 静态资源 -->
<link rel="stylesheet" type="text/css" href="<?php Tool::indexTheme('/assets/mdui/css/mdui.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php Tool::indexTheme('/assets/css/style.css'); ?>">
<!-- 优先加载必要js -->
<script src="<?php Tool::indexTheme('/assets/mdui/js/mdui.js'); ?>"></script>
<?php Tool::night(); ?>
<?php if ($this->options->bg): ?>
<!-- 背景图 -->
<style>
body {
	background-image: url(<?php $this->options->bg(); ?>);
	background-size: cover;
	background-position: center;
	background-attachment: fixed;
	background-repeat: no-repeat;
}
body.mdui-theme-layout-dark {
	background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),url(<?php $this->options->bg(); ?>);
}
</style>
<?php endif; ?>
<?php $this->head(); ?>
</head>
<body class="<?php if (empty($this->options->topbar == 2)){?>mdui-appbar-with-toolbar<?php } ?> <?php if (!empty($this->options->primaryColor)){ ?>mdui-theme-primary-<?php $this->options->primaryColor(); } ?> mdui-theme-accent-<?php $this->options->accentColor(); ?> mdui-loaded<?php if($this->options->night_js == 2){ echo ' mdui-theme-layout-dark';} ?>">
<?php $this->need('includes/topnav.php'); ?>
<?php $this->need('includes/sidebar.php'); ?>