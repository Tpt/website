<?php
/**
* @package     jelix
* @subpackage  responsehtml_plugin
* @author      Thomas Pellissier Tanon
* @copyright   2011 Thomas Pellissier Tanon
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
 * plugin for jResponseHTML, it displays dublin core metadata in the header
 * @see http://dublincore.org/documents/dcq-html/
 */
class dublincoreHTMLResponsePlugin implements jIHTMLResponsePlugin {

    protected $response = null;
    protected $meta = array();
    protected $link = array();

    public function __construct(jResponse $c) {
        $this->response = $c;
    }

    /**
     * called just before the jResponseBasicHtml::doAfterActions() call
     */
    public function afterAction() {

    }

    /**
     * called just before the final output. This is the opportunity
     * to make changes before the head and body output. At this step
     * the main content (if any) is already generated.
     */
    public function beforeOutput() {
        if(empty($this->meta) && empty($this->link))
            return;

        if(method_exists($this->response, 'addHeadProfile'))
            $this->response->addHeadProfile('http://dublincore.org/documents/dcq-html/');
        $this->response->addHeadContent('<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />');
        $this->response->addHeadContent('<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" />');

        foreach($this->meta as $meta) {
            $content = '<meta name="' . $meta->name . '" content="' . $meta->content . '" ';
            if($meta->scheme != '') {
                $content .= 'scheme="' . $meta->scheme . '" ';
            }
            if($meta->lang != '') {
                $content .= 'lang="' . $meta->lang . '" ';
                if($rep->xhtmlContentType) {
                    $content .= 'xml:lang="' . $meta->lang . '" ';
                }
            }
            $this->response->addHeadContent($content . '/>');
        }

        foreach($this->link as $link) {
            $content = '<link rel="' . $link->rel . '" href="' . $link->href . '" ';
            if($link->lang != '') {
                $content .= 'hreflang="' . $link->lang . '" ';
            }
            $this->response->addHeadContent($content . '/>');
        }
    }

    /**
     * called when the content is generated, and potentially sent, except
     * the body end tag and the html end tags. This method can output
     * directly some contents.
     */
    public function atBottom() {
    }

    /**
     * called just before the output of an error page
     */
    public function beforeOutputError() {
    }

    /**
     * add a Dublin Core meta
     * @param string $name the name of the property
     * @param string $content the content of the property
     * @param string $scheme the scheme the property respect
     * @param string $lang the language of the content
     */
    public function addMeta($name, $content, $scheme = '', $lang = '') {
        $meta = new dublincoreMeta();
        $meta->name = $name;
        $meta->content = $content;
        $meta->scheme = $scheme;
        $meta->lang = $lang;
        $this->meta[] = $meta;
    }

    /**
     * add a Dublin Core link
     * @param string $rel the name of the property
     * @param string $href the link
     * @param string $lang the language of the content linked
     */
    public function addLink($rel, $href, $lang = '') {
        $link = new dublincoreLink();
        $link->rel = $rel;
        $link->href = $href;
        $link->lang = $lang;
        $this->link[] = $link;
    }
}

/**
 * a dublincore meta
 * @package    jelix
 * @subpackage  responsehtml_plugin
 */
class dublincoreMeta {
    public $name;
    public $content;
    public $scheme;
    public $lang;
}

/**
 * a dublincore link
 * @package    jelix
 * @subpackage  responsehtml_plugin
 */
class dublincoreLink {
    public $rel;
    public $href;
    public $lang;
}
