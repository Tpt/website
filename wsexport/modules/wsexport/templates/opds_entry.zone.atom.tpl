{if $main}
<entry xml:lang="{$book->lang}" xmlns="http://www.w3.org/2005/Atom" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/">
        <link type="application/atom+xml;type=entry;profile=opds-catalog" rel="self" href="{jfullurl "book:view", array('lang' => $book->lang, 'title' => $book->title, 'format' => 'atom')}" />
{else}
<entry xml:lang="{$book->lang}">
{/if}
        <id xsi:type="dcterms:URI">{jfullurl "book:view", array('lang' => $book->lang, 'title' => $book->title)}</id>
        <title>{$book->name|escxml}</title>
        {if $book->author}
        <author>
                <name>{$book->author|escxml}</name>
                <uri>{jurl 'book:index', array('author' => $book->author)}</uri>
        </author>
        {/if}
        <published>{$book->created|jdatetime:'db_date':'iso8601'}</published>
        <updated>{$book->updated|jdatetime:'db_date':'iso8601'}</updated>
        <rights>CC-BY-SA 3.0 or GNU FDL</rights>
        <summary>{$book->name|escxml}</summary>
        <dc:identifier xsi:type="dcterms:URI">urn:uuid:{$book->uuid|escxml}</dc:identifier>
        <dc:language xsi:type="dcterms:RFC4646">{$book->lang|escxml}</dc:language>
        <dc:source xsi:type="dcterms:URI">http://{$book->lang|escurl}.wikisource.org/wiki/{$book->title|escurl}</dc:source>
        {if $book->year}<dcterms:issued {if is_numeric($book->year)} xsi:type="dcterms:W3CDTF"{/if} >{$book->year|escxml}</dcterms:issued>{/if}
        {if $book->publisher}<dc:publisher>{$book->publisher|escxml}</dc:publisher>{/if}
        {if $book->translator}<dc:contributor>{$book->translator|escxml}</dc:contributor>{/if}
        {if $book->illustrator}<dc:contributor>{$book->illustrator|escxml}</dc:contributor>{/if}
        {foreach $book->categories as $categorie}
        <category label="{$categorie|escxml}" term="{$categorie|escxml}" />
        {/foreach}
        <link type="text/html" rel="alternate" href="{jurl "book:view", array('lang' => $book->lang, 'title' => $book->title, 'format' => 'html')}" />
        {if $book->coverUrl}<link rel="http://opds-spec.org/image" href="{$book->coverUrl}" type="image/jpeg" />{/if}
        <link type="application/epub+zip" rel="http://opds-spec.org/acquisition" href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'epub', 'title' => $book->title)}" />
        <link type="application/xhtml+xml" rel="http://opds-spec.org/acquisition" href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'xhtml', 'title' => $book->title)}" />
        <link type="application/vnd.oasis.opendocument.text" rel="http://opds-spec.org/acquisition" href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'odt', 'title' => $book->title)}" />
{if $main}
        <link type="application/atom+xml;profile=opds-catalog;kind=acquisition" title="{@wsexport.form_the_same_author@}" rel="related" href="{jurl 'book:index', array('lang' => $book->lang, 'format' => 'atom', 'author' => $book->author)}" />
{else}
        <link type="application/atom+xml;type=entry;profile=opds-catalog" title="{@wsexport.full_entry@}" rel="alternate" href="{jurl "book:view", array('lang' => $book->lang, 'title' => $book->title, 'format' => 'atom')}" />
{/if}
</entry>
