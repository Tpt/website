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
        protected $lang = '';
        protected $format = '';

        public function __construct($request) {
                parent::__construct($request);
                $lang = explode('_', $GLOBALS['gJConfig']->locale);
                $this->lang = $lang[0];
                $this->format = strtolower($this->param('format'));
        }

        function _error($id) {
                if($id = 404) {
                        $rep = $this->getResponse('redirect');
		        $rep->action = 'jelix~error:notfound';
        		return $rep;
                }
        }
}
