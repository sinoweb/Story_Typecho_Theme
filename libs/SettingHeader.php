<?php
class SettingHeader
{

	/**
	 * 随机选取背景颜色
	 * @return mixed
	 */
	public static function getBackgroundColor()
	{
		$colors = array(
			array('#673AB7', '#512DA8'),
			array('#20af42', '#1a9c39'),
			array('#336666', '#2d4e4e'),
			array('#2e3344', '#232735')
		);
		$randomKey = array_rand($colors, 1);
		$randomColor = $colors[$randomKey];
		return $randomColor;
	}

    /**
     * 用户初始化信息
     * @return string
     */
    public static function SettingsWelcome()
	{
		return self::useIntro();
	}

	
    /**
     * 输出模板相关信息
     * @return string
     */
    public static function useIntro()
	{
		$themecoloc = Typecho_Widget::widget('Widget_Options')->primaryColor;
		$themeaccent= Typecho_Widget::widget('Widget_Options')->accentColor;
		//备份检测
		$db = Typecho_Db::get();
		$str1 = explode('/themes/', Helper::options()->themeUrl);
		$str2 = explode('/', $str1[1]);
		$name = $str2[0];
        $backupInfo = "";
        if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:'.$name.'bf'))){
			$backupInfo = '<div class="mdui-chip" style="color: rgb(26, 188, 156);">
			<span class="mdui-chip-icon mdui-color-green"><i class="mdui-icon material-icons">&#xe8ba;</i></span>
			<span class="mdui-chip-title">数据库存在主题数据备份</span></div>';
        }else{
			$backupInfo = '<div class="mdui-chip" style="color: rgb(26, 188, 156);">
			<span class="mdui-chip-icon mdui-color-red"><i class="mdui-icon material-icons">&#xe8ba;</i></span>
			<span class="mdui-chip-title" style="color: rgb(255, 82, 82);">没有主题数据备份</span></div>';
        }
		//输出
        $html = <<<HTML
<div class="mdui-card-content mdui-theme-primary-{$themecoloc} mdui-theme-accent-{$themeaccent}">

  <!-- 卡片的标题和副标题 -->

<div class="mdui-card">

  <!-- 卡片的标题和副标题 -->
<div class="mdui-card-primary mdui-p-t-2">
    <div class="mdui-card-primary-title"><div class="mdui-card-primary-title mdui-text-color-theme-accent">Story For Typecho</div>
<div class="mdui-card-primary-subtitle">
一款使用Google Material Design风格的模板。简约而不简单，拥有强大且完善的功能，欢迎您体验！<br>
Tips：功能强大且简化操作，开箱即用型，给您更方便的设置。<!--<span class="mdui-text-color-red">红色字体</span>为必须配置项，--><span class="mdui-text-color-green">绿色字体</span>为建议配置项，黑色若无特殊需求保持默认即可。</div>
    <div class="mdui-card-primary-subtitle mdui-row mdui-row-gapless  mdui-p-t-1 mdui-p-l-1">
                <!--备份情况-->
                {$backupInfo}

     </div>
  </div>  
  <!-- 卡片的按钮 -->
	<div id="backup-btn" class="mdui-card-actions">
  
	<button class="mdui-btn showSettings" mdui-tooltip="{content:'展开所有设置后，使用ctrl+F 可以快速搜索🔍某一设置项'}">展开所有设置</button>
	<button class="mdui-btn hideSettings">折叠所有设置</button>

	<form style="display:inline-block" action="?'.$name.'bf" method="post">
	<input type="submit" name="type" class="mdui-btn back_up" mdui-tooltip="{content: '1. 仅仅是备份主题的外观数据</br>2. 切换主题的时候，虽然以前的外观设置的会清空但是备份数据不会被删除。</br>3. 所以当你切换回来之后，可以恢复备份数据。</br>4. 备份数据同样是备份到数据库中。</br>5. 如果已有备份数据，再次备份会覆盖之前备份'}" value="备份模板设置数据">
	<input type="submit" name="type" class="mdui-btn recover_back_up" mdui-tooltip="{content: '从主题备份恢复数据'}" value="还原模板设置数据">
	<input type="submit" name="type" onclick="javascript:return bk_del()" class="mdui-btn un_back_up" mdui-tooltip="{content: '删除备份数据'}" value="删除备份数据">
	</form>

	</div>
	
</div></div>
HTML;
	return $html;


	}

    /**
     * 输出到后台外观设置的css
     * @return string
     */
    public static function StyleOutput()
	{
        $randomColor = self::getBackgroundColor();
        //$randomColor[0] = "#fff";
        return <<<HTML
<style>
/*后台外观全局控制*/
.message{
    background-color:{$randomColor[0]} !important;
    color:#fff;
}
.success{
    background-color:{$randomColor[0]};
    color:#fff;
}
#typecho-nav-list{display:none;}
.typecho-head-nav {
    padding: 0 10px;
    background: {$randomColor[0]};
}
ul.typecho-option-tabs.fix-tabs.clearfix {
    background: {$randomColor[1]};
}
.col-mb-12 {
    padding: 0px!important;
}
.typecho-page-title {
    margin:0;
    height: 70px;
    background: {$randomColor[0]};
    background-size: cover;
    padding: 30px;
}
/*额外*/
.mdui-panel-item-sub-header{
    color: #999;
    margin-left: 25px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.typecho-option span{
    display: block;
}
.description {
    margin: .5em 0 0;
    color: #999;
    font-size: .92857em;
}
.description:hover{
    color:#333;
    transition: 0.3s;
}
.checking{
    margin-top: 10px;
}

#update_notification {
    margin-top: 10px;
}
button.btn.primary {
    display: none;
}
.mdui-btn[class*=mdui-color-]:hover, .mdui-fab[class*=mdui-color-]:hover {
    opacity: .87;
    background: #00BCD4;
}
label.settings-subtitle {
    color: #999;
    font-size: 10px;
    font-weight: normal;
}
.settingsbutton{
    margin-bottom:10px;
    display:block
}
.settingsbutton a{
    margin-right: 10px;
}

@media screen and (min-device-width: 1024px) {
    ::-webkit-scrollbar-track {
        background-color: rgba(255,255,255,0);
    }
    ::-webkit-scrollbar {
        width: 6px;
        background-color: rgba(255,255,255,0);
    }
    ::-webkit-scrollbar-thumb {
        border-radius: 3px;
        background-color: rgba(193,193,193,1);
    }
}
.row {
    margin: 0px;
}
code, pre, .mono {
    background: #e8e8e8;
}
#use-intro {
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);
    background-color: #fff;
    margin: 8px;
    padding: 8px;
    padding-left: 20px;
    margin-bottom: 40px;
}
.typecho-head-nav .operate a{
    border: none;
    padding-top: 0px;
    padding-bottom: 0px;
    color: rgba(255,255,255,.6);
}
.typecho-head-nav .operate a:hover {
    background-color: rgba(0, 0, 0, 0.05);
    color: #fff;
}
.typecho-page-title h2{
    margin: 0px;
    font-size: 2.28571em;
    color: #fff;
}
.typecho-option-tabs{
    padding: 0px;
    background: #fff;
}
.typecho-option-tabs a:hover{
    background-color: rgba(0, 0, 0, 0.05);
    color: rgba(255,255,255,.8);
}
.typecho-option-tabs a{
    border: none;
    height: auto;
    color: rgba(255,255,255,.6);
    padding: 15px;
}
li.current {
    background-color: #FFF;
    height: 4px;
    padding: 0 !important;
    bottom: 0px;
}
.typecho-option-tabs li.current a, .typecho-option-tabs li.active a{
    background:none;
}
.container{
    margin:0;
    padding:0;
}
.body.container {
    min-width: 100% !important;
    padding: 0px;
}
.typecho-option-tabs{
    margin:0;
}
.typecho-option-submit button {
    float: right;
    background: #00BCD4;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);
    color: #FFF;
}
.typecho-option-tabs li{
    margin-left:20px;
}
.typecho-option{
    border-radius: 3px;
    background: #fff;
    padding: 12px 16px;
}
.col-mb-12{
    padding-left: 0px!important;
}
.typecho-option-submit{
    background:none!important;
}
.typecho-option {
    float: left;
}
.typecho-option span {
    margin-right: 0;
}
.typecho-option label.typecho-label {
    font-weight: 500;
    margin-bottom: 10px;
    margin-top: 10px;
    font-size: 16px;
    padding-bottom: 5px;
    border-bottom: 1px solid rgba(0,0,0,0.2);
}
.typecho-page-main .typecho-option input.text {
    width: 100%;
}
input[type=text], textarea {
    border: none;
    border-bottom: 1px solid rgba(0,0,0,.60);
    outline: none;
    border-radius: 0;
}
.typecho-option-submit {
    position: fixed;
    right: 32px;
    bottom: 32px;
}
.typecho-foot {
    padding: 16px 40px;
    color: rgb(158, 158, 158);
    background-color: rgb(66, 66, 66);
    margin-top: 80px;
}
.typecho-option .description{
    font-weight: normal;
}
@media screen and (max-width: 480px){
.typecho-option {
    width: 94% !important;
    margin-bottom: 20px !important;
}
}
/*大标题样式控制*/
label.typecho-label.settings-title{
	font-size: 30px;
    font-weight: bold;
    border: none;
}
.settings-title:hover {
    text-decoration: underline;
}
.appearanceTitle{
    float: inherit;
    margin-bottom: 0px;
	box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    background-color: #fff;
    margin: 8px 1%;
    padding: 8px 2%;
    width: 94%;
	display: table;
	background-color: #f6f8f8;
}
/*组件大小为94%*/
.length-94{
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    background-color: #fff;
    margin: 8px 1%;
    padding: 8px 2%;
    width: 94%;
	margin-bottom:20px;
}
/*组件大小为60%*/
.length-60{
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    background-color: #fff;
    margin: 8px 1%;
    padding: 8px 2%;
    width: 60%;
}
/*组件大小为44%*/
.length-44{
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    background-color: #fff;
    margin: 8px 1%;
    padding: 8px 2%;
    width: 44%;
    margin-bottom: 30px;
}
/*组件大小为27%*/
.length-27{
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    background-color: #fff;
    margin: 8px 1%;
    padding: 8px 2%;
    width: 27.333%;
    margin-bottom: 40px;
}
/*组件大小为29%*/
.length-29 {
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);
    background-color: #fff;
    margin: 8px 1%;
    padding: 8px 2%;
    width: 29%;
}
/*组件大小为59%*/
.length-59{
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14),0 3px 1px -2px rgba(0,0,0,.2),0 1px 5px 0 rgba(0,0,0,.12);
    background-color: #fff;
    margin: 8px 1%;
    padding: 8px 2%;
    width: 59%;
	margin-bottom: 30px;
}
#typecho-option-item-BGtype-2 {
    margin-bottom: 0px;
}
#typecho-option-item-bgcolor-4 {
    margin-bottom: 20px;
}
#typecho-option-item-BlogJob-10 {
    margin-bottom: 55px;
}
#typecho-option-item-titleintro-8{
    margin-bottom: 50px;
}
</style>
<script>
var $ = mdui.$;
$(function() {
	$('.showSettings').bind('click',function() {
		$('.mdui-panel-item').addClass('mdui-panel-item-open');
	});
	$('.hideSettings').bind('click',function() {
		$('.mdui-panel-item').removeClass('mdui-panel-item-open');
		$('.mdui-panel-item-body').attr('style','');
	});
});

function bk_del(){
	if (confirm('您确定要删除现有备份数据吗？','警告')==true){
		return true;
    }else{
		return false;
	}	
}

</script>
HTML;
    }
}
?>