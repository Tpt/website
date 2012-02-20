{include 'init.atom'}
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
