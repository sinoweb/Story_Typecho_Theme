<?php
/**
 * Contents.php
 * 
 * 解析器等内容处理相关
 * 
 * 
 */
class Contents {
	/**
	 * 根据 cid 返回文章对象
	 * 
	 * @return Widget_Abstract_Contents
	 */
	public static function getPost($cid)
	{
		$db = Typecho_Db::get();
		$post = new Widget_Abstract_Contents(Typecho_Request::getInstance(), Typecho_Widget_Helper_Empty::getInstance());
		$db->fetchRow($post->select()
			->where("cid = ?", $cid)
			->limit(1),
			array($post, 'push'));
		return $post;
	}
	
	/**
	 * 根据 cid 返回评论对象
	 * 
	 * @return Widget_Abstract_Comments
	 */
	public static function getComment($coid)
	{
		$db = Typecho_Db::get();
		$comment = new Widget_Abstract_Comments(Typecho_Request::getInstance(), Typecho_Widget_Helper_Empty::getInstance());
		$db->fetchRow($comment->select()
			->where("coid = ?", $coid)
			->limit(1),
			array($comment, 'push'));
		return $comment;
	}
	
	/**
	 * 根据 mid 返回 meta 对象
	 * 
	 * @return Widget_Abstract_Metas
	 */
	public static function getMeta($mid)
	{
		$db = Typecho_Db::get();
		$meta = new Widget_Abstract_Metas(Typecho_Request::getInstance(), Typecho_Widget_Helper_Empty::getInstance());
		$db->fetchRow($meta->select()
			->where("mid = ?", $mid)
			->limit(1),
			array($meta, 'push'));
		return $meta;
	}
	
	/**
	 * 输出完备的标题
	 * 
	 * @return story
	 */
	public static function title(Widget_Archive $archive)
	{
		$archive->archiveTitle(array(
			'category'  =>  '分类 %s 下的文章',
			'search'	=>  '包含关键字 %s 的文章',
			'tag'	   =>  '标签 %s 下的文章',
			'author'	=>  '%s 发布的文章'
		), '', ' - ');
		Helper::options()->title();
	}

	/**
	 * 输出文章摘要
	 * @param $content
	 * @param $limit 字数限制
	 * @param string $emptyText
	 * @return string
	 */
	public static function excerpt($content, $limit, $emptyText = "暂时无可提供的摘要")
	{
		if ($limit == 0) {
			return "";
	    } else {
	        $content = self::returnExceptCodeContent($content);
	        if (trim($content) == "") {
				return $emptyText;
			} else {
				return Typecho_Common::subStr(strip_tags($content), 0, $limit, "...");
			}
		}
	}
	
	/**
	 * 文章摘要排除短代码
	 */
	public static function returnExceptCodeContent($content)
	{
		// 其它文章
		if (strpos($content, '[cid') !== false) {
			$pattern = '/\[cid="(.+?)"]/';
			$content = Tool::handle_preg_replace($pattern, '',$content);
		}
		// 拼音注解写法
		if (strpos($content, '{{') !== false) {
			$pattern = '/\{\{(.*?):(.*?)\}\}/s';
			$content = Tool::handle_preg_replace($pattern, '$1',$content);
		}
		// Markdown图片
		if (strpos($content, '![') !== false) {
			$pattern = '/!\[(.*?)]\[(.*?)]/i';
			$content = Tool::handle_preg_replace($pattern, '',$content);
		}
		
		return $content;
	}

	/**
	 * 内容解析点钩子
	 */
	static public function contentEx($data, $widget, $last)
	{
		$text = empty($last)?$data:$last;
		if ($widget instanceof Widget_Archive) {
			$text = self::parseHeader($text);
			$text = self::parseTable($text);
			$text = self::parseTableTd($text);
			$text = self::parseAlink($text);
			$text = self::parseImg($text);
			$text = self::parseRuby($text);
			$text = self::parseCidcontent($text);
		}
		return $text;
	}

	/**
	 * 摘要解析点钩子
	 */
	static public function excerptEx($data, $widget, $last)
	{
		$text = empty($last)?$data:$last;
		if ($widget instanceof Widget_Archive) {
			$text = self::excerpt($text, 200);
		}
		return $text;
	}
	
	/**
	 * 解析文章内 h2 ~ h5 元素
	 * 
	 * @return string
	 */
	static public function parseHeader($content)
	{
		$reg ='/\<h([2-6])(.*?)\>(.*?)\<\/h.*?\>/s';
		$new = Tool::handle_preg_replace_callback($reg, array('Contents', 'parseHeaderCallback'), $content);
		return $new;
	}
	
	/**
	 * 为内容中的 h2-h6 元素编号
	 */
	static private $CurrentTocID = 0;
	static public function parseHeaderCallback($matchs)
	{
		// 增加单独标记，否则冲突
		$id = 'toc_'.(self::$CurrentTocID++);
		return '<h'.$matchs[1].$matchs[2].' id="'.$id.'">'.$matchs[3].'</h'.$matchs[1].'>';
	}
	
	/**
	 * 解析表格
	 * 
	 * @return string
	 */
	static public function parseTable($content)
	{
		$reg = '/<table>(.*?)<\/table>/';
		$rp = '<div class="mdui-table-fluid mdui-m-y-1"><table class="mdui-table mdui-table-hoverable">$1</table></div>';
		$new = Tool::handle_preg_replace($reg,$rp,$content);
		return $new;
	}
	
	/**
	 * 解析表格内td列表
	 * 
	 * @return string
	 */
	static public function parseTableTd($content)
	{
		$reg = '/<td align="(.*?)">(.*?)<\/td>/';
		$rp = '<td class="mdui-text-$1">$2</td>';
		$new = Tool::handle_preg_replace($reg,$rp,$content);
		return $new;
	}
	
	/**
	 * 解析a标签
	 * 
	 * @return string
	 */
	static public function parseAlink($content)
	{
		$reg = '/<a\b([^>]+?)\bhref="((?!'.addcslashes(Helper::options()->index, '/._-+=#?&').'|\#).*?)"([^>]*?)>/i';
		$rp = '<a\1href="\2"\3 target="_blank">';
		$new = Tool::handle_preg_replace($reg,$rp,$content);
		return $new;
	}
	
	/**
	 * 解析img标签
	 * 
	 * @return string
	 */
	static public function parseImg($content)
	{
		$reg = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
		$rp = '<img class="mdui-img-fluid mdui-m-y-1 postimg" src="$1">';//postimg为缩略图
		$new = Tool::handle_preg_replace($reg,$rp,$content);
		return $new;
	}
	
	/**
	 * 解析拼音注解写法
	 * 
	 * @return string
	 */
	static public function parseRuby($string)
	{
	    $reg = '/\{\{(.*?):(.*?)\}\}/s';
	    $rp = '<ruby>${1}<rp>(</rp><rt>${2}</rt><rp>)</rp></ruby>';
	    $new = Tool::handle_preg_replace($reg,$rp,$string);
	    return $new;
	}
	
	/**
	 * 解析文章中引用另一篇文章短代码
	 * 
	 * @return array
	 */
	public static function parseCidcontent($text)
	{
		$reg = '/\[cid="(.+?)"]/';
		if (preg_match_all($reg, $text, $matches)) {
			foreach ($matches[1] as $match) {
				$db = Typecho_Db::get();
				$articleArr = $db->fetchAll($db->select()->from('table.contents')
					->where('status = ?','publish')
					->where('type = ? AND password IS NULL', 'post')
					->where('cid = ?',$match));
				if (count($articleArr) == 0) {
					$text =  Tool::handle_preg_replace($reg, '文章不存在，或被密码保护！', $text, 1);
	            }else{
					$val = Typecho_Widget::widget('Widget_Abstract_Contents')->push($articleArr[0]);
					$pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
					if (preg_match_all($pattern, Typecho_Widget::widget('Widget_Abstract_Contents')->content, $thumbUrl) && count($thumbUrl) > 0 && count($thumbUrl[1]) > 0 ) {
						$img = $thumbUrl[1][0];
					}else{
						$img = Tool::showThumbnail(self::getPost($val['cid']),false,true);
					}
					$rp = '<div class="mdui-card mdui-shadow-1 mdui-m-y-1">
					<div class="mdui-row">
					<div class="mdui-col-xs-6 mdui-col-sm-5 mdui-col-md-4">
					<div class="mdui-card-media">
					<img class="Cid_concent" src="'.$img.'" style="height: 200px;">
					</div>
					</div>
					<div class="mdui-col-xs-6 mdui-col-sm-7 mdui-col-md-8 mdui-p-l-0" style="height: 200px;">
					<div class="mdui-card-primary mdui-p-y-1 mdui-p-r-1 mdui-p-l-0">
					<a href="'.$val['permalink'].'" class="mdui-typo-subheading mdui-img-fluid mdui-text-truncate">'.$val['title'].'</a>
					<div class="mdui-card-header-subtitle mdui-m-a-0">
					'.Tool::getUserScreenName($val['authorId']).' -
					<time datetime="'.date('Y-m-d H:i:s', $val['created']).'" itemprop="datePublished">'.date('Y-m-d', $val['created']).'</time>
					</div>
					</div>
					<div class="mdui-card-content mdui-p-a-0 mdui-m-r-1 h-3x" style="-webkit-line-clamp: 5;">
					'.self::excerpt(Markdown::convert($val['text']), 200).'
					<!-- 调用Typecho Markdown函数转换为纯文本-->
					</div>
					</div>
					</div>
					</div>';
					//Typecho_Common::subStr(str_replace("\n", '', trim(strip_tags(self::getPost($val['cid'])->excerpt))), 0, 200, '...')<!-- 调用Typecho自带函数，并且根据cid返回文章对象，获取文章的纯文本摘要 -->
					$text = Tool::handle_preg_replace($reg, $rp, $text, 1);
				}
			}
		}
		return $text;
	}
	
	/**
	 * 文章上一篇
	 */
	public static function thePrev($archive)
	{
		$db = Typecho_Db::get();
		$content = $db->fetchRow($db->select()->from('table.contents')->where('table.contents.created < ?', $archive->created)
			->where('table.contents.status = ?', 'publish')
			->where('table.contents.type = ?', $archive->type)
			->where('table.contents.password IS NULL')
			->order('table.contents.created', Typecho_Db::SORT_DESC)
			->limit(1));
		if ($content) {
			return self::getPost($content['cid']);	
		} else {
			return null;
		}
	}
	
	/**
	 * 文章下一篇
	 */
	public static function theNext($archive)
	{
		$db = Typecho_Db::get();
		$content = $db->fetchRow($db->select()->from('table.contents')->where('table.contents.created > ? AND table.contents.created < ?',
			$archive->created, Helper::options()->gmtTime)
			->where('table.contents.status = ?', 'publish')
			->where('table.contents.type = ?', $archive->type)
			->where('table.contents.password IS NULL')
			->order('table.contents.created', Typecho_Db::SORT_ASC)
			->limit(1));
		if ($content) {
			return self::getPost($content['cid']);	
		} else {
			return null;
		}
	}
	
	/**
	 * 文章标签
	 * 
	 * @return array
	 */
	public static function getTags($cid)
	{
		$db = Typecho_Db::get();
		$rows = $db->fetchAll($db->select('mid')
			->from('table.relationships')
			->where("cid = ?", $cid));
		$metas = array();
		foreach ($rows as $row) {
			$meta = self::getMeta($row['mid']);
			if ($meta->type == 'tag') {
				$meta = array(
					'name' => $meta->name,
					'permalink' => $meta->permalink);
				$metas[] = $meta;
			}
		}
		return $metas;
	}
}
?>