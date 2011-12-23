<?php
/**
* @package     jelix
* @subpackage  core_response
* @author      Thomas Pellissier Tanon
* @copyright   2011 Thomas Pellissier Tanon
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
*
*/
require_once(JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');

/**
* HTML5 response
* @package  jelix
* @subpackage core_response
* @since
*/
class jResponseHtml5 extends jResponseHtml {
    /**
     * use a script to fix problem with new html5 elements and old ie, html link to the script.
     */
    protected $_fixeOldIE = '//html5shiv.googlecode.com/svn/trunk/html5.js';

    /**
     * property rofile of the head element.
     */
    protected $_headProfile = '';

    function __construct (){
        parent::__construct();
        global $gJConfig;
        $lang = split('_', $gJConfig->locale);
        $this->_lang = $lang[0];
    }

    /**
     * add a profile to the head element.
     * @param string $profile the profile
     */
    public function addHeadProfile ($profile){
        $this->_headProfile .= htmlspecialchars($profile) . ' ';
    }

    /**
     * generate the doctype. You can override it if you want to have your own doctype, like XHTML+MATHML.
     */
    protected function outputDoctype (){
    	
        if($this->_isXhtml && $this->xhtmlContentType && strstr($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')){
            echo '<?xml version="1.0" encoding="'.$this->_charset.'"?>', "\n";
            echo '<!DOCTYPE html>', "\n";
            echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="',$this->_lang,'" lang="',$this->_lang,'">', "\n";
        }else{
            echo '<!DOCTYPE html>', "\n";
            echo '<html lang="',$this->_lang,'">', "\n";
        }
    }

    /**
     * generate the content of the <head> content
     */
    protected function outputHtmlHeader (){
        global $gJConfig;

        if($this->_headProfile != '') {
            echo '<head profile="',$this->_headProfile,'">',"\n";
        } else {
            echo '<head>',"\n";
        }
        if($this->_isXhtml && $this->xhtmlContentType && strstr($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')){      
            echo '<meta content="application/xhtml+xml;charset=',$this->_charset,'" http-equiv="content-type"',$this->_endTag;
        } else {
            echo '<meta content="text/html;charset=',$this->_charset,'" http-equiv="content-type"',$this->_endTag;
        }
        echo '<title>',htmlspecialchars($this->title),"</title>\n";

        if(!empty($this->_MetaDescription)){
            // meta description
            $description = implode(' ',$this->_MetaDescription);
            echo '<meta name="description" content="',htmlspecialchars($description).'" ',$this->_endTag;
        }

        if(!empty($this->_MetaKeywords)){
            // meta description
            $keywords = implode(',',$this->_MetaKeywords);
            echo '<meta name="keywords" content="'.htmlspecialchars($keywords).'" '.$this->_endTag;
        }
        if (!empty($this->_MetaGenerator)) {
            echo '<meta name="generator" content="'.htmlspecialchars($this->_MetaGenerator).'" '.$this->_endTag;
        }
        if (!empty($this->_MetaAuthor)) {
            echo '<meta name="author" content="'.htmlspecialchars($this->_MetaAuthor).'" '.$this->_endTag;
        }

        // css link
        foreach ($this->_CSSLink as $src=>$params){
            $this->outputCssLinkTag($src, $params);
        }

        foreach ($this->_CSSIELink as $src=>$params){
            // special params for conditions on IE versions
            if (!isset($params['_ieCondition']))
              $params['_ieCondition'] = 'IE' ;
            echo '<!--[if '.$params['_ieCondition'].' ]>';
            $this->outputCssLinkTag($src, $params);
            echo '<![endif]-->';
        }

        if($this->favicon != ''){
            $fav = htmlspecialchars($this->favicon);
            echo '<link rel="icon" type="image/x-icon" href="',$fav,'" ',$this->_endTag;
            echo '<link rel="shortcut icon" type="image/x-icon" href="',$fav,'" ',$this->_endTag;
        }
        
        // others links
        foreach($this->_Link as $href=>$params){
            $more = array();
            if( !empty($params[1]))
                $more[] = 'type="'.$params[1].'"';
            if (!empty($params[2]))
                $more[] = 'title = "'.htmlspecialchars($params[2]).'"';
            echo '<link rel="',$params[0],'" href="',htmlspecialchars($href),'" ',implode($more, ' '),$this->_endTag;
        }

        // js code
        if($this->_fixeOldIE) {
            global $gJConfig;
            echo '<!--[if lte IE 8]><script src="', $this->_fixeOldIE, '"></script><![endif]-->';
		}

        if(count($this->_JSCodeBefore)){
            echo '<script type="text/javascript">
// <![CDATA[
 ',implode ("\n", $this->_JSCodeBefore),'
// ]]>
</script>';
        }

        // js link
        foreach ($this->_JSLink as $src=>$params){
            $this->outputJsScriptTag($src, $params);
        }

        foreach ($this->_JSIELink as $src=>$params){
            if (!isset($params['_ieCondition']))
                $params['_ieCondition'] = 'IE' ;
            echo '<!--[if ',$params['_ieCondition'],' ]>';
            $this->outputJsScriptTag($src, $params);
            echo '<![endif]-->';
        }

        // styles
        if(count($this->_Styles)){
            echo "<style type=\"text/css\">\n";
            foreach ($this->_Styles as $selector=>$value){
                if (strlen ($value)){
                    //il y a une paire clef valeur.
                    echo $selector.' {'.$value."}\n";
                }else{
                    //il n'y a pas de valeur, c'est peut être simplement une commande.
                    //par exemple @import qqchose, ...
                    echo $selector, "\n";
                }
            }
            echo "\n </style>\n";
        }
        // js code
        if(count($this->_JSCode)){
            echo '<script type="text/javascript">
// <![CDATA[
 ',implode ("\n", $this->_JSCode),'
// ]]>
</script>';
        }
        echo implode ("\n", $this->_headBottom), '</head>';
    }
}
