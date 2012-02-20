<?php
/**
* @package   wsexport
* @subpackage wsexport
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/

jClasses::inc('myController');

class personCtrl extends myController {
        /**
         * search and list of authors
         */
        public function index() {
                $lang = $this->_getLang();
                $params = array();
                $params['lang'] = $lang;
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
                                        $rep->addUrl(jUrl::get('person:view', array('lang' => $person->lang, 'format' => 'html', 'title' => $person->title)), $updated[0], 'weekly', '0.5');
                                }
                                break;
                        case 'atom':
                                $rep = $this->_getAtomResponse('profile=opds-catalog;kind=navigation', 'index.person.atom');
                                foreach($people as $person) {
                                        $person->imageType = jFile::getMimeTypeFromFilename(end(explode('/', $person->imageUrl)));
                                }
                                $rep->content->assign('people', $people);
                                $rep->content->assign('count', $count);
                                $rep->content->assign('offset', $offset);
                                $rep->content->assign('itemPerPage', $itemPerPage);
                                break;
                        case 'html':
                                $rep = $this->_getHtmlResponse();
                                $rep->noRobots = true;
                                $rep->addLink(jUrl::get('person:index', array_merge($this->request->params, array('format' => 'atom'))), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=navigation', jLocale::get('wsexport.opds_catalog'));
                                $rep->headTagAttributes['profile'] .= ' http://a9.com/-/spec/opensearch/1.1/';
                                $rep->addHeadContent('<meta name="totalResults" content="' . $count . '" />');
                                $rep->addHeadContent('<meta name="startIndex" content="' . $offset . '" />');
                                $rep->addHeadContent('<meta name="itemsPerPage" content="' . $itemPerPage . '" />');
                                $rep->title = ''; //TODO
                                $tpl = new jTpl();
                                $tpl->assign('people', $people);
                                $tpl->assign('lang', $lang);
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
                $lang = $this->_getLang();
                $title = $this->_getTitle();
                $wayAsc = $this->boolParam('asc', true);
                $itemPerPage = $this->intParam('itemPerPage', 20);
                $offset = $this->intParam('offset', 0);

                $personStorage = jClasses::create('PersonStorage');
                try {
                        $person = $personStorage->get($lang, $title);
                } catch(HttpException $e) {
                        $person = jClasses::create('PersonRecord');
                        $person->name = $this->param('title');
                }

                $bookStorage = jClasses::create('BookStorage');
                $results = $bookStorage->getMetadatasByPerson($lang, $person->name, $wayAsc, $itemPerPage, $offset);
                $count = $results[0];
                $books = $results[1];
                switch($this->format) {
                        case 'atom':
                                $rep = $this->getResponse('xml');
                                $rep = $this->_getAtomResponse('profile=opds-catalog;kind=acquisition', 'index.book.atom', $person->name);
                                $rep->content->assign('books', $books);
                                $rep->content->assign('count', $count);
                                $rep->content->assign('offset', $offset);
                                $rep->content->assign('itemPerPage', $itemPerPage);
                                $rep->content->assign('icon', $person->imageUrl);
                                break;
                        case 'html':
                                $rep = $this->_getHtmlResponse();
                                $rep->addLink(jUrl::get('person:view', array('lang' => $lang, 'format' => 'html', 'title' => $title)), 'canonical');
                                $rep->addLink(jUrl::get('person:view', array('lang' => $lang, 'format' => 'atom', 'title' => $title)), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=acquisition', jLocale::get('wsexport.opds_catalog'));
                                $rep->title = $person->name;

                                $rep->htmlTagAttributes['prefix'] = 'og: http://ogp.me/ns#';
                                $rep->addHeadContent('<meta property="og:title" content="' . htmlspecialchars($person->name) . '" />');
                                $rep->addHeadContent('<meta property="og:url" content="' . jUrl::getFull('person:view', array('lang' => $person->lang, 'format' => 'html', 'title' => $person->title), jUrl::XMLSTRING) . '" />');
                                $rep->addHeadContent('<meta property="og:image" content="' . $person->imageUrl . '" />');
                                $rep->addHeadContent('<meta property="og:locale" content="' . $this->_getFullLang($person->lang) . '" />');
                                $rep->addHeadContent('<meta property="og:site_name" content="' . jLocale::get('wsexport.site.short_name') . '" />');

                                $rep->headTagAttributes['profile'] .= ' http://microformats.org/profile/rel-license';
                                $rep->addLink('http://creativecommons.org/licenses/by-sa/3.0/', 'licence', null, 'CC BY-SA 3.0');
                                $rep->addLink('http://www.gnu.org/copyleft/fdl.html', 'licence', null, 'GNU FDL');

                                $tpl = new jTpl();
                                $tpl->assign('person', $person);
                                $tpl->assign('books', $books);
                                $tpl->assign('lang', $lang);
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

        public function random() {
                $lang = $this->_getLang();
                $personStorage = jClasses::create('PersonStorage');
                $title = $personStorage->getRandomTitle($lang);
                $rep = $this->getResponse('redirect');
                $rep->action = 'person:view';
                $rep->params = array('lang' => $lang, 'format' => $this->format, 'title' => urlencode($title));
                return $rep;
        }
}
