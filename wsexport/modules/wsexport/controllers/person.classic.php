<?php
/**
* @package   wsexport
* @subpackage wsexport
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/

global $gJConfig;
$basePath = $gJConfig->_modulesPathList['wsexport'].'/classes/wikisource-export';
include_once $gJConfig->_modulesPathList['wsexport'].'/classes/wikisource-export/book/init.php';
jClasses::inc('myController');

class personCtrl extends myController {
        /**
         * search and list of authors
         */
        public function index() {
                $params = array();
                $params['lang'] = $this->lang;
                $startsWith = $this->boolParam('startsWith', false);
                jClasses::inc('PersonStorage');
                foreach(PersonStorage::$SEARCH_KEYS as $key) {
                        if($this->param($key)) {
                                if($startsWith)
                                        $params[$key] = $this->param($key) . '%';
                                else
                                        $params[$key] = '%' . $this->param($key) . '%';
                        }
                }
                switch($this->param('order')) {
                        case 'title':
                                $order = 'title';
                                break;
                        case 'name':
                                $order = 'name';
                                break;
                        case 'birthDate':
                                $order = 'birthDate';
                                break;
                        case 'deathDate':
                                $order = 'deathDate';
                                break;
                        case 'created':
                                $order = 'created';
                                break;
                        case 'modified':
                                $order = 'modified';
                                break;
                        default:
                                $order = 'key';
                }
                $wayAsc = $this->boolParam('asc', true);
                if($this->format == 'sitemap')
                        $itemPerPage = $this->intParam('itemPerPage', 50000);
                else
                        $itemPerPage = $this->intParam('itemPerPage', 20);
                $offset = $this->intParam('offset', 0);
                $personStorage = jClasses::create('PersonStorage');
                $results = $personStorage->gets($params, $order, $wayAsc, $itemPerPage, $offset);
                $count = $results[0];
                $people = $results[1];
                switch($this->format) {
                        case 'sitemap':
                                $rep = $this->getResponse('sitemap');
                                foreach($people as $person) {
                                        $updated = explode(' ', $person->updated);
                                        $rep->addUrl(jUrl::get('person:view', array('lang' => $person->lang, 'title' => $person->title)), $updated[0], 'weekly', '0.5');
			        }
                                break;
                        case 'atom':
                                $rep = $this->getResponse('xml');
                                $rep->addHttpHeader('Content-Type', 'application/atom+xml;profile=opds-catalog;kind=navigation', true);
		                $rep->contentTpl = 'index.person.atom';
                                $rep->content->assign('people', $people);
                                $rep->content->assign('lang', $this->lang);
                                $rep->content->assign('params', $this->request->params);
                                $rep->content->assign('count', $count);
                                $rep->content->assign('offset', $offset);
                                $rep->content->assign('itemPerPage', $itemPerPage);
                                $dt = new jDateTime();
                                $dt->now();
                                $now = $dt->toString(jDateTime::ISO8601_FORMAT);
                                $rep->content->assign('now', $now);
                                break;
                        case 'html':
                        case '':
                                $rep = $this->_getHtmlResponse();
                                $rep->noRobots = true;
                                $rep->addLink(jUrl::get('person:index', array_merge($this->request->params, array('format' => 'atom'))), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=acquisition', jLocale::get('wsexport.opds_catalog'));
                                $rep->headTagAttributes['profile'] .= ' http://a9.com/-/spec/opensearch/1.1/';
                                $rep->addHeadContent('<meta name="totalResults" content="' . $count . '" />');
                                $rep->addHeadContent('<meta name="startIndex" content="' . $offset . '" />');
                                $rep->addHeadContent('<meta name="itemsPerPage" content="' . $itemPerPage . '" />');
		                $rep->title = ''; //TODO
                                $tpl = new jTpl();
                                $tpl->assign('people', $people);
                                $tpl->assign('lang', $this->lang);
                                $tpl->assign('params', $this->request->params);
                                $tpl->assign('count', $count);
                                $tpl->assign('offset', $offset);
                                $tpl->assign('itemPerPage', $itemPerPage);
                                $rep->body->assign('MAIN', $tpl->fetch('index.person.html'));
                                break;
                        default:
                                return $this->_error(404);
                }
		return $rep;
        }

        /**
         * informations on a person
         */
        public function view() {
                $title = $this->_getTitle();
                $wayAsc = $this->boolParam('asc', true);
                $itemPerPage = $this->intParam('itemPerPage', 20);
                $offset = $this->intParam('offset', 0);

                $personStorage = jClasses::create('PersonStorage');
                try {
                        $person = $personStorage->get($this->lang, $title);
		} catch(HttpException $e) {
        		$person = jClasses::create('PersonRecord');
                        $person->name = $this->param('title');
                }

                $bookStorage = jClasses::create('BookStorage');
                $results = $bookStorage->getMetadatasByPerson($this->lang, $person->name, $wayAsc, $itemPerPage, $offset);
                $count = $results[0];
                $books = $results[1];
                switch($this->format) {
                        case 'atom':
                                $rep = $this->getResponse('xml');
                                $rep->addHttpHeader('Content-Type', 'application/atom+xml;profile=opds-catalog;kind=acquisition', true);
                                $rep->contentTpl = 'index.book.atom';
                                $rep->content->assign('books', $books);
                                $rep->content->assign('lang', $this->lang);
                                $rep->content->assign('params', $this->request->params);
                                $rep->content->assign('count', $count);
                                $rep->content->assign('offset', $offset);
                                $rep->content->assign('itemPerPage', $itemPerPage);
                                $dt = new jDateTime();
                                $dt->now();
                                $now = $dt->toString(jDateTime::ISO8601_FORMAT);
                                $rep->content->assign('now', $now);
                                break;
                        case 'html':
                        case '':
                                $rep = $this->_getHtmlResponse();
                                $rep->addLink(jUrl::get('person:view', array('lang' => $this->lang, 'title' => $title)), 'canonical');
		                $rep->title = $person->name;

                                $rep->htmlTagAttributes['prefix'] = 'og: http://ogp.me/ns#';
                                $rep->addHeadContent('<meta property="og:title" content="' . htmlspecialchars($person->name) . '" />');
                                $rep->addHeadContent('<meta property="og:url" content="' . jUrl::getFull('person:view', array('lang' => $person->lang, 'title' => $person->title), jUrl::XMLSTRING) . '" />');
                                $rep->addHeadContent('<meta property="og:image" content="' . $person->imageUrl . '" />');
                                $rep->addHeadContent('<meta property="og:locale" content="' . $this->_getFullLang($person->lang) . '" />');
                                $rep->addHeadContent('<meta property="og:site_name" content="' . jLocale::get('wsexport.site.short_name') . '" />');

                                $rep->headTagAttributes['profile'] .= ' http://microformats.org/profile/rel-license';
                                $rep->addLink('http://creativecommons.org/licenses/by-sa/3.0/', 'licence', null, 'CC BY-SA 3.0');
                                $rep->addLink('http://www.gnu.org/copyleft/fdl.html', 'licence', null, 'GNU FDL');

                                $tpl = new jTpl();
		                $tpl->assign('person', $person);
                                $tpl->assign('books', $books);
                                $tpl->assign('lang', $this->lang);
                                $tpl->assign('params', $this->request->params);
                                $tpl->assign('count', $count);
                                $tpl->assign('offset', $offset);
                                $tpl->assign('itemPerPage', $itemPerPage);
        		        $rep->body->assign('MAIN', $tpl->fetch('view.person.html'));
                                break;
                        default:
                                return $this->_error(404);
                }
		return $rep;
        }

}
