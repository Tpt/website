<?php
/**
* @package   wsexport
* @subpackage wsexport
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/

global $gJConfig;
global $wsexportConfig;
$wsexportConfig = array(
        'basePath' => $gJConfig->_modulesPathList['wsexport'].'classes/wikisource-export',
        'tempPath' => jApp::tempPath('wiki')
);
include_once $gJConfig->_modulesPathList['wsexport'].'/classes/wikisource-export/book/init.php';
jClasses::inc('myController');

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

        protected function _getLanguages() {
                global $gJConfig;
                return explode(',', $gJConfig->languages);
        }

        protected function _getTitle() {
                return str_replace(' ', '_', urldecode($this->param('title')));
        }

        protected function _getHtmlResponse() {
                $rep = $this->getResponse('html');
                $rep->lang = $this->_getLang();
                $rep->languages = $this->_getLanguages();
                return $rep;
        }

        protected function _getFullLang($lang) {
                return $lang . '_' . strtoupper($lang);
        }

        protected function _getAtomResponse($profile, $template = '', $title = '') {
                $rep = $this->getResponse('xml');
                $rep->addHttpHeader('Content-Type', 'application/atom+xml;' . $profile, true);
                if($template != '') {
                        $rep->contentTpl = $template;
                        $rep->content->assign('languages', $this->_getLanguages());
                        $rep->content->assign('lang', $this->_getLang());
                        $rep->content->assign('params', $this->request->params);
                        $rep->content->assign('title', $title);
                        $dt = new jDateTime();
                        $dt->now();
                        $now = $dt->toString(jDateTime::ISO8601_FORMAT);
                        $rep->content->assign('now', $now);
                }
                return $rep;
        }
}
