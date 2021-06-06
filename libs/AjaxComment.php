<?php
class Story_Comment
{
	/**
	 * ajaxComment
	 * 实现Ajax评论的方法(实现feedback中的comment功能)
	 * @param Widget_Archive $archive
	 * @return void
	 */
	public static function ajaxComment($archive)
	{
	    $options = Helper::options();
	    $user = Typecho_Widget::widget('Widget_User');
	    $db = Typecho_Db::get();
	    // Security 验证不通过时会直接跳转，所以需要自己进行判断
	    // 需要开启反垃圾保护，此时将不验证来源
	    if($archive->request->get('_') != Helper::security()->getToken($archive->request->getReferer())){
	        $archive->response->throwJson(array('status'=>0,'msg'=>_t('非法请求')));
	    }
	    /** 评论关闭 */
	    if(!$archive->allow('comment')){
	        $archive->response->throwJson(array('status'=>0,'msg'=>_t('评论已关闭')));
	    }
	    /** 检查ip评论间隔 */
	    if (!$user->pass('editor', true) && $archive->authorId != $user->uid &&
	    $options->commentsPostIntervalEnable){
	        $latestComment = $db->fetchRow($db->select('created')->from('table.comments')
	                    ->where('cid = ?', $archive->cid)
	                    ->where('ip = ?', $archive->request->getIp())
	                    ->order('created', Typecho_Db::SORT_DESC)
	                    ->limit(1));
	
	        if ($latestComment && ($options->gmtTime - $latestComment['created'] > 0 &&
	        $options->gmtTime - $latestComment['created'] < $options->commentsPostInterval)) {
	            $archive->response->throwJson(array('status'=>0,'msg'=>_t('对不起, 您的发言过于频繁, 请稍侯再次发布')));
	        }        
	    }
	    $comment = array(
	        'cid'       =>  $archive->cid,
	        'created'   =>  $options->gmtTime,
	        'agent'     =>  $archive->request->getAgent(),
	        'ip'        =>  $archive->request->getIp(),
	        'ownerId'   =>  $archive->author->uid,
	        'type'      =>  'comment',
	        'status'    =>  !$archive->allow('edit') && $options->commentsRequireModeration ? 'waiting' : 'approved'
	    );
	    /** 判断父节点 */
	    if ($parentId = $archive->request->filter('int')->get('parent')) {
	        if ($options->commentsThreaded && ($parent = $db->fetchRow($db->select('coid', 'cid')->from('table.comments')->where('coid = ?', $parentId))) && $archive->cid == $parent['cid']) {
				$comment['parent'] = $parentId;
	        } else {
	            $archive->response->throwJson(array('status'=>0,'msg'=>_t('父级评论不存在')));
	        }
	    }
	    $feedback = Typecho_Widget::widget('Widget_Feedback');
	    //检验格式
	    $validator = new Typecho_Validate();
	    $validator->addRule('author', 'required', _t('必须填写用户名'));
	    $validator->addRule('author', 'xssCheck', _t('请不要在用户名中使用特殊字符'));
	    $validator->addRule('author', array($feedback, 'requireUserLogin'), _t('您所使用的用户名已经被注册,请登录后再次提交'));
	    $validator->addRule('author', 'maxLength', _t('用户名最多包含200个字符'), 200);
	    if ($options->commentsRequireMail && !$user->hasLogin()) {
	        $validator->addRule('mail', 'required', _t('必须填写电子邮箱地址'));
	    }
	    $validator->addRule('mail', 'email', _t('邮箱地址不合法'));
	    $validator->addRule('mail', 'maxLength', _t('电子邮箱最多包含200个字符'), 200);
	    if ($options->commentsRequireUrl && !$user->hasLogin()) {
	        $validator->addRule('url', 'required', _t('必须填写个人主页'));
	    }
	    $validator->addRule('url', 'url', _t('个人主页地址格式错误'));
	    $validator->addRule('url', 'maxLength', _t('个人主页地址最多包含200个字符'), 200);
	    $validator->addRule('text', 'required', _t('必须填写评论内容'));
	    $comment['text'] = $archive->request->text;
	    /** 对一般匿名访问者,将用户数据保存一个月 */
	    if (!$user->hasLogin()) {
	        /** Anti-XSS */
	        $comment['author'] = $archive->request->filter('trim')->author;
	        $comment['mail'] = $archive->request->filter('trim')->mail;
	        $comment['url'] = $archive->request->filter('trim')->url;
	        /** 修正用户提交的url */
	        if (!empty($comment['url'])) {
	            $urlParams = parse_url($comment['url']);
	            if (!isset($urlParams['scheme'])) {
	                $comment['url'] = 'http://' . $comment['url'];
	            }
	        }
	        $expire = $options->gmtTime + $options->timezone + 30*24*3600;
	        Typecho_Cookie::set('__typecho_remember_author', $comment['author'], $expire);
	        Typecho_Cookie::set('__typecho_remember_mail', $comment['mail'], $expire);
	        Typecho_Cookie::set('__typecho_remember_url', $comment['url'], $expire);
	    } else {
	        $comment['author'] = $user->screenName;
	        $comment['mail'] = $user->mail;
	        $comment['url'] = $user->url;
	        /** 记录登录用户的id */
	        $comment['authorId'] = $user->uid;
	    }
	    /** 评论者之前须有评论通过了审核 */
	    if (!$options->commentsRequireModeration && $options->commentsWhitelist) {
	        if ($feedback->size($feedback->select()->where('author = ? AND mail = ? AND status = ?', $comment['author'], $comment['mail'], 'approved'))) {
	            $comment['status'] = 'approved';
	        } else {
	            $comment['status'] = 'waiting';
	        }
	    }
	    if ($error = $validator->run($comment)) {
	        $archive->response->throwJson(array('status'=>0,'msg'=> implode(';',$error)));
	    }
		//评论过程的插件接口，一般用于过滤垃圾评论的插件
		try {
			$comment = $feedback->pluginHandle()->comment($comment, $feedback->_content);
		} catch (Typecho_Exception $e) {
			Typecho_Cookie::set('__typecho_remember_text', $comment['text']);
			$archive->response->throwJson(array('status'=>0,'msg'=>_t($e->getMessage())));
			throw $e;
		}
	    /** 添加评论 */
		$commentId = $feedback->insert($comment);
		Typecho_Cookie::delete('__typecho_remember_text');
		$db->fetchRow($feedback->select()->where('coid = ?', $commentId)
		->limit(1), array($feedback, 'push'));
		//评论完成后的接口，一般用于评论提醒插件
		$feedback->pluginHandle()->finishComment($feedback);
		// 返回评论数据
		$data = array(
			'cid' => $feedback->cid,
			'coid' => $feedback->coid,
			'parent' => $feedback->parent,
			'mail' => $feedback->mail,
			'url' => $feedback->url,
			'ip' => $feedback->ip,
			'agent' => $feedback->agent,
			'author' => $feedback->author,
			'authorId' => $feedback->authorId,
			'permalink' => $feedback->permalink,
			'created' => $feedback->created,
			'datetime' => $feedback->date->format('Y-m-d H:i:s'),
			'status' => $feedback->status,
		);
	    // 评论内容
	    ob_start();
	    $feedback->content();
	    $data['content'] = ob_get_clean();
		// if($data['permalink']){
		// 	$obj = (object)$comment;//数组转对象
		// 	$data['author'] = Tool::CommentLink($obj);
		// }
		$data['avatar'] = Tool::avatr($data['mail'],null,true);
	    $archive->response->throwJson(array('status'=>1,'comment'=>$data));
	}
}

?>