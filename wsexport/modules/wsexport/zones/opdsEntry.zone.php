<?php
/**
* @package   wsexport
* @subpackage wsexport
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/

class opdsEntryZone extends jZone {
        protected $_tplname = 'opds_entry.zone.atom';
 
        protected function _prepareTpl() {
                $this->_tpl->assign('main', $this->param('main', false));
                $this->_tpl->assign('book', $this->param('book'));
                $this->_tpl->assign('self', jUrl::getCurrentUrl(true));
                $this->_tpl->assign('coverType', jFile::getMimeTypeFromFilename(end(explode('/', $this->param('book')->coverUrl))));
        }
}
