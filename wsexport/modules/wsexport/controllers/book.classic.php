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

class bookCtrl extends myController {
        /**
         * search and list of books
         */
        public function index() {
                $params = array();
                $params['lang'] = $this->lang;
                if($this->param('title'))
                        $params['title'] = '%' . $this->param('title') . '%';
                if($this->param('author'))
                        $params['author'] = '%' . $this->param('author') . '%';
                if($this->param('translator'))
                        $params['translator'] = '%' . $this->param('translator') . '%';
                if($this->param('illustrator'))
                        $params['illustrator'] = '%' . $this->param('illustrator') . '%';
                if($this->param('year'))
                        $params['year'] = $this->param('year');
                switch($this->param('order')) {
                        case 'title':
                                $order = 'title';
                                break;
                        case 'year':
                                $order = 'year';
                                break;
                        case 'created':
                                $order = 'created';
                                break;
                        case 'modified':
                                $order = 'modified';
                                break;
                        case 'downloads':
                                $order = 'downloads';
                                break;
                        default:
                                $order = '';
                }
                $wayAsc = $this->boolParam('asc', true);
                $itemPerPage = $this->intParam('itemPerPage', 20);
                $offset = $this->intParam('offset', 0);
                $bookStorage = jClasses::create('BookStorage');
                $results = $bookStorage->getMetadatas($params, $order, $wayAsc, $itemPerPage, $offset);
                $count = $results[0];
                $books = $results[1];
                switch($this->format) {
                        case 'sitemap':
                                $rep = $this->getResponse('sitemap');
                                foreach($books as $book) {
                                        $updated = explode(' ', $book->updated);
                                        $rep->addUrl(jUrl::get('book:view', array('lang' => $book->lang, 'title' => $book->title)), $updated[0], 'weekly', '0.5');
			        }
                                break;
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
                                $rep = $this->getResponse('html');
                                $rep->addLink(jUrl::get('book:index', array_merge($this->request->params, array('format' => 'atom'))), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=acquisition', jLocale::get('wsexport.opds_catalog'));
                                $rep->addHeadContent('<meta name="totalResults" content="' . $count . '" />');
                                $rep->addHeadContent('<meta name="startIndex" content="' . $offset . '" />');
                                $rep->addHeadContent('<meta name="itemsPerPage" content="' . $itemPerPage . '" />');
		                $rep->title = ''; //TODO
                                $tpl = new jTpl();
                                $tpl->assign('books', $books);
                                $tpl->assign('lang', $this->lang);
                                $tpl->assign('params', $this->request->params);
                                $tpl->assign('count', $count);
                                $tpl->assign('offset', $offset);
                                $tpl->assign('itemPerPage', $itemPerPage);
                                $rep->body->assign('MAIN', $tpl->fetch('index.book.html'));
                                break;
                        default:
                                return $this->_error(404);
                }
		return $rep;
        }

        /**
         * informations on a book
         */
        public function view() {
                $title = $this->param('title');
                $bookStorage = jClasses::create('BookStorage');
                try {
                        $book = $bookStorage->getMetadata($this->lang, $title);
		} catch(HttpException $e) {
        		return $this->_error(404);
                }
                switch($this->format) {
                        case 'atom':
                                $rep = $this->getResponse('xml');
                                $rep->addHttpHeader('Content-Type', 'application/atom+xml;type=entry;profile=opds-catalog', true);
		                $rep->content = jZone::get('opdsEntry', array('main' => true, 'book' => $book));
                                break;
                        case 'html':
                        case '':
                                $rep = $this->getResponse('html');
                                $rep->addLink(jUrl::get('default:index', array('lang' => $this->lang, 'title' => $title, 'format' => 'atom')), 'alternate', 'application/atom+xml;type=entry;profile=opds-catalog', jLocale::get('wsexport.opds_catalog'));
		                $rep->title = $book->name;
                                $tpl = new jTpl();
		                $tpl->assign('book', $book);
        		        $rep->body->assign('MAIN', $tpl->fetch('view.book.html'));
                                break;
                        default:
                                return $this->_error(404);
                }
		return $rep;
        }

        /**
         * get the book
         */
        public function get() {
                $title = $this->param('title');
                $withPictures = $this->boolParam('withPictures', true);
                $bookStorage = jClasses::create('BookStorage');
                global $gJConfig;
                switch($this->format) {
                        case 'epub':
                                include($gJConfig->_modulesPathList['wsexport'].'/classes/wikisource-export/book/formats/Epub2Generator.php');
                                $generator = new Epub2Generator();
                                break;
                        case 'odt':
                                include($gJConfig->_modulesPathList['wsexport'].'/classes/wikisource-export/book/formats/OdtGenerator.php');
                                $generator = new OdtGenerator();
                                break;
                        case 'xhtml':
                                include($gJConfig->_modulesPathList['wsexport'].'/classes/wikisource-export/book/formats/XhtmlGenerator.php');
                                $generator = new XhtmlGenerator();
                                break;
                        default:
                                return $this->_error(404);
                }
                try {
                        $book = $bookStorage->get($this->lang, $title, $withPictures);
		} catch(HttpException $e) {
        		return $this->_error($e->code);
                }
                $bookStorage->incrementDownload($this->lang, $title);
                $rep = $this->getResponse('binary');
                $rep->outputFileName = $title . '.' . $generator->getExtension();
                $rep->mimeType = $generator->getMimeType();
                $rep->content = $generator->create($book);
                return $rep;
        }

        public function search() {
                $query = trim($this->param('q'));
                $itemPerPage = $this->intParam('itemPerPage', 20);
                $offset = $this->intParam('offset', 0);
                $bookStorage = jClasses::create('BookStorage');
                try {
                        $book = $bookStorage->getMetadata($this->lang, str_replace(' ', '_', $query));
                        $rep = $this->getResponse('redirect');
		        $rep->action = 'book:view';
                        $rep->params = array('lang' => $book->lang, 'title' => $book->title);
		        return $rep;
		} catch(HttpException $e) {
                }
                $results = $bookStorage->searchMetadatas($this->lang, $query, $itemPerPage, $offset);
                $count = $results[0];
                $books = $results[1];
                switch($this->format) {
		        case 'opensearchdescription':
			        $rep = $this->getResponse('opensearchdescription');
			        $rep->infos->shortName = '';
			        $rep->infos->description = '';
			        $rep->infos->tags = '';
			        $rep->infos->contact = '';
			        $rep->infos->longName = '';
			        $rep->infos->imageType = '';
			        $rep->infos->imageLink = '';
			        $rep->infos->iconType = '';
			        $rep->infos->iconLink = '';
			        $rep->infos->exemple = '';
			        $rep->infos->developer = '';
			        $rep->infos->attribution = '';
			        $rep->infos->syndicationRight = 'open';
			        $rep->infos->languages = array('*');
			        $rep->addItem($rep->createItem('application/opensearchdescription+xml', jUrl::getFull('#', array('format' => 'opensearchdescription'), jUrl::XMLSTRING), 'self'));
			        $rep->addItem($rep->createItem('application/x-suggestions+json', jUrl::getFull('#', array('format' => 'opensearchsuggestions', 'lang' => '{language}', 'q' => '{searchTerms}', 'limit' => '{count?}', 'offset' => '{startIndex?}'), jUrl::XMLSTRING), 'suggestions'));
			        $rep->addItem($rep->createItem('application/atom+xml', jUrl::getFull('#', array('format' => 'atom', 'lang' => '{language}', 'q' => '{searchTerms}', 'limit' => '{count?}', 'offset' => '{startIndex?}'), jUrl::XMLSTRING), 'results'));
			        $rep->addItem($rep->createItem('application/xhtml+xml', jUrl::getFull('#', array('q' => '{searchTerms}', 'lang' => '{language}', 'limit' => '{count?}', 'offset' => '{startIndex?}'), jUrl::XMLSTRING), 'results'));
                                break;
		        case 'opensearchsuggestions':
			        $rep = $this->getResponse('opensearchsuggestions');
			        foreach($books as $book) {
				        $rep->addItem($book->name, $book->name, jUrl::getFull('book:view', array('lang' => $book->lang, 'title' => $book->title)));
			        }
                                break;
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
                                $rep = $this->getResponse('html');
                                $rep->addLink(jUrl::get('book:index', array_merge($this->request->params, array('format' => 'atom'))), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=acquisition', jLocale::get('wsexport.opds_catalog'));
                                $rep->addHeadContent('<meta name="totalResults" content="' . $count . '" />');
                                $rep->addHeadContent('<meta name="startIndex" content"' . $offset . '" />');
                                $rep->addHeadContent('<meta name="itemsPerPage" content="' . $itemPerPage . '" />');
		                $rep->title = ''; //TODO
                                $tpl = new jTpl();
                                $tpl->assign('books', $books);
                                $tpl->assign('lang', $this->lang);
                                $tpl->assign('params', $this->request->params);
                                $tpl->assign('count', $count);
                                $tpl->assign('offset', $offset);
                                $tpl->assign('itemPerPage', $itemPerPage);
                                $rep->body->assign('MAIN', $tpl->fetch('index.book.html'));
                                break;

                        default:
                                return $this->_error(404);
                }
		return $rep;
        }


        public function updateAll() {
                $bookStorage = jClasses::create('BookStorage');
                $book = $bookStorage->setMetadataFromCategory($this->lang, 'Catégorie:Bon_pour_export');
                jMessage::add('Mise à jour effectuée');
                $rep = $this->getResponse('redirect');
		$rep->action = 'book:index';
		return $rep;
        }
}
