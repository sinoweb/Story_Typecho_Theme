<?php
/**
 * 主题后台配置
 * @param $form
 */
function themeConfig($form) {
	?>
	<link rel="stylesheet" type="text/css" href="<?php Tool::indexTheme('/assets/mdui/css/mdui.css'); ?>">
	<script src="<?php Tool::indexTheme('/assets/mdui/js/mdui.js'); ?>"></script>
	<?php
	Backup::echoBackup();
	echo SettingHeader::StyleOutput();
	echo SettingHeader::SettingsWelcome();
	
	$form->addItem(new Story_CustomLabel('<div class="mdui-panel" mdui-panel="">'));
	$form->addItem(new Story_Title('外观设置','主题主色调、主题强调色、卡片半透明设置、网站图标、背景图片、登录背景图片'));
	
	$primaryColor = new Story_Select('primaryColor',[
	'indigo' => 'Indigo',
	'red' => 'Red',
	'pink' => 'Pink',
	'purple' => 'Purple',
	'deep-purple' => 'Deep Purple',
	'blue' => 'Blue',
	'light-blue' => 'Light Blue',
	'cyan' => 'Cyan',
	'teal' => 'Teal',
	'green' => 'Green',
	'light-green' => 'Light Green',
	'lime' => 'Lime',
	'yellow' => 'Yellow',
	'amber' => 'Amber',
	'orange' => 'Orange',
	'deep-orange' => 'Deep Orange',
	'brown' => 'Brown',
	'grey' => 'Grey',
	'blue-grey' => 'Blue Grey',
	'' => 'White'],
	'indigo','主题主色调','选择主题主色调，应用在卡片背景色等<br>具体颜色可查看<a class="mdui-text-color-theme-accent" target="_blank" href="https://www.mdui.org/docs/color">MDUI官网</a>');
	$form->addInput($primaryColor->multiMode());

	$accentColor = new Story_Select('accentColor',[
	'pink' => 'Pink',
	'red' => 'Red',
	'purple' => 'Purple',
	'deep-purple' => 'Deep Purple',
	'indigo' => 'Indigo',
	'blue' => 'Blue',
	'light-blue' => 'Light Blue',
	'cyan' => 'Cyan',
	'teal' => 'Teal',
	'green' => 'Green',
	'light-green' => 'Light Green',
	'lime' => 'Lime',
	'yellow' => 'Yellow',
	'amber' => 'Amber',
	'orange' => 'Orange',
	'deep-orange' => 'Deep Orange'],
	'pink','主题强调色','选择主题强调色，应用在按钮标签颜色等<br>具体颜色可查看<a class="mdui-text-color-theme-accent" target="_blank" href="https://www.mdui.org/docs/color">MDUI官网</a>');
	$form->addInput($accentColor->multiMode());
	
	$rgba = new Story_Checkbox('rgba',[
	'IndexRgba' => '首页及Archive页',
	'PostRgba' => '文章页',
	'PageRgba' => '页面页'],
	array(),'卡片半透明设置');
	$form->addInput($rgba->multiMode());
	
	$favicon = new Story_Text('favicon', NULL, NULL, 
	'网站图标','在这里填入一个图片 URL 地址，留空默认为<code>/favicon.ico</code>文件。');
	$form->addInput($favicon);
	
	$bg = new Story_Text('bg', NULL, NULL, 
	'背景图片','在这里填入一个图片 URL 地址，用于网站的背景图。');
	$form->addInput($bg);
	
	$loginbg = new Story_Text('loginbg', NULL, NULL,
	'登录背景图片','在这里填入一个图片 URL 地址，用于登录的背景图，留空输出随机图。');
	$form->addInput($loginbg);
	
	$form->addItem(new Story_EndSymbol(2));
	$form->addItem(new Story_Title('基础功能','首页资料盒、gravatar头像源、顶栏固定方式、翻页按钮、夜间模式、qq头像解析'));
	
	$indexBox = new Story_Text('indexBox', NULL, NULL,
	'首页资料盒图片/个人描述','请根据以下格式填写：<br>
	https://abc.com/left.jpg$https://abc.com/right.jpg$个人描述<br>
	“个人描述”，可以填写你想写的文字。若填写 [yiyan] 则随机输出一言，[shici] 则随机输出诗词，api来自<a class="mdui-text-color-theme-accent" target="_blank" href="https://hitokoto.cn/">一言</a>，留空则输出网站的描述。');
	$form->addInput($indexBox);
	
	$gravatars = new Story_Select('gravatars',[
	'www.gravatar.com/avatar' => 'gravatar的www源',
	'cn.gravatar.com/avatar' => 'gravatar的cn源',
	'secure.gravatar.com/avatar' => 'gravatar的secure源',
	'sdn.geekzu.org/avatar' => '极客族源',
	'gravatar.proxy.ustclug.org/avatar' => '中科大源[不建议]',
	'cdn.v2ex.com/gravatar' => 'v2ex源',
	'dn-qiniu-avatar.qbox.me/avatar' => '七牛源[不建议]',
	'gravatar.helingqi.com/wavatar' => '禾令奇源[建议]',
	'gravatar.loli.net/avatar' => 'loli.net源'],
	'gravatar.helingqi.com/wavatar','<span class="mdui-text-color-green">gravatar头像源</span>','替换Gravatar头像的默认地址。<br>替换后可提升加载速度，默认使用<b>禾令奇[建议]源</b>。');
	$form->addInput($gravatars->multiMode());

	$topbar = new Story_Select('topbar',[
	'0' => '跟随浮动',
	'1' => '始终固定',
	'2' => '不固定'],
	'0','顶栏固定方式设置',"顶栏固定方式设置说明：<br>跟随浮动：下拉隐藏，上拉显示；<br>始终固定：一直固定在顶部。");
	$form->addInput($topbar);
	
	$pagenav = new Story_Select('pagenav',[
	'0' => '数字型',
	'1' => '按钮型'],
	'0','翻页按钮',"翻页样式说明：<br>按钮型是只有<code>上一页</code>和<code>下一页</code>两个按钮；<br>数字型则是默认样式。");
	$form->addInput($pagenav);

	$night_js = new Story_Select('night_js',[
	'0' => '自动',
	'1' => '手动',
	'2' => '常驻',
	'3' => '关闭'],
	"0",'夜间模式',"夜间模式说明：<br>自动是晚上10点到早上6点开启；<br>手动则需要点击开启；<br>常驻则是永远为夜间模式。");
	$form->addInput($night_js);
	
	$qq = new Story_Radio('qq',[
	'0' => '全站解析',
	'1' => '不解析'],
	"0",'qq头像解析',"是否根据qq邮箱解析qq头像。");
	$form->addInput($qq);
	
	$form->addItem(new Typecho_Widget_Helper_Layout("/div"));
	$form->addItem(new Typecho_Widget_Helper_Layout("/div"));
	$form->addItem(new Story_Title('侧边栏设置','分类图标、页面图标、自定义侧边栏菜单列表'));
	
	$Categorys_icon = new Story_Textarea('Categorys_icon', NULL, NULL,
	'分类图标',' 设置侧边栏分类的图标，使用方法：<br>
	<span class="mdui-text-color-theme-accent">{"分类页面的mid（不得填错！以及不支持子分类！）"</span>:<span class="mdui-text-color-theme-accent">"图标"}</span><br>
	注意：请填写<span class="mdui-text-color-theme-accent"> Json语法，也不得少填</span>，结尾不要有‘<span class="mdui-text-color-theme-accent"> , </span>’逗号，<span class="mdui-text-color-theme-accent">填写错误则侧边栏会报错！</span>如下例：<br>
	<code class="mdui-text-color-theme-accent">{"6":"widgets", "12":"chat"}</code><br>
	注意icon请填写<span class="mdui-text-color-theme-accent"> icon 的名称或代码</span>，只支持Material icon图标，<a href="https://www.mdui.org/docs/material_icon" target="_blank" class="mdui-text-color-theme-accent">点击这里查看图标大全!</a>');
	$form->addInput($Categorys_icon);
	
	$Pages_icon = new Story_Textarea('Pages_icon', NULL, NULL,
	'页面图标','设置侧边栏页面的图标，使用方法：<br>
	<span class="mdui-text-color-theme-accent">{"页面的slug（不得填错！）"</span>:<span class="mdui-text-color-theme-accent">"图标"}</span><br>
	注意：请填写<span class="mdui-text-color-theme-accent"> Json语法，也不得少填</span>，结尾不要有‘<span class="mdui-text-color-theme-accent"> , </span>’逗号，<span class="mdui-text-color-theme-accent">填写错误则侧边栏会报错！</span>如下例：<br>
	<code class="mdui-text-color-theme-accent">{"links":"links", "archive":"archive"}</code><br>
	注意icon请填写<span class="mdui-text-color-theme-accent"> icon 的名称或代码</span>，只支持Material icon图标，<a href="https://www.mdui.org/docs/material_icon" target="_blank" class="mdui-text-color-theme-accent">点击这里查看图标大全!</a>');
	$form->addInput($Pages_icon);
	
	$customUrl = new Story_Textarea('customUrl',NULL,NULL,
	'自定义侧边栏菜单列表','填入自定义链接显示在侧边栏，使用方法：<br>
	text：<span class="mdui-text-color-theme-accent">标题</span><br>
	href：<span class="mdui-text-color-theme-accent">地址</span><br>
	icon：<span class="mdui-text-color-theme-accent">图标（非必须）</span><br>
	target：<span class="mdui-text-color-theme-accent">打开方式（非必须）</span><br>
	注意：每个父级菜单都可以设置子菜单，目前仅支持最多二级菜单，但二级菜单不支持图标。请填写<span class="mdui-text-color-theme-accent"> Json语法，也不得少填</span>，结尾不要有‘<span class="mdui-text-color-theme-accent"> , </span>’逗号，<span class="mdui-text-color-theme-accent">填写错误则侧边栏会报错！</span>如下例：<br>
	<div class="mdui-m-y-1 mdui-panel mdui-panel-gapless" mdui-panel>
	<div class="mdui-panel-item">
	<div class="mdui-panel-item-header">
	<div class="mdui-panel-item-title">使用方法</div>
	<i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
	</div>
	<div class="mdui-panel-item-body">
	<div class="mdui-typo">
	<pre><code>
{
	"text": "首页",
	"href": "/",
	"icon": "home",
	"target": "_blank"
}, {
	"text": "父菜单",
	"href": "/",
	"icon": "archives",
	"sub": [{
"text": "子菜单",
"href": "/"
		},
		{
"text": "二级菜单",
"href": "/"
		}
	]
}
	</code></pre>
	</div>
	</div>
	</div>
	</div>
	注意：图标请填写<span class="mdui-text-color-theme-accent"> icon 的名称或代码</span>，只支持Material icon图标，<a href="https://www.mdui.org/docs/material_icon" target="_blank" class="mdui-text-color-theme-accent">点击这里查看图标大全!</a>');
	$form->addInput($customUrl);
	
	$form->addItem(new Typecho_Widget_Helper_Layout("/div"));
	$form->addItem(new Typecho_Widget_Helper_Layout("/div"));
	$form->addItem(new Story_Title('自定义添加','head标签添加自定义代码、body标签添加自定义代码、页脚自定义'));
	
	$add_head = new Story_Textarea('add_head', NULL, NULL,
	'head标签添加自定义代码','在head标签内添加自定义代码');
	$form->addInput($add_head);
	
	$add_body = new Story_Textarea('add_body', NULL, NULL,
	'body标签添加自定义代码','在body标签尾部添加自定义代码');
	$form->addInput($add_body);
	
	$footerText = new Story_Textarea('footerText', NULL, NULL,
	'页脚自定义','在这里填入文本或者标签，在页脚显示');
	$form->addInput($footerText);
	
	$form->addItem(new Typecho_Widget_Helper_Layout("/div"));
	$form->addItem(new Typecho_Widget_Helper_Layout("/div"));
	
	//mdui-panel 结束符
	$form->addItem(new Typecho_Widget_Helper_Layout("/div"));
	
	//保存按钮
	$submit = new Typecho_Widget_Helper_Form_Element_Submit(NULL, NULL, _t('保存设置'));
	$submit->input->setAttribute('class', 'mdui-btn mdui-color-theme-accent mdui-ripple submit_only');
	$form->addItem($submit);
}
/*
function themeFields($layout) {
	$logoUrl = new Story_Text('logoUrl', NULL, NULL, _t('站点LOGO地址'), _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO'));
	$layout->addItem($logoUrl);
}
*/
?>