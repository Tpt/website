<?php
/**
* @package     jelix
* @subpackage  core_response
* @author      Thomas Pellissier Tanon
* @copyright   2011 Thomas Pellissier Tanon
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');

/**
* Opensearch description response generator. Use the extensions referrer and suggestions.
* @link http://www.opensearch.org/Specifications/OpenSearch/1.1
* @package  jelix
* @subpackage core_response
*/
class jResponseOpenSearchDescription extends jResponse {
	protected $_type = 'opensearchdescription';

    /**
    * charset used in the file
    * @var string
    */
    public $charset;

    /**
    * Language used in the file
    * @var string
    */
    public $lang;

    /**
    * informations of the file
    * @var jOpenSearchDescriptionInfo
    */
    public $infos = null;

    /**
    * array of urls
    */
    public $itemList = array();

    /**
    * Template engine used for output
    * @var jtpl
    */
    private $_template = null;

    /**
     * template name
     * @var string
     */
    private $_mainTpl = 'wsexport~open_search';

    /**
    * Array containing the XSL stylesheets links
    */
    private $_xsl = array();

    /**
     * Class constructor
     */
    function __construct (){
        global $gJConfig;

        $this->_template = new jTpl();
        $this->charset = $gJConfig->charset;
		$this->infos = new jOpenSearchDescriptionInfo();
        list($lang,$country) = explode('_', $gJConfig->locale);
        $this->lang = $lang;

        parent::__construct ();
    }

    /**
     * Generate the content and send it.
     * Errors are managed
     * @return boolean true if generation is ok, else false
     */
    final public function output (){
        $this->_headSent = false;

        $this->_httpHeaders['Content-Type'] = 'application/opensearchdescription+xml;charset=' . $this->charset;

        $this->sendHttpHeaders();

        echo '<?xml version="1.0" encoding="'. $this->charset .'"?>', "\n";
        $this->_outputXmlHeader();

        $this->_headSent = true;

        $this->_template->assign('opensearch', $this->infos);
        $this->_template->assign('urls', $this->itemList);
		$this->_template->assign('lang', $this->lang);

        $this->_template->display($this->_mainTpl);

        echo '</OpenSearchDescription>';
        return true;
    }

    final public function outputErrors() {
        if (!$this->_headSent) {
             if (!$this->_httpHeadersSent) {
                header("HTTP/1.0 500 Internal Server Error");
                header('Content-Type: text/xml;charset='.$this->charset);
             }
             echo '<?xml version="1.0" encoding="'. $this->charset .'"?>';
        }

        echo '<errors xmlns="http://jelix.org/ns/xmlerror/1.0">';
        if ($this->hasErrors()) {
            echo $this->getFormatedErrorMsg();
        } else {
            echo '<error>Unknown Error</error>';
        }
        echo '</errors>';
    }

    /**
     * Format error messages
     * @return string formated errors
     */
    protected function getFormatedErrorMsg(){
        $errors = '';
        foreach ($GLOBALS['gJCoord']->errorMessages  as $e) {
           $errors .=  '<error xmlns="http://jelix.org/ns/xmlerror/1.0" type="'. $e[0] .'" code="'. $e[1] .'" file="'. $e[3] .'" line="'. $e[4] .'">';
           $errors .= htmlspecialchars($e[2], ENT_NOQUOTES, $this->charset);
           if ($e[5])
              $errors .= "\n".htmlspecialchars($e[5], ENT_NOQUOTES, $this->charset);
           $errors .= '</error>'. "\n";
        }
        return $errors;
    }

    /**
     * create a new url
     * @param string $type     the MIME type of the resource
     * @param string $template the url of the resource
     * @return jOpenSearchDescriptionUrl
     */
    public function createItem($type, $template, $rel = 'results') {
        $item = new jOpenSearchDescriptionUrl();
        $item->type = $type;
        $item->template = $template;
		$item->rel = $rel;
        return $item;
    }

    /**
     * add an url in the file
     * @param jOpenSearchDescriptionUrl $item
     */
    public function addItem($item) {
        $this->itemList[] = $item;
    }


    public function addOptionals($content) {
        if (is_array($content)) {
            $this->_optionals = $content;
        }
    }

    public function addXSLStyleSheet($src, $params=array()) {
        if (!isset($this->_xsl[$src])){
            $this->_xsl[$src] = $params;
        }
    }

    protected function _outputXmlHeader() {
        // XSL stylesheet
        foreach ($this->_xsl as $src => $params) {
            //the extra params we may found in there.
            $more = '';
            foreach ($params as $param_name => $param_value) {
                $more .= $param_name.'="'. htmlspecialchars($param_value).'" ';
            }
            echo ' <?xml-stylesheet type="text/xsl" href="', $src,'" ', $more,' ?>';
        }
    }

    protected function _outputOptionals() {
        if (is_array($this->_optionals)) {
            foreach ($this->_optionals as $name => $value) {
                echo '<'. $name .'>'. $value .'</'. $name .'>', "\n";
            }
        }
    }
}


/**
 * meta data of the search
 * @package    jelix
 * @subpackage core_response
 * @since 
 */
class jOpenSearchDescriptionInfo {
    /**
     * name of the search engine (only text, no html and only 16 character or less)
	 * @var string
     */
    public $shortName;
    /**
     * long name of the search engine (only text, no html and only 48 character or less)
	 * @var string
     */
    public $longName;
    /**
     * description of the search engine (only text, no html and only 1024 character or less)
	 * @var string
     */
    public $description;
    /**
     * the email adress of the maintainer
     * @var string
     */
    public $contact;
    /**
     * list of keywords witch identify and categorize the search content. They are single words delimited by the space character (only text, no html and only 256 character or less)
	 * @var array
     */
    public $tags;
    /**
     * the MIMEtype of the image of the engine ('image/png' or 'image/jpeg'). The best dimensions are 64x64.
     * @var string
     */
    public $imageType;
    /**
     * web site url corresponding to the image
     * @var string
     */
    public $imageLink;
    /**
     * width of the image
     * @var string
     */
    public $imageWidth = '64';
    /**
     * height of the image
     * @var string
     */
    public $imageHeight = '64';
    /**
     * the MIMEtype of the other icon of the engine ('image/x-icon' or 'image/vnd.microsoft.icon'). The best dimensions are 16x16.
     * @var string
     */
    public $iconType;
    /**
     * web site url corresponding to the icon
     * @var string
     */
    public $iconLink;
    /**
     * width of the icon
     * @var string
     */
    public $iconWidth = '16';
    /**
     * height of the icon
     * @var string
     */
    public $iconHeight = '16';
    /**
     * An exemple of search like "cat"
     * @var string
     */
    public $exemple;
    /**
     * The name of the creator of the file (only text, no html and only 64 character or less)
     * @var string
     */
    public $developer;
    /**
     * The coryright of the data searched (only text, no html and only 256 character or less)
     * @var string
     */
    public $attribution;
    /**
     * The degree of protection of the search engine : open, limited, private or closed (case insensitive)
     * @var string
     */
    public $syndicationRight = 'open';
    /**
     * Boolean giving if the serch engine contain adult content.
     * @var string
     */
    public $adultContent = 'false';
    /**
     * All the languages that the search engine supports or '*' for all languages
     * @var array
     */
    public $languages = array('*');
    /**
     * All the encoding that can be used for the search request
     * @var array
     */
    public $inputEncodings = array('UTF-8');
    /**
     * All the encoding that can be used for the search response
     * @var array
     */
    public $outputEncodings = array('UTF-8');
}

/**
 * a search response
 * @package    jelix
 * @subpackage core_response
 * @since 
 */
class jOpenSearchDescriptionUrl {
    /**
     * the MIME type of the resource
     * @var string
     */
    public $type;
    /**
     * the url of the resource with the tag. You can use the tags of the extensions referrer and suggestions with the namespaces referrer and suggestions
     * @var string
     */
    public $template;
    /**
     * the role of the resource results, suggestions, self or collection (case insensitive)
     * @var string
     */
    public $rel = 'results';
    /**
     * the index number of the first result
     * @var string
     */
    public $indexOffset = '1';
    /**
     * the page number of the first set of results
     * @var string
     */
    public $pageOffset = '1';
}
