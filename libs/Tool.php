<?php
/**
 * tool.php
 * 
 * 工具类或全局类
 * 
 */
$GLOBALS['dir'] = Helper::options()->themeUrl;

class Tool
{
	/**
	 * 输出相对首页路由，本方法会自适应伪静态
	 * 
	 * @return story
	 */
	public static function index($path)
	{
		Helper::options()->index($path);
	}

	/**
	 * 输出相对首页路径，本方法不处理伪静态，用于静态文件
	 * 
	 * @return story
	 */
	public static function indexHome($path)
	{
		Helper::options()->siteUrl($path);
	}

	/**
	 * 输出相对主题目录路径，用于静态文件
	 * 
	 * @return story
	 */
	public static function indexTheme($path)
	{
		Helper::options()->themeUrl($path);
	}
	
	/**
	 * 获取无参数URL，Apache和Nginx均可使用
	 * 
	 * 若使用$_SERVER['PHP_SELF']则会出现index.php与伪静态冲突
	 */
	public static function curPageURL()
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		$pageURL .= "://";
		$this_page = $_SERVER["REQUEST_URI"];
		// 只取 ? 前面的内容
		if (strpos($this_page, "?") !== false) {
			$this_pages = explode("?", $this_page);
			$this_page = reset($this_pages);
		}
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $this_page;
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"] . $this_page;
		}
		return $pageURL;
	}

	/**
	 * 批量引用文件
	 *
	 * @param $path  目录
	 * @param $file  文件格式
	 * @return story
	 */
	public static function requireFile($path, $file)
	{
		$all = glob($path.'*.'.$file);
			foreach ($all as $file) {
				require_once $file;
			}
	}

	/**
	 * 替换默认的preg_replace_callback函数
	 * @param $pattern
	 * @param $callback
	 * @param $subject
	 * @param $limt
	 * @return string
	 */
	public static function handle_preg_replace_callback($pattern, $callback, $subject , int $limt = -1) {
		return self::handleHtml($subject, function ($content) use ($callback, $pattern ,$limt) {
			return preg_replace_callback($pattern, $callback, $content , $limt);
		});
	}

	/**
	 * 替换默认的preg_replace函数
	 * @param $pattern
	 * @param $callback
	 * @param $subject
	 * @param $limt
	 * @return string
	 */
	public static function handle_preg_replace($pattern, $replacement, $subject , int $limt = -1) {
		return self::handleHtml($subject, function ($content) use ($replacement, $pattern ,$limt) {
			return preg_replace($pattern, $replacement, $content , $limt);
		});
	}

	/**
	 * 处理 HTML 文本，确保不会解析代码块中的内容
	 * @param $content
	 * @param callable $callback
	 * @return string
	 */
	public static function handleHtml($content, $callback) {
		$replaceStartIndex = array();
		$replaceEndIndex = array();
		$currentReplaceId = 0;
		$replaceIndex = 0;
		$searchIndex = 0;
		$searchCloseTag = false;
		$contentLength = strlen($content);
		while (true) {
			if ($searchCloseTag) {
				$tagName = substr($content, $searchIndex, 4);
				if ($tagName == "<cod") {
					$searchIndex = strpos($content, '</code>', $searchIndex);
					if (!$searchIndex) {
						break;
					}
					$searchIndex += 7;
				} elseif ($tagName == "<pre") {
					$searchIndex = strpos($content, '</pre>', $searchIndex);
					if (!$searchIndex) {
						break;
					}
					$searchIndex += 6;
				} elseif ($tagName == "<kbd") {
					$searchIndex = strpos($content, '</kbd>', $searchIndex);
					if (!$searchIndex) {
						break;
					}
					$searchIndex += 6;
				} elseif ($tagName == "<scr") {
					$searchIndex = strpos($content, '</script>', $searchIndex);
					if (!$searchIndex) {
						break;
					}
					$searchIndex += 9;
				} elseif ($tagName == "<sty") {
					$searchIndex = strpos($content, '</style>', $searchIndex);
					if (!$searchIndex) {
						break;
					}
					$searchIndex += 8;
				} else {
					break;
				}
				if (!$searchIndex) {
					break;
				}
				$replaceIndex = $searchIndex;
				$searchCloseTag = false;
				continue;
			} else {
				$searchCodeIndex = strpos($content, '<code', $searchIndex);
				$searchPreIndex = strpos($content, '<pre', $searchIndex);
				$searchKbdIndex = strpos($content, '<kbd', $searchIndex);
				$searchScriptIndex = strpos($content, '<script', $searchIndex);
				$searchStyleIndex = strpos($content, '<style', $searchIndex);
				if (!$searchCodeIndex) {
					$searchCodeIndex = $contentLength;
				}
				if (!$searchPreIndex) {
					$searchPreIndex = $contentLength;
				}
				if (!$searchKbdIndex) {
					$searchKbdIndex = $contentLength;
				}
				if (!$searchScriptIndex) {
					$searchScriptIndex = $contentLength;
				}
				if (!$searchStyleIndex) {
					$searchStyleIndex = $contentLength;
				}
				$searchIndex = min($searchCodeIndex, $searchPreIndex, $searchKbdIndex, $searchScriptIndex, $searchStyleIndex);
				$searchCloseTag = true;
			}
			$replaceStartIndex[$currentReplaceId] = $replaceIndex;
			$replaceEndIndex[$currentReplaceId] = $searchIndex;
			$currentReplaceId++;
			$replaceIndex = $searchIndex;
		}
		$output = "";
		$output .= substr($content, 0, $replaceStartIndex[0]);
		for ($i = 0; $i < count($replaceStartIndex); $i++) {
			$part = substr($content, $replaceStartIndex[$i], $replaceEndIndex[$i] - $replaceStartIndex[$i]);
			if (is_array($callback)) {
				$className = $callback[0];
				$method = $callback[1];
				$renderedPart = call_user_func($className.'::'.$method, $part);
			} else {
				$renderedPart = $callback($part);
			}
			$output.= $renderedPart;
			if ($i < count($replaceStartIndex) - 1) {
				$output.= substr($content, $replaceEndIndex[$i], $replaceStartIndex[$i + 1] - $replaceEndIndex[$i]);
			}
		}
		$output .= substr($content, $replaceEndIndex[count($replaceStartIndex) - 1]);
		return $output;
	}

	/**
	 * string数据转换为array或键值对
	 *
	 * @param string $item 设置名
	 * @param bool $mode 转换类型
	 *
	 * @return array|bool
	 */
	public static function convertConfigData($item, $mode)
	{
		$options = Helper::options();
		//根据$item获取对应的设置中的string数据
		$data = $options->$item ? $options->$item : false;
		$content = null;
		if (!$data) {
			//不存在对应的设置名或内容为空
			$content = false;
		} else {
			if ($mode) {
				//转换为数组
				$content = json_decode("[" . $data . "]", true);
			} else {
				//转换为键值对
				$content = json_decode(trim("{" . $data . "}"), true);
			}
		}
		return $content;
	}

	/**
	 * 获取网页内容
	 * 
	 * @return story
	 */
	public static function curl_url($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	/**
	 * 13位时间戳  暂未使用
	 * 
	 * @return story
	 */
	public static function Millisecond($time)
	{
		list($msec ,$sec) =  explode ( " " ,  microtime ());
		$millisecond = intval($msec*1000) + $sec*1000;
		return $millisecond;
	}

	/**
	 * 中国天干地支年
	 * 
	 * @return story
	 */
	public static function china_year($numYear)
	{
		$china_era = array('0'=>'庚','1'=>'辛','2'=>'壬','3'=>'癸','4'=>'甲','5'=>'乙','6'=>'丙','7'=>'丁','8'=>'戊','9'=>'己');
		$china_branch = array('0'=>'申','1'=>'酉','2'=>'戌','3'=>'亥','4'=>'子','5'=>'丑','6'=>'寅','7'=>'卯','8'=>'辰','9'=>'巳','10'=>'午','11'=>'未');
		$lastNum = mb_strcut($numYear, -1);
		$remainder = $numYear%12;
		$chinaYear = $china_era[$lastNum].$china_branch[$remainder].'年';
		return $chinaYear;
	}

	/**
	 * 夜间模式判断
	 * 
	 * @return story
	 */
	public static function night()
	{
		
		$night_bol = Typecho_Widget::widget('Widget_Options')->night_js;
		if ($night_bol == 0) {
			echo '<script src="'.$GLOBALS['dir'].'/assets/js/autonight.js" type="text/javascript"></script>';
		}
		if(empty($night_bol == 2||$night_bol == 3)) {
			echo '<script src="'.$GLOBALS['dir'].'/assets/js/nightstyle.js" type="text/javascript"></script>';
		}
	}

	/**
	 * 输出qq或者自定义源gravatar的头像
	 * 
	 * @return story
	 */
	public static function avatr($mail, int $size = 100, bool $out = false)
	{
		$Op_avatr = Typecho_Widget::widget('Widget_Options')->gravatars;
		$Op_tools  = Typecho_Widget::widget('Widget_Options')->qq;
		$Str = str_replace('@qq.com','',$mail);
		$avatr = '';
		if(stristr($mail,'@qq.com')&&is_numeric($Str)&&strlen($Str)<11&&strlen($Str)>4&&$Op_tools == 0){
			$url = 'https://s.p.qq.com/pub/get_face?img_type=3&uin='.$Str;
			$api = get_headers($url,true)['Location'];
			$json_api = json_encode($api);
			$ex_api = explode("&k=",$json_api)[1];
			$k_value = explode("&s=",$ex_api)[0];
			$avatr = 'https://q.qlogo.cn/g?b=qq&k='.$k_value.'&s='.$size;
		}else{
			$mail = md5($mail);
			$avatr = 'https://'.$Op_avatr.'/'.$mail.'?s='.$size;
		}
		if ($out === true) {
			return $avatr;
		}else{
			echo $avatr;
		}
	}

	/**
	 * 输出随机缩略图
	 * 
	 * @return story
	 */
	public static function showThumbnail($widget, bool $rd = false , bool $out = false)
	{
		$random = $widget->widget('Widget_Options')->themeUrl.'/assets/img/random/'.rand(1,10).'.webp';
		$pattern = '/\<img.*?class\=\"(.*?postimg.*?)".*?src\=\"(.*?)\"[^>]*>/i';
		$patternMD = '/\!\[.*?\]\((http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i';
		$patternMDfoot = '/\[.*?\]:\s*(http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i';
		$preg_content = preg_match_all($pattern, $widget->content, $thumbUrl);
		$img = $random;
		//获取图片后缀
		//$str_pat = preg_grep('/(.+)?\.(\w+)(\?.+)?/i', $thumbUrl);
		//强制开启随机
		if ($rd === true) {
			$img = $random;
		}
		//自定义字段
		elseif ($widget->fields->thumb) {
			$img = $widget->fields->thumb;
		}
		//文章中内容
		elseif ($preg_content && 
			count($thumbUrl) > 0 && //标签存在
			count($thumbUrl[2]) > 0 //标签src存在，[1]是class属性、[2]是src链接
			//strpos($thumbUrl[1][0],'icon'.$str_pat[1]) == false//排除icon.*图片
			) {
			$img = $thumbUrl[2][0];
		}
		//markdown格式的图片
		elseif (preg_match_all($patternMD, $widget->content, $thumbUrl)) {
			$img = $thumbUrl[2][0];
		}
		//脚注式markdown格式的图片
		elseif (preg_match_all($patternMDfoot, $widget->content, $thumbUrl)) {
			$img = $thumbUrl[2][0];
		}
		//输出
		if ($out === true) {
			return $img;
		}else{
			echo $img;
		}
	}

	/**
	 * 判断文章内是否有图片
	 * 
	 * @return story
	 */
	static function ifPost_img($widget)
	{
		$pattern = '/\<img.*?class\=\"(.*?postimg.*?)".*?src\=\"(.*?)\"[^>]*>/i'; 
		$preg = preg_match_all($pattern, $widget->content);
		if ($preg) {
			echo '<div class="mdui-card-menu"><button class="mdui-btn mdui-btn-icon mdui-text-color-black-icon"><!-- 偷偷说一下，文章内有图片 --><i class="mdui-icon material-icons">image</i></button></div>';
		}
	}

	/**
	 * 个性描述
	 * 
	 * @return story
	 */
	static function My_dep()
	{
		$Te_Widget = Typecho_Widget::widget('Widget_Options');
		$content = $Te_Widget->indexBox;
		$Op_dep = explode("$",$content)[2];
		//一言
		if (preg_match("/\[yiyan\]/",$Op_dep)) {
			$url = 'https://v1.hitokoto.cn/?c=a&c=b&c=c&c=d&c=e&c=f&c=g&c=h&c=k';
			$decode = self::curl_url($url);
			$api = json_decode($decode,true)['hitokoto'];
			$echotext = $api;
		}
		//诗词
		elseif (preg_match("/\[shici\]/",$Op_dep)) {
			$url = 'https://v1.hitokoto.cn/?c=i';
			$decode = self::curl_url($url);
			$api = json_decode($decode,true)['hitokoto'];
			$echotext = $api;
		}
		// 自定义
		elseif (!empty($Op_dep)) {
			$echotext = $Op_dep;
		}
		//站点说明
		else {
			$echotext = $Te_Widget->description;
		}
		echo $echotext;
	}

	/**
	 * 根据配置的JSON数据输出侧边栏菜单列表
	 * 
	 * @return story
	 */
	public static function sidebarNav()
	{
		$data = self::convertConfigData('customUrl', true);
		if (!$data) {
			return;
		}
		// 基础设置
		$text = null;
		$href = null;
		$icon = null;
		$target = null;
		$sub = null;
		// 转换输出
		if ($data) {
			$html = '';
			foreach ($data as $v) {
				$text = array_key_exists('text', $v) ? $v['text'] : "";
				$href = array_key_exists('href', $v) ? $v['href'] : "";
				$icon = array_key_exists('icon', $v) ? $v['icon'] : "";
				$target = array_key_exists('target', $v) ? $v['target'] : "";
				// 有子菜单
				if (array_key_exists('sub', $v)) {
					$html .= '
				<div class="mdui-collapse" mdui-collapse="{accordion: true}">
					<div class="mdui-collapse-item">
						<div class="mdui-collapse-item-header mdui-list-item">
							<i class="mdui-list-item-icon mdui-icon material-icons">'.$icon.'</i>
							<div class="mdui-list-item-content">'.$text.'</div>
							<i class="mdui-collapse-item-arrow mdui-list-item-icon mdui-icon material-icons ">keyboard_arrow_down</i>
						</div>
						<div class="mdui-collapse-item-body">
							<ul class="mdui-list mdui-list-dense">';
					foreach ($v['sub'] as $_k => $_v) {
						$text = array_key_exists('text', $_v) ? $_v['text'] : "";
						$href = array_key_exists('href', $_v) ? $_v['href'] : "";
						$target = array_key_exists('target', $_v) ? $_v['target'] : "";
						$html .= '
								<li class="mdui-list-item">
									<a href="'.$href.'" target="'.$target.'" class="mdui-list-item-content mdui-text-color-theme-secondary">'.$text.'</a>
								</li>';
						}
					$html .= "
							</ul>
						</div>
					</div>
				</div>";
				// 无子菜单
				}else{
					$html .= '<a href="'.$href.'" target="'.$target.'" class="mdui-list-item">
					<i class="mdui-list-item-icon mdui-icon material-icons">'.$icon.'</i>
					<div class="mdui-list-item-content">'.$text.'</div>
					</a>';
				}
			}
			echo $html;
		}
		//转换失败
		echo false;
	}

	/**
	 * 侧边栏分类图标设置
	 * 
	 * @return story
	 */
	static function Categorys_icon()
	{
		$Te_Widget = Typecho_Widget::Widget('Widget_Metas_Category_List');
		$Options_icon = Typecho_Widget::widget('Widget_Options')->Categorys_icon;
		$default_icon = 'view_week';
		if (!empty($Options_icon)) {
			$key_ex = $Te_Widget->mid;
			$json_icon = json_decode($Options_icon,false);
			foreach ($json_icon as $key=>$value) {
				if($key_ex === $key) {
					$default_icon = $value;
				}
			}
		}
		echo $default_icon;
	}

	/**
	 * 侧边栏页面图标设置
	 * 
	 * @return story
	 */
	static function Pages_icon()
	{
		$Te_Widget = Typecho_Widget::Widget('Widget_Contents_Page_List');
		$Options_icon = Typecho_Widget::widget('Widget_Options')->Pages_icon;
		$default_icon = 'description';
		if (!empty($Options_icon)) {
			$key_ex = $Te_Widget->slug;
			$json_icon = json_decode($Options_icon,true);
			foreach ($json_icon as $key=>$value) {
				if($key_ex === $key) {
					$default_icon = $value;
				}
			}
		}
		echo $default_icon;
	}

	/**
	 * 根据用户 UID 获取昵称
	 *
	 * @return $story
	 */
	public static function getUserScreenName(int $userID)
	{
		$db = Typecho_Db::get();
		$name = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', $userID));
		return $name['screenName'];
	}

	/**
	 * 根据用户 UID 获取邮箱
	 *
	 * @return $story
	 */
	static function getUserMail(int $userID)
	{
		$db = Typecho_Db::get();
		$mail = $db->fetchRow($db->select('mail')->from('table.users')->where('uid = ?', 1));
		return $mail['mail'];
	}

	/**
	 * 增加浏览量
	 * 
	 * @return story
	 */
	public static function get_post_view($archive)
	{
		$cid  = $archive->cid;
		$db = Typecho_Db::get();
		$prefix = $db->getPrefix();
		if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
			$db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
			echo 0;
			return;
		}
		$row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
		if ($archive->is('single')) {
			$views = Typecho_Cookie::get('extend_contents_views');
			if(empty($views)){
				$views = array();
			}else{
				$views = explode(',', $views);
			}
			if(!in_array($cid,$views)){
				$db->query($db->update('table.contents')->rows(array('views' => (int) $row['views'] + 1))->where('cid = ?', $cid));
				array_push($views, $cid);
				$views = implode(',', $views);
				Typecho_Cookie::set('extend_contents_views', $views,';SameSite=None;Secure'); //记录查看cookie
			}
		}
		echo $row['views'];
	}

	/**
	 * archive页面判断
	 * 
	 * @return story
	 */
	public static function  archive_dep($ac)
	{
		if($ac->is('category')){
			echo'分类';
		}elseif($ac->is('search')){
			echo'搜索';
		}elseif($ac->is('tag')){
			echo'标签';
		}elseif($ac->is('author')){
			echo self::My_dep();
		}
	}
	
	/**
	 * 评论者个人主页链接
	 * 
	 * @access public
	 * @param string $autoLink 原生参数，控制输出链接
	 * @param string $noFollow 原生参数，控制输出链接额外属性
	 * @return story
	 */
	public function CommentLink($obj, $autoLink = NULL, $noFollow = NULL)
	{
		$options = Helper::options();
		$autoLink = $autoLink ? $autoLink : $options->commentsShowUrl;
		$noFollow = $noFollow ? $noFollow : $options->commentsUrlNofollow;
	    if ($obj->url && $autoLink) {
			$link = '<a href="'.$obj->url.'"'.($noFollow ? ' rel="external nofollow"' : NULL).(strstr($obj->url, $options->index) == $obj->url ? NULL : ' target="_blank"').'>'.$obj->author.'</a>';
		}else{
			$link = $obj->author;
		}
		return $link;
	}
	
	/**
	 * 子评论@父级评论人
	 * 
	 * @access public
	 * @param string $coid 父级coid
	 * @return story
	 */
	public function childrenReply($coid)
	{
	    $db = Typecho_Db::get();
	    $prow = $db->fetchRow($db->select('parent')->from('table.comments')->where('coid = ?', $coid));
	    $parent = $prow['parent'];
		$href = '';
	    if ($parent != "0") {
			$arow = $db->fetchRow($db->select('author')->from('table.comments')->where('coid = ? AND status = ?', $parent, 'approved'));
			if($arow['author']) {
				$author = $arow['author'];
				$href = '<a href="#comment-' . $parent . '">@' . $author . '</a>';
			}
		}
		echo $href;
	}

}
?>
