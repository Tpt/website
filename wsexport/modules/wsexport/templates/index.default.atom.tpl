<feed xml:lang="{$lang}" xmlns="http://www.w3.org/2005/Atom" xmlns:opds="http://opds-spec.org/2010/catalog" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/" xmlns:app="http://www.w3.org/2007/app" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:thr="http://purl.org/syndication/thread/1.0">
        <id>{jurl '', array('lang' => $lang, 'format' => 'atom')}</id>
        <title>{@wsexport.mainpage@}</title>
        <updated>{$now}</updated>
        <icon></icon>
        <author>
                <name></name>
                <uri>{jurl ''}</uri>
        </author>
        <link type="text/html" rel="alternate" href="{jurl '', array('lang' => $lang, 'format' => 'html')}" />
        <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="self" href="{jurl '', array('lang' => $lang, 'format' => 'atom')}" />
        <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="start" title="{@wsexport.mainpage@}" href="{jurl '', array('lang' => $lang, 'format' => 'atom')}" />
        <link type="application/opensearchdescription+xml" rel="search" title="{@wsexport.search@}" href="{jurl 'book:search', array('format' => 'opensearchdescription')}" />
        {* {foreach $langs as $lang} opds:activeFacet="true"
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="http://opds-spec.org/facet" title="{$lang}" opds:facetGroup="Langue" href="{jurl '', array('lang' => $lang, 'format' => 'atom')}" />
        {/foreach} *}
        <entry>
                <title>{@wsexport.popular_publications@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="http://opds-spec.org/sort/popular" href="{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'downloads', 'asc' => 'false')}" />
                <updated>{$now}</updated>
                <id>{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'downloads', 'asc' => 'false')}</id>
                <content type="text">{@wsexport.popular_publications.desc@}</content>
        </entry>
        <entry>
                <title>{@wsexport.new_publications@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="http://opds-spec.org/sort/new" href="{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'created', 'asc' => 'false')}" />
                <updated>{$now}</updated>
                <id>{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'created', 'asc' => 'false')}</id>
                <content type="text">{@wsexport.new_publications.desc@}</content>
        </entry>
        <entry>
                <title>{@wsexport.unpopular_publications@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="http://opds-spec.org/sort/unpopular" href="{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'downloads', 'asc' => 'true')}" />
                <updated>{$now}</updated>
                <id>{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'downloads', 'asc' => 'true')}</id>
                <content type="text">{@wsexport.unpopular_publications.desc@}</content>
        </entry>
</feed>
