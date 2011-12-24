<?php
/**
* @package   wsexport
* @subpackage wsexport
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/

/**
 * All datas of a book
 * @package   wsexport
 * @subpackage wsexport
 */
class BookRecord extends Book {

        public $created = '';
        public $updated = '';
        public $downloads = 0;
        public $coverUrl = '';
        public $iconUrl = '';
}
