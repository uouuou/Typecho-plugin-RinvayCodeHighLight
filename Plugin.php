<?php
/**
 * 一个CodeHighLight，支持所有目前程序格式
 * 
 * @package RinvayCodeHighLight 
 * @author Rinvay
 * @version 1.8.0
 * @link https://www.rinvay.cc
 */
class RinvayCodeHighLight_Plugin implements Typecho_Plugin_Interface {
     /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
        //设置代码风格样式
        $styles = array_map('basename', glob(dirname(__FILE__) . '/rinvay/styles/*.css'));
        $styles = array_combine($styles, $styles);
        $name = new Typecho_Widget_Helper_Form_Element_Select('code_style', $styles, 'coy.css', _t('选择你的代码风格'));
        $form->addInput($name->addRule('enum', _t('必须选择配色样式'), $styles));
        $showLineNumber = new Typecho_Widget_Helper_Form_Element_Checkbox('showln', array('showln' => _t('显示行号')), array('showln'), _t('是否在代码左侧显示行号'));
        $form->addInput($showLineNumber);

        /*$jq_import = new Typecho_Widget_Helper_Form_Element_Radio('jq_import', array(
            0   =>  _t('不引入'),
            1   =>  _t('引入')
        ), 1, _t('是否引入jQuery'), _t('此插件需要jQuery，如已有选择不引入避免引入多余jQuery'));
        $form->addInput($jq_import->addRule('enum', _t('必须选择一个模式'), array(0, 1)));*/

    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render() {
        
    }

    /**
     *为header添加css文件
     *@return void
     */
    public static function header() {
        $style = Helper::options()->plugin('RinvayCodeHighLight')->code_style;
        $cssUrl = Helper::options()->pluginUrl . '/RinvayCodeHighLight/rinvay/styles/' . $style;
        echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />';
        if (Helper::options()->plugin('RinvayCodeHighLight')->showln) {
            echo '<link rel="stylesheet" type="text/css" href="' . Helper::options()->pluginUrl. '/RinvayCodeHighLight/rinvay/highlightjs-line.css" />';
        }
    }

    /**
     *为footer添加js文件
     *@return void
     */
    public static function footer() {
        $jsUrl = Helper::options()->pluginUrl . '/RinvayCodeHighLight/rinvay/prism.js';
        $lineUrl = Helper::options()->pluginUrl . '/RinvayCodeHighLight/rinvay/highlightjs-line-numbers.min.js';
        $showIn = Helper::options()->plugin('RinvayCodeHighLight')->showln;
        echo <<<HTML
            <script type="text/javascript" src="{$jsUrl}"></script>
            <script type="text/javascript">
                hljs.initHighlightingOnLoad();
            </script>
HTML;
        if ($showIn) {
            echo <<<HTML
            <script type="text/javascript" src="{$lineUrl}"></script>
            <script type="text/javascript">
                hljs.initLineNumbersOnLoad();
            </script>
HTML;

        }
    }
}
