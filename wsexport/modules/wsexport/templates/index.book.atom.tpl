<feed xml:lang="{$lang}" xmlns="http://www.w3.org/2005/Atom" xmlns:opds="http://opds-spec.org/2010/catalog" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/" xmlns:app="http://www.w3.org/2007/app" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:thr="http://purl.org/syndication/thread/1.0">
        <id>{jfullurl2 '#', $params}</id>
        <title>{$title|escxml}</title>
        <updated>{$now}</updated>
        {if isset($icon)}<icon>{$icon|escxml}</icon>{/if}
        <author>
                <name></name>
                <uri>{jurl ''}</uri>
        </author>
        <link type="text/html" rel="alternate" href="{jurl '#', array_merge($params, array('format' => 'html'))}" />
        <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="self" href="{jfullurl2 '#', $params}" />
        <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="start" title="{@wsexport.mainpage@}" href="{jurl '', array('lang' => $lang, 'format' => 'atom')}" />
        <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="up" title="{@wsexport.mainpage@}" href="{jurl '', array('lang' => $lang, 'format' => 'atom')}" />
        <link type="application/opensearchdescription+xml" rel="search" title="{@wsexport.search@}" href="{jurl 'book:search', array('format' => 'opensearchdescription')}" />
        <opensearch:totalResults>{$count}</opensearch:totalResults>
        <opensearch:startIndex>{$offset}</opensearch:startIndex>
        <opensearch:itemsPerPage>{$itemPerPage}</opensearch:itemsPerPage>
        {if isset($query)}<opensearch:Query role="request" searchTerms="{$query}" />{/if}
        {if $offset + $itemPerPage < $count}
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="next" title="{@wsexport.pagelinks.next@}" href="{jurl '#', array_merge($params, array('offset' => $offset + $itemPerPage))}" />
        {/if}
        {* {foreach $langs as $lang}
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="http://opds-spec.org/facet" title="{$lang}" opds:facetGroup="Langue" href="' . $this->getLink($langlink['*'], $langlink['lang'])  . '" />
        {/foreach} *}
        {foreach $books as $book}
                {zone 'opdsEntry', array('main' => false, 'book' => $book)}
        {/foreach}
</feed>
