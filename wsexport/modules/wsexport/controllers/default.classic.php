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
        function index() {
                switch($this->format) {
                        case 'sitemap':
                                $rep = $this->getResponse('sitemap');
                                //$rep->addUrl(jUrl::get('#'), date('Y-m-d'));
			        $rep->addSitemap(jUrl::get('book:index', array('format' => 'sitemap')), date('Y-m-d'));
                                break;
                        case 'atom':
                                $rep = $this->getResponse('xml');
                                $rep->addHttpHeader('Content-Type', 'application/atom+xml;profile=opds-catalog;kind=navigation', true);
		                $rep->contentTpl = 'index.default.atom';
                                $rep->content->assign('lang', $this->lang);
                                $dt = new jDateTime();
                                $dt->now();
                                $now = $dt->toString(jDateTime::ISO8601_FORMAT);
                                $rep->content->assign('now', $now);
                                break;
                        case 'html':
                        case '':
                                $rep = $this->getResponse('html');
                                $rep->addLink(jUrl::get('default:index', array('lang' => $this->lang, 'format' => 'atom')), 'alternate', 'application/atom+xml;profile=opds-catalog;kind=navigation', jLocale::get('wsexport.opds_catalog'));
                                $rep->htmlTagAttributes['prefix'] = 'og: http://ogp.me/ns#';
                                $rep->addHeadContent('<meta property="og:title" content="' . jLocale::get('wsexport.site.long_name') . '" />');
                                $rep->addHeadContent('<meta property="og:type" content="website" />');
                                $rep->addHeadContent('<meta property="og:url" content="' . jUrl::getFull('#') . '" />');
                                //$rep->addHeadContent('<meta property="og:image" content="" />');
                                //$rep->addHeadContent('<meta property="og:locale" content="' . '' . '" />'); TODO
                                $rep->addHeadContent('<meta property="og:site_name" content="' . jLocale::get('wsexport.site.short_name') . '" />');
                                $rep->addHeadContent('<meta property="og:description" content="' . jLocale::get('wsexport.site.description') . '" />');
                                $tpl = new jTpl();
                                $tpl->assign('lang', $this->lang);
        		        $rep->body->assign('MAIN', $tpl->fetch('index.default.html'));
                                break;
                        default:
                                return $this->_error(404);
                }
		return $rep;
        }
}
