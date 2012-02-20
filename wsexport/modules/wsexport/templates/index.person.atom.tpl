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
        {foreach $people as $person}
        <entry>
                <title>{$person->name}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="subsection" href="{jurl 'person:view', array('lang' => $person->lang, 'format' => 'atom', 'title' => $person->title)}" />
                <link type="application/xhtml+xml" rel="alternate" href="{jurl 'person:view', array('lang' => $person->lang, 'format' => 'html', 'title' => $person->title)}" />
                <updated>{$now}</updated>
                <id xsi:type="dcterms:URI">{jfullurl2 'person:view', array('lang' => $person->lang, 'format' => 'atom', 'title' => $person->title)}</id>
                {if $person->imageUrl != ''}<link rel="http://opds-spec.org/image/thumbnail" href="{$person->imageUrl}" type="{$person->imageType}" />{/if}
        </entry>
        {/foreach}
</feed>
