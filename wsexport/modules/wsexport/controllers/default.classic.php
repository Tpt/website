<?php
/**
* @package   wsexport
* @subpackage wsexport
* @author Thomas Pellissier Tanon
* @copyright 2011 Thomas Pellissier Tanon
* @licence http://www.gnu.org/licenses/gpl.html GNU General Public Licence
*/
jClasses::inc('myController');

class defaultCtrl extends myController {

        public function index() {
                switch($this->format) {
                        case 'opensearchdescription':
                                $rep = $this->getResponse('opensearchdescription');
                                $rep->infos->shortName = jLocale::get('wsexport.site.short_name');
                                $rep->infos->description = jLocale::get('wsexport.site.description');
                                $rep->infos->tags = '';
                                $rep->infos->contact = '';
                                $rep->infos->longName = jLocale::get('wsexport.site.long_name');
                                $rep->infos->imageType = '';
                                $rep->infos->imageLink = '';
                                $rep->infos->iconType = '';
                                $rep->infos->iconLink = '';
                                $rep->infos->exemple = 'Declaration';
                                $rep->infos->developer = '';
                                $rep->infos->attribution = '';
                                $rep->infos->syndicationRight = 'open';
                                $rep->infos->languages = array('*');
                                $rep->addItem($rep->createItem('application/opensearchdescription+xml', jUrl::getFull('#', array('format' => 'opensearchdescription'), jUrl::XMLSTRING), 'self'));
                                $rep->addItem($rep->createItem('application/x-suggestions+json', jUrl::getFull('#', array('format' => 'opensearchsuggestions', 'lang' => '{language}', 'q' => '{searchTerms}', 'limit' => '{count?}', 'offset' => '{startIndex?}'), jUrl::XMLSTRING), 'suggestions'));
                                $rep->addItem($rep->createItem('application/atom+xml', jUrl::getFull('person:search', array('format' => 'atom', 'lang' => '{language}', 'q' => '{searchTerms}', 'limit' => '{count?}', 'offset' => '{startIndex?}'), jUrl::XMLSTRING), 'results'));
                                $rep->addItem($rep->createItem('application/xhtml+xml', jUrl::getFull('person:search', array('format' => 'html', 'q' => '{searchTerms}', 'lang' => '{language}', 'limit' => '{count?}', 'offset' => '{startIndex?}'), jUrl::XMLSTRING), 'results'));
                                break;
                        default:
                                $rep = $this->getResponse('redirect');
                                $rep->action = 'default:home';
                                $rep->params = array('lang' => $this->_getLang(), 'format' => $this->format);
                }
                return $rep;
        }

        public function home() {
                $lang = $this->_getLang();
                switch($this->format) {
                        case 'sitemap':
                                $rep = $this->getResponse('sitemap');
                                //$rep->addUrl(jUrl::get('#'), date('Y-m-d'));
                                $rep->addSitemap(jUrl::get('book:index', array('lang' => $lang, 'format' => 'sitemap')), date('Y-m-d'));
                                $rep->addSitemap(jUrl::get('person:index', array('lang' => $lang, 'format' => 'sitemap')), date('Y-m-d'));
                                break;
                        case 'atom':
                                $rep = $this->_getAtomResponse('profile=opds-catalog;kind=navigation', 'home.default.atom', jLocale::get('wsexport.mainpage'));
                                break;
                        case 'html':
                                $rep = $this->_getHtmlResponse();
                                $rep->action = 'home';
                                $rep->addLink(jUrl::get('default:home', array('lang' => $lang, 'format' => 'atom')), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=navigation', jLocale::get('wsexport.opds_catalog'));
                                $rep->htmlTagAttributes['prefix'] = 'og: http://ogp.me/ns#';
                                $rep->addHeadContent('<meta property="og:title" content="' . jLocale::get('wsexport.site.long_name') . '" />');
                                $rep->addHeadContent('<meta property="og:type" content="website" />');
                                $rep->addHeadContent('<meta property="og:url" content="' . jUrl::getFull('#') . '" />');
                                //$rep->addHeadContent('<meta property="og:image" content="" />');
                                $rep->addHeadContent('<meta property="og:locale" content="' . $this->_getFullLang($lang) . '" />');
                                $rep->addHeadContent('<meta property="og:site_name" content="' . jLocale::get('wsexport.site.short_name') . '" />');
                                $rep->addHeadContent('<meta property="og:description" content="' . jLocale::get('wsexport.site.description') . '" />');
                                $tpl = new jTpl();
                                $tpl->assign('lang', $lang);
                                $rep->body->assign('MAIN', $tpl->fetch('home.default.html'));
                                break;
                        default:
                                return $this->_error(404);
                }
                return $rep;
        }

        public function about() {
                switch($this->format) {
                        case 'html':
                                $rep = $this->_getHtmlResponse();
                                $rep->action = 'about';
                                $tpl = new jTpl();
                                $tpl->assign('lang', $this->_getLang());
                                $rep->body->assign('MAIN', $tpl->fetch('about.default.html'));
                                break;
                        default:
                                return $this->_error(404);
                }
                return $rep;
        }
}
