<?php
/**
* @package   wsexport
* @subpackage wsexport
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/

/**
 * base class for all controller in the module
* @package   wsexport
* @subpackage wsexport
 */
class myController extends jController {
        protected $format = '';

        public function __construct($request) {
                parent::__construct($request);
                $this->format = strtolower($this->param('format'));
                if($this->format == '') {
                        $this->format = 'html';
                }
        }

        protected function _error($id) {
                if($id = 404) {
                        $rep = $this->getResponse('redirect');
		        $rep->action = 'jelix~error:notfound';
        		return $rep;
                }
        }

        protected function _getLang() {
                global $gJConfig;
                $lang = explode('_', $gJConfig->locale);
                return $lang[0];
        }

        protected function _getTitle() {
                return str_replace(' ', '_', urldecode($this->param('title')));
        }

        protected function _getHtmlResponse() {
                $rep = $this->getResponse('html');
                $rep->lang = $this->_getLang();
                return $rep;
        }

        protected function _getFullLang($lang) {
                return $lang . '_' . strtoupper($lang);
        }
}
