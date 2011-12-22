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
* Opensearch Suggestions response generator
* @link http://www.opensearch.org/Specifications/OpenSearch/Extensions/Suggestions/1.1
* @package  jelix
* @subpackage core_response
*/
final class jResponseOpenSearchSuggestions extends jResponse {
    protected $_type = 'opensearchsuggestions';

    /**
     * the searched term
     * @var string
     */
    public $query;

    /**
    * array of suggestions
    */
    public $itemList = array();


    public function output() {
        global $gJCoord;
        $this->_httpHeaders['Content-Type'] = 'application/x-suggestions+json';
        $content = array($this->query, array(), array(), array());
        foreach($this->itemList as $item) {
            $content[1][] = $item->term;
            $content[2][] = $item->description;
            $content[3][] = $item->url;
        }
        $content = json_encode($content);
        $this->_httpHeaders['Content-length'] = strlen($content);
        $this->sendHttpHeaders();
        echo $content;
        return true;
    }

    public function outputErrors(){
        global $gJCoord;
        $message = array();
        $message['errorMessage'] = $gJCoord->getGenericErrorMessage();
        $e = $gJCoord->getErrorMessage();
        if($e){
            $message['errorCode'] = $e->getCode();
        }else{
            $message['errorCode'] = -1;
        }
        $this->clearHttpHeaders();
        $this->_httpStatusCode ='500';
        $this->_httpStatusMsg ='Internal Server Error';
        $this->_httpHeaders['Content-Type'] = 'application/x-suggestions+json';
        $content = json_encode($message);
        $this->_httpHeaders['Content-length'] = strlen($content);
        $this->sendHttpHeaders();
        echo $content;
    }

    /**
     * add a suggestion
     * @param string $term        the suggestion term
     * @param string $description the description of the suggestion
     * @param string $url         the url of the search
     * @return jOpenSearchSuggestionsItem
     */
    public function addItem($term, $description, $url) {
        $item = new jOpenSearchSuggestionsItem();
        $item->term = $term;
        $item->description = $description;
        $item->url = $url;
        $this->itemList[] = $item;
    }
}

/**
 * a search suggestion
 * @package    jelix
 * @subpackage core_response
 * @since 
 */
class jOpenSearchSuggestionsItem {
    /**
     * the suggestion term
     * @var string
     */
    public $term;
    /**
     * the description of the suggestion
     * @var string
     */
    public $description;
    /**
     * the url of the search
     * @var string
     */
    public $url;
}
