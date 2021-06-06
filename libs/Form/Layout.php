<?php
/**
 * 重构HTML
 *
 */
class Story_CustomLabel extends Typecho_Widget_Helper_Layout
{
    public function __construct($html)
    {
        $this->html($html);
        $this->start();
        $this->end();
    }

    public function start(){}
    public function end(){}
}

/**
 * 标签闭合
 *
 */
class Story_EndSymbol extends Typecho_Widget_Helper_Layout
{
    public function __construct($num)
    {
        for ($i =0;$i<$num;$i++){
            $this->addItem(new Story_CustomLabel("</div>"));
        }
    }
    public function start(){}
    public function end(){}
}

/**
 * 标题面板
 *
 */
class Story_Title extends Typecho_Widget_Helper_Layout
{
    /**
     * 构造函数,设置标签名称
     *
     * @access public
     * @param string $titleName
     * @param $subtitleName
     * @param bool $isOpen
     * @internal param string $tagName 标签名称
     * @internal param array $attributes 属性列表
     */
    public function __construct($titleName,$subtitleName = null,$isOpen = false)
    {
        $this->addItem(new Story_CustomLabel('<div class="mdui-panel-item">'));

        $this->addItem(new Story_CustomLabel('<div class="mdui-panel-item-header">'.$titleName. '<small class="mdui-panel-item-sub-header">'.$subtitleName.'</small><i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i></div>'));

        $this->addItem(new Story_CustomLabel('<div class="mdui-panel-item-body">'));

    }

    public function start(){}
    public function end(){}
}