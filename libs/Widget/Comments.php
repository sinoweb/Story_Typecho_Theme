<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 评论归档
 *
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 * @version $Id$
 */

/**
 * 评论归档组件
 *
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
class Story_Comments_Archive extends Widget_Abstract_Comments
{
	 /**
	 * 当前页
	 *
	 * @access private
	 * @var integer
	 */
	private $_currentPage;

	/**
	 * 所有文章个数
	 *
	 * @access private
	 * @var integer
	 */
	private $_total = false;

	/**
	 * 子父级评论关系
	 *
	 * @access private
	 * @var array
	 */
	private $_threadedComments = array();

	/**
	 * 多级评论回调函数
	 * 
	 * @access private
	 * @var mixed
	 */
	private $_customThreadedCommentsCallback = false;

	/**
	 * _singleCommentOptions  
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_singleCommentOptions = NULL;


	private $_commentAuthors = array();

	/**
	 * 安全组件
	 */
	private $_security = NULL;

	/**
	 * 构造函数,初始化组件
	 *
	 * @access public
	 * @param mixed $request request对象
	 * @param mixed $response response对象
	 * @param mixed $params 参数列表
	 * @return story
	 */
	public function __construct($request, $response, $params = NULL)
	{
		parent::__construct($request, $response, $params);
		$this->parameter->setDefault('parentId=0&commentPage=0&commentsNum=0&allowComment=1');
		
		Typecho_Widget::widget('Widget_Security')->to($this->_security);

		/** 初始化回调函数 */
		if (function_exists('threadedComments')) {
			$this->_customThreadedCommentsCallback = true;
		}
	}

	/**
	 * 评论回调函数
	 * 
	 * @access private
	 * @return story
	 */
	private function threadedCommentsCallback()
	{
		$singleComment = $this->_singleCommentOptions;
		if (function_exists('threadedComments')) {
			return threadedComments($this, $singleComment);
		}
		
		$commentClass = '';
		if ($this->authorId) {
			if ($this->authorId == $this->ownerId) {
				$master = '<span class="avatar-icon"></span>';
				$mcss = 'class="master"';
				$commentClass .= ' comment-by-author';
			} else {
				$commentClass .= ' comment-by-user';
			}
		}
?>
<article id="<?php $this->theId(); ?>" class="comment-body<?php
if ($this->levels > 0) {
echo ' comment-child';
$this->levelsAlt(' comment-level-odd', ' comment-level-even');
} else {
echo ' comment-parent';
}
$this->alt(' comment-odd', ' comment-even');
echo $commentClass;
?>">
<div class="mdui-card-header mdui-p-y-1 mdui-p-x-0">
<div <?php echo $mcss; ?> style="position: relative;float: left;">
<img class="mdui-card-header-avatar" src="<?php Tool::avatr($this->mail,$singleComment->avatarSize); ?>">
<?php echo $master; ?>
</div>
<div class="mdui-card-header-title">
<?php echo Tool::CommentLink($this); ?>
<?php if ('waiting' == $this->status) { ?>
<?php $singleComment->commentStatus(); ?>
<?php } ?>
</div>
<div class="mdui-card-header-subtitle"><time itemprop="commentTime"  datetime="<?php $this->date('c'); ?>"><?php $singleComment->beforeDate();echo date('Y-m-d H:i',$this->created);$singleComment->afterDate(); ?></time>
</div>
<div class="mdui-card-menu">
<?php $this->reply($singleComment->replyWord); ?>
</div>
</div>
<div class="mdui-m-b-1 mdui-typo">
<?php Tool::childrenReply($this->coid);$this->content(); ?>
</div>
<!-- <div class="mdui-divider-inset mdui-m-y-0"></div> -->
</article>
<?php if ($this->children) { ?>
<div class="comment-children">
<?php $this->threadedComments(); ?>
</div>
<?php } ?>
<?php
	}

	/**
	 * 获取当前评论链接
	 *
	 * @access protected
	 * @return string
	 */
	protected function ___permalink()
	{

		if ($this->options->commentsPageBreak) {			
			$pageRow = array('permalink' => $this->parentContent['pathinfo'], 'commentPage' => $this->_currentPage);
			return Typecho_Router::url('comment_page',
						$pageRow, $this->options->index) . '#' . $this->theId;
		}
		
		return $this->parentContent['permalink'] . '#' . $this->theId;
	}

	/**
	 * 子评论
	 *
	 * @access protected
	 * @return array
	 */
	protected function ___children()
	{
		return $this->options->commentsThreaded && !$this->isTopLevel && isset($this->_threadedComments[$this->coid]) 
			? $this->_threadedComments[$this->coid] : array();
	}

	/**
	 * 是否到达顶层
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function ___isTopLevel()
	{
		return $this->levels > 0;
	}

	/**
	 * 重载内容获取
	 *
	 * @access protected
	 * @return story
	 */
	protected function ___parentContent()
	{
		return $this->parameter->parentContent;
	}

	/**
	 * 输出文章评论数
	 *
	 * @access public
	 * @param string $string 评论数格式化数据
	 * @return story
	 */
	public function num()
	{
		$args = func_get_args();
		if (!$args) {
			$args[] = '%d';
		}

		$num = intval($this->_total);

		echo sprintf(isset($args[$num]) ? $args[$num] : array_pop($args), $num);
	}

	/**
	 * 执行函数
	 *
	 * @access public
	 * @return story
	 */
	public function execute()
	{
		if (!$this->parameter->parentId) {
			return;
		}

		$commentsAuthor = Typecho_Cookie::get('__typecho_remember_author');
		$commentsMail = Typecho_Cookie::get('__typecho_remember_mail');

		// 对已登录用户显示待审核评论，方便前台管理
		if ($this->user->hasLogin()) {
			$select = $this->select()->where('table.comments.cid = ?', $this->parameter->parentId)
				->where('table.comments.status = ? OR table.comments.status = ?', 'approved', 'waiting');
		} else {
			$select = $this->select()->where('table.comments.cid = ?', $this->parameter->parentId)
				->where('table.comments.status = ? OR (table.comments.author = ? AND table.comments.mail = ? AND table.comments.status = ?)', 'approved', $commentsAuthor, $commentsMail, 'waiting');
		}

		$threadedSelect = NULL;
		
		if ($this->options->commentsShowCommentOnly) {
			$select->where('table.comments.type = ?', 'comment');
		}
		
		$select->order('table.comments.coid', 'ASC');
		$this->db->fetchAll($select, array($this, 'push'));
		
		/** 需要输出的评论列表 */
		$outputComments = array();
		
		/** 如果开启评论回复 */
		if ($this->options->commentsThreaded) {
		
			foreach ($this->stack as $coid => &$comment) {
				
				/** 取出父节点 */
				$parent = $comment['parent'];
			
				/** 如果存在父节点 */
				if (0 != $parent && isset($this->stack[$parent])) {
				
					/** 如果当前节点深度大于最大深度, 则将其挂接在父节点上 */
					if ($comment['levels'] >= 2) {
						$comment['levels'] = $this->stack[$parent]['levels'];
						$parent = $this->stack[$parent]['parent'];	 // 上上层节点
						$comment['parent'] = $parent;
					}
				
					/** 计算子节点顺序 */
					$comment['order'] = isset($this->_threadedComments[$parent]) 
						? count($this->_threadedComments[$parent]) + 1 : 1;
				
					/** 如果是子节点 */
					$this->_threadedComments[$parent][$coid] = $comment;
				} else {
					$outputComments[$coid] = $comment;
				}
				
			}
		
			$this->stack = $outputComments;
		}
		
		/** 评论排序 */
		if ('DESC' == $this->options->commentsOrder) {
			$this->stack = array_reverse($this->stack, true);
		}
		
		/** 评论总数 */
		$this->_total = count($this->stack);
		
		/** 对评论进行分页 */
		if ($this->options->commentsPageBreak) {
			if ('last' == $this->options->commentsPageDisplay && !$this->parameter->commentPage) {
				$this->_currentPage = ceil($this->_total / $this->options->commentsPageSize);
			} else {
				$this->_currentPage = $this->parameter->commentPage ? $this->parameter->commentPage : 1;
			}
			
			/** 截取评论 */
			$this->stack = array_slice($this->stack,
				($this->_currentPage - 1) * $this->options->commentsPageSize, $this->options->commentsPageSize);
			
			/** 评论置位 */
			$this->row = current($this->stack);
			$this->length = count($this->stack);
		}
		
		reset($this->stack);
	}

	/**
	 * 将每行的值压入堆栈
	 *
	 * @access public
	 * @param array $value 每行的值
	 * @return array
	 */
	public function push(array $value)
	{
		$value = $this->filter($value);
		
		/** 计算深度 */
		if (0 != $value['parent'] && isset($this->stack[$value['parent']]['levels'])) {
			$value['levels'] = $this->stack[$value['parent']]['levels'] + 1;
		} else {
			$value['levels'] = 0;
		}

		$value['realParent'] = $value['parent'];

		/** 重载push函数,使用coid作为数组键值,便于索引 */
		$this->stack[$value['coid']] = $value;
		$this->_commentAuthors[$value['coid']] = $value['author'];
		$this->length ++;
		
		return $value;
	}

	/**
	 * 输出分页
	 *
	 * @access public
	 * @param string $prev 上一页文字
	 * @param string $next 下一页文字
	 * @param int $splitPage 分割范围
	 * @param string $splitWord 分割字符
	 * @param string $template 展现配置信息
	 * @return story
	 */
	public function pageNav($prev = '&laquo;', $next = '&raquo;', $splitPage = 3, $splitWord = '...', $template = '')
	{
		if ($this->options->commentsPageBreak && $this->_total > $this->options->commentsPageSize) {
			$default = array(
				'wrapTag'	   =>  'ol',
				'wrapClass'	 =>  'page-navigator'
			);

			if (is_string($template)) {
				parse_str($template, $config);
			} else {
				$config = $template;
			}

			$template = array_merge($default, $config);

			$pageRow = $this->parameter->parentContent;
			$pageRow['permalink'] = $pageRow['pathinfo'];

			$query = Typecho_Router::url('comment_page', $pageRow, $this->options->index);

			/** 使用盒状分页 */
			$nav = new Typecho_Widget_Helper_PageNavigator_Box($this->_total,$this->_currentPage, $this->options->commentsPageSize, $query);
			$nav->setPageHolder('commentPage');
			$nav->setAnchor('comments');
			
			echo '<' . $template['wrapTag'] . (empty($template['wrapClass']) 
					? '' : ' class="' . $template['wrapClass'] . '"') . '>';
			$nav->render($prev, $next, $splitPage, $splitWord, $template);
			echo '</' . $template['wrapTag'] . '>';
		}
	}

	/**
	 * 递归输出评论
	 *
	 * @access protected
	 * @return story
	 */
	public function threadedComments()
	{
		$children = $this->children;
		if ($children) {
			//缓存变量便于还原
			$tmp = $this->row;
			$this->sequence ++;

			//在子评论之前输出
			echo $this->_singleCommentOptions->before;

			foreach ($children as $child) {
				$this->row = $child;
				$this->threadedCommentsCallback();
				$this->row = $tmp;
			}

			//在子评论之后输出
			echo $this->_singleCommentOptions->after;

			$this->sequence --;
		}
	}
	
	/**
	 * 列出评论
	 * 
	 * @access private
	 * @param mixed $singleComment 单个评论自定义选项
	 * @return story
	 */
	public function listComments($singleComment = NULL)
	{
		//初始化一些变量
		$this->_singleCommentOptions = Typecho_Config::factory($singleComment);
		$this->_singleCommentOptions->setDefault(array(
			'before'		=>  '<ol class="comment-list">',
			'after'		 =>  '</ol>',
			'beforeAuthor'  =>  '',
			'afterAuthor'   =>  '',
			'beforeDate'	=>  '',
			'afterDate'	 =>  '',
			'dateFormat'	=>  $this->options->commentDateFormat,
			'replyWord'	 =>  '<i class="mdui-icon material-icons">reply_all</i>',
			'commentStatus' =>  '<em>评论正等待审核!</em>',
			'avatarSize'	=>  100,
			'defaultAvatar' =>  NULL
		));
		$this->pluginHandle()->trigger($plugged)->listComments($this->_singleCommentOptions, $this);

		if (!$plugged) {
			if ($this->have()) { 
				echo $this->_singleCommentOptions->before;
			
				while ($this->next()) {
					$this->threadedCommentsCallback();
				}
			
				echo $this->_singleCommentOptions->after;
			}
		}
	}
	
	/**
	 * 重载alt函数,以适应多级评论
	 * 
	 * @access public
	 * @return story
	 */
	public function alt()
	{
		$args = func_get_args();
		$num = func_num_args();
		
		$sequence = $this->levels <= 0 ? $this->sequence : $this->order;
		
		$split = $sequence % $num;
		echo $args[(0 == $split ? $num : $split) -1];
	}

	/**
	 * 根据深度余数输出
	 *
	 * @access public
	 * @param string $param 需要输出的值
	 * @return story
	 */
	public function levelsAlt()
	{
		$args = func_get_args();
		$num = func_num_args();
		$split = $this->levels % $num;
		echo $args[(0 == $split ? $num : $split) -1];
	}
	
	/**
	 * 评论回复链接
	 * 
	 * @access public
	 * @param string $word 回复链接文字
	 * @return story
	 */
	public function reply($word = '')
	{
		if ($this->options->commentsThreaded && $this->parameter->allowComment) {
			$word = empty($word) ? '<i class="mdui-icon material-icons">reply_all</i>' : $word;
			$this->pluginHandle()->trigger($plugged)->reply($word, $this);
			
			if (!$plugged) {
				echo '<a id="comment-reply-link" class="mdui-btn mdui-text-color-theme-accent mdui-m-l-0" href="' . substr($this->permalink, 0, - strlen($this->theId) - 1) . '?replyTo=' . $this->coid .
					'#' . $this->parameter->respondId . '" rel="nofollow" onclick="return TypechoComment.reply(\'' .
					$this->theId . '\', ' . $this->coid . ');">' . $word . '</a>';
			}
		}
	}
	
	/**
	 * 取消评论回复链接
	 * 
	 * @access public
	 * @param string $word 取消回复链接文字
	 * @return story
	 */
	public function cancelReply($word = '')
	{
		if ($this->options->commentsThreaded) {
			$word = empty($word) ? '<i class="mdui-icon material-icons">close</i>' : $word;
			$this->pluginHandle()->trigger($plugged)->cancelReply($word, $this);
			if (!$plugged) {
				$replyId = $this->request->filter('int')->replyTo;
				echo '<a id="cancel-comment-reply-link" class="mdui-btn mdui-btn-dense mdui-text-color-theme-accent" href="' . $this->parameter->parentContent['permalink'] . '#' . $this->parameter->respondId .
				'" rel="nofollow"' . ($replyId ? '' : ' style="display:none"') . ' onclick="return TypechoComment.cancelReply();">' . $word . '</a>';
			}
		}
	}
	
}