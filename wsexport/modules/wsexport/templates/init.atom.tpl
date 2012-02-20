<feed xml:lang="{$lang}" xmlns="http://www.w3.org/2005/Atom" xmlns:opds="http://opds-spec.org/2010/catalog" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/" xmlns:app="http://www.w3.org/2007/app" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:thr="http://purl.org/syndication/thread/1.0">
        <id xsi:type="dcterms:URI">{jfullurl2 '#', $params}</id>
        <title>{$title|escxml}</title>
        <updated>{$now}</updated>
        <icon></icon>
        <author>
                <name></name>
                <uri>{jurl ''}</uri>
        </author>
        <link type="text/html" rel="alternate" href="{jurl '#', array('lang' => $lang, 'format' => 'html')}" />
        <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="self" href="{jfullurl2 'default:home', array('lang' => $lang, 'format' => 'atom')}" />
        <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="start" title="{@wsexport.mainpage@}" href="{jurl 'default:home', array('lang' => $lang, 'format' => 'atom')}" />
        <link type="application/opensearchdescription+xml" rel="search" title="{@wsexport.search@}" href="{jurl 'default:index', array('format' => 'opensearchdescription')}" />
        {foreach $languages as $language}
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="http://opds-spec.org/facet" title="{$language}" opds:facetGroup="Langue" {if $language == $lang}opds:activeFacet="true"{/if} href="{jurl '#', array_merge($params, array('lang' => $language))}" />
        {/foreach}
