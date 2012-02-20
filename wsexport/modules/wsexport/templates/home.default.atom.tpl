{include 'init.atom'}
        <entry>
                <title>{@wsexport.popular_publications@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="http://opds-spec.org/sort/popular" href="{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'downloads', 'asc' => 'false')}" />
                <updated>{$now}</updated>
                <id xsi:type="dcterms:URI">{jfullurl2 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'downloads', 'asc' => 'false')}</id>
                <content type="text">{@wsexport.popular_publications.desc@}</content>
        </entry>
        <entry>
                <title>{@wsexport.new_publications@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="http://opds-spec.org/sort/new" href="{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'created', 'asc' => 'false')}" />
                <updated>{$now}</updated>
                <id xsi:type="dcterms:URI">{jfullurl2 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'created', 'asc' => 'false')}</id>
                <content type="text">{@wsexport.new_publications.desc@}</content>
        </entry>
        <entry>
                <title>{@wsexport.unpopular_publications@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" rel="subsection" href="{jurl 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'downloads', 'asc' => 'true')}" />
                <updated>{$now}</updated>
                <id xsi:type="dcterms:URI">{jfullurl2 'book:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'downloads', 'asc' => 'true')}</id>
                <content type="text">{@wsexport.unpopular_publications.desc@}</content>
        </entry>
        <entry>
                <title>{@wsexport.all_authors@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="subsection" href="{jurl 'person:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'name', 'asc' => 'true')}" />
                <updated>{$now}</updated>
                <id xsi:type="dcterms:URI">{jfullurl2 'person:index', array('lang' => $lang, 'format' => 'atom', 'order' => 'name', 'asc' => 'true')}</id>
                <content type="text">{@wsexport.all_authors@}</content>
        </entry>
        <entry>
                <title>{@wsexport.authors_in_chronological_order@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="subsection" href="{jurl 'person:index', array('lang' => $lang, 'format' => 'html', 'order' => 'birthDate', 'asc' => 'true')}" />
                <updated>{$now}</updated>
                <id xsi:type="dcterms:URI">{jfullurl2 'person:index', array('lang' => $lang, 'format' => 'html', 'order' => 'birthDate', 'asc' => 'true')}</id>
                <content type="text">{@wsexport.authors_in_chronological_order@}</content>
        </entry>
        <entry>
                <title>{@wsexport.all_authors@}</title>
                <link type="application/atom+xml;profile=opds-catalog;kind=navigation" rel="subsection" href="{jurl 'person:index', array('lang' => $lang, 'format' => 'html', 'order' => 'birthDate', 'asc' => 'false')}" />
                <updated>{$now}</updated>
                <id xsi:type="dcterms:URI">{jfullurl2 'person:index', array('lang' => $lang, 'format' => 'html', 'order' => 'birthDate', 'asc' => 'false')}</id>
                <content type="text">{@wsexport.authors_in_reverse_chronological_order@}</content>
        </entry>
</feed>
