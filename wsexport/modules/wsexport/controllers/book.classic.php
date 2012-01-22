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

class bookCtrl extends myController {
        /**
         * search and list of books
         */
        public function index() {
                $lang = $this->_getLang();
                $params = array();
                $params['lang'] = $lang;
                $startsWith = $this->boolParam('startsWith', false);
                jClasses::inc('BookStorage');
                foreach(BookStorage::$SEARCH_KEYS as $key) {
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
                                $order = 'name';
                }
                $wayAsc = $this->boolParam('asc', true);
                if($this->format == 'sitemap')
                        $itemPerPage = $this->intParam('itemPerPage', 50000);
                else
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
                                        $rep->addUrl(jUrl::get('book:view', array('lang' => $book->lang, 'format' => 'html', 'title' => $book->title)), $updated[0], 'weekly', '0.5');
                                }
                                break;
                        case 'atom':
                                $rep = $this->getResponse('xml');
                                $rep->addHttpHeader('Content-Type', 'application/atom+xml;profile=opds-catalog;kind=acquisition', true);
                                $rep->contentTpl = 'index.book.atom';
                                $rep->content->assign('books', $books);
                                $rep->content->assign('lang', $lang);
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
                                $rep = $this->_getHtmlResponse();
                                $rep->noRobots = true;
                                $rep->addLink(jUrl::get('book:index', array_merge($this->request->params, array('format' => 'atom'))), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=acquisition', jLocale::get('wsexport.opds_catalog'));
                                $rep->headTagAttributes['profile'] .= ' http://a9.com/-/spec/opensearch/1.1/';
                                $rep->addHeadContent('<meta name="totalResults" content="' . $count . '" />');
                                $rep->addHeadContent('<meta name="startIndex" content="' . $offset . '" />');
                                $rep->addHeadContent('<meta name="itemsPerPage" content="' . $itemPerPage . '" />');
                                $rep->title = ''; //TODO
                                $tpl = new jTpl();
                                $tpl->assign('books', $books);
                                $tpl->assign('lang', $lang);
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
                $lang = $this->_getLang();
                $title = $this->_getTitle();
                $bookStorage = jClasses::create('BookStorage');
                try {
                        $book = $bookStorage->getMetadata($lang, $title);
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
                                $rep = $this->_getHtmlResponse();
                                $rep->addLink(jUrl::get('book:view', array('lang' => $book->lang, 'format' => 'atom', 'title' => $title)), 'alternate', 'application/atom+xml;type=entry;profile=opds-catalog', jLocale::get('wsexport.opds_catalog'));
                                $rep->addLink(jUrl::get('book:view', array('lang' => $book->lang, 'format' => 'html', 'title' => $title)), 'canonical');
                                $rep->title = $book->name;

                                $dublincore = $rep->getPlugin('dublincore');
                                $dublincore->addMeta('DC.identifier', 'http://' . $book->lang . '.wikisource.org/wiki/' . $book->title, 'DCTERMS.URI');
                                $dublincore->addMeta('DC.language', $book->lang);
                                $dublincore->addMeta('DC.title', htmlspecialchars($book->name));
                                $dublincore->addMeta('DC.creator', htmlspecialchars($book->author));
                                $dublincore->addMeta('DC.publisher',htmlspecialchars($book->publisher));
                                $dublincore->addLink('DC.source', 'http://' . $book->lang . '.wikisource.org/wiki/' . htmlspecialchars($book->title));
                                $dublincore->addLink('DC.rights', 'http://creativecommons.org/licenses/by-sa/3.0/', 'en');
                                $dublincore->addLink('DC.rights', 'http://www.gnu.org/copyleft/fdl.html', 'en');
                                $dublincore->addMeta('DC.format', 'application/xhtml+xml', 'DCTERMS.IMT');
                                $dublincore->addMeta('DC.type', 'Text', 'DCTERMS.DCMIType');

                                $rep->htmlTagAttributes['prefix'] = 'og: http://ogp.me/ns#';
                                $rep->headTagAttributes['prefix'] = 'book: http://ogp.me/ns/book#';
                                $rep->addHeadContent('<meta property="og:title" content="' . htmlspecialchars($book->name) . '" />');
                                $rep->addHeadContent('<meta property="og:type" content="book" />');
                                $rep->addHeadContent('<meta property="og:url" content="' . jUrl::getFull('book:view', array('lang' => $book->lang, 'format' => 'html', 'title' => $book->title), jUrl::XMLSTRING) . '" />');
                                $rep->addHeadContent('<meta property="og:image" content="' . $book->coverUrl . '" />');
                                $rep->addHeadContent('<meta property="og:locale" content="' . $this->_getFullLang($book->lang) . '" />');
                                $rep->addHeadContent('<meta property="og:site_name" content="' . jLocale::get('wsexport.site.short_name') . '" />');
                                $rep->addHeadContent('<meta property="book:author:username" content="' . htmlspecialchars($book->author) . '" />');
                                if(is_numeric($book->year))
                                        $rep->addHeadContent('<meta property="book:release_date" content="' . $book->year . '" />');

                                $rep->headTagAttributes['profile'] .= ' http://microformats.org/profile/rel-license';
                                $rep->addLink('http://creativecommons.org/licenses/by-sa/3.0/', 'licence', null, 'CC BY-SA 3.0');
                                $rep->addLink('http://www.gnu.org/copyleft/fdl.html', 'licence', null, 'GNU FDL');

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
                $lang = $this->_getLang();
                global $gJConfig;
                $title = $this->_getTitle();
                $withPictures = $this->boolParam('withPictures', true);
                if($gJConfig->useToolserverExport == 'true') {
                        $rep = $this->getResponse('redirectUrl');
                        $rep->url = 'http://toolserver.org/~tpt/wsexport/book.php?lang='.$lang.'&page='.urlencode($title).'&format='.urlencode($this->format).'&withPictures='.$withPictures;
                } else {
                        $bookStorage = jClasses::create('BookStorage');
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
                                $book = $bookStorage->get($lang, $title, $withPictures);
                        } catch(HttpException $e) {
                                return $this->_error($e->getCode());
                        }
                        $bookStorage->incrementDownload($lang, $title);
                        $rep = $this->getResponse('binary');
                        $rep->outputFileName = $title . '.' . $generator->getExtension();
                        $rep->mimeType = $generator->getMimeType();
                        $rep->content = $generator->create($book);
                }
                return $rep;
        }

        public function search() {
                $lang = $this->_getLang();
                $query = trim($this->param('q'));
                $itemPerPage = $this->intParam('itemPerPage', 20);
                $offset = $this->intParam('offset', 0);
                $startsWith = $this->boolParam('startsWith', false);
                $bookStorage = jClasses::create('BookStorage');
                try {
                        $book = $bookStorage->getMetadata($lang, str_replace(' ', '_', $query));
                        $rep = $this->getResponse('redirect');
                        $rep->action = 'book:view';
                        $rep->params = array('lang' => $book->lang, 'format' => $this->format, 'title' => $book->title);
                        return $rep;
                } catch(HttpException $e) {
                }
                if($startsWith)
                        $results = $bookStorage->searchMetadatas($lang, $query . '%', $itemPerPage, $offset);
                else
                        $results = $bookStorage->searchMetadatas($lang, '%' . $query . '%', $itemPerPage, $offset);
                $count = $results[0];
                $books = $results[1];
                switch($this->format) {
                        case 'opensearchsuggestions':
                                $rep = $this->getResponse('opensearchsuggestions');
                                $rep->query = $query;
                                foreach($books as $book) {
                                        $rep->addItem($book->name, $book->name, jUrl::getFull('book:view', array('lang' => $book->lang, 'format' => 'html', 'title' => $book->title)));
                                }
                                break;
                        case 'atom':
                                $rep = $this->getResponse('xml');
                                $rep->addHttpHeader('Content-Type', 'application/atom+xml;profile=opds-catalog;kind=acquisition', true);
                                $rep->contentTpl = 'index.book.atom';
                                $rep->content->assign('books', $books);
                                $rep->content->assign('lang', $lang);
                                $rep->content->assign('params', $this->request->params);
                                $rep->content->assign('count', $count);
                                $rep->content->assign('offset', $offset);
                                $rep->content->assign('itemPerPage', $itemPerPage);
                                $rep->content->assign('query', $query);
                                $dt = new jDateTime();
                                $dt->now();
                                $now = $dt->toString(jDateTime::ISO8601_FORMAT);
                                $rep->content->assign('now', $now);
                                break;
                        case 'html':
                                $rep = $this->_getHtmlResponse();
                                $rep->noRobots = true;
                                $rep->addLink(jUrl::get('book:index', array_merge($this->request->params, array('format' => 'atom'))), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=acquisition', jLocale::get('wsexport.opds_catalog'));
                                $rep->headTagAttributes['profile'] .= ' http://a9.com/-/spec/opensearch/1.1/';
                                $rep->addHeadContent('<meta name="totalResults" content="' . $count . '" />');
                                $rep->addHeadContent('<meta name="startIndex" content="' . $offset . '" />');
                                $rep->addHeadContent('<meta name="itemsPerPage" content="' . $itemPerPage . '" />');
                                $rep->title = ''; //TODO
                                $tpl = new jTpl();
                                $tpl->assign('books', $books);
                                $tpl->assign('lang', $lang);
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

        public function random() {
                $lang = $this->_getLang();
                $bookStorage = jClasses::create('BookStorage');
                $title = $bookStorage->getRandomTitle($lang);
                $rep = $this->getResponse('redirect');
                $rep->action = 'book:view';
                $rep->params = array('lang' => $lang, 'format' => $this->format, 'title' => urlencode($title));
                return $rep;
        }

        public function updateAll() {
                $lang = $this->_getLang();
                $bookStorage = jClasses::create('BookStorage');
                $book = $bookStorage->setMetadataFromCategory($lang, 'Catégorie:Bon_pour_export');
                $personStorage = jClasses::create('PersonStorage');
                $personStorage->refresh($lang);
                $bookStorage->updateTemp($lang);
                jMessage::add('Mise à jour effectuée');
                $rep = $this->getResponse('redirect');
                $rep->action = 'book:index';
                $rep->params = array('lang' => $lang, 'format' => 'html', 'order' => 'updated', 'asc' => 'false');
                return $rep;
        }
}
