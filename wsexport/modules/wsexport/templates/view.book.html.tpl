<article class="block" itemscope="itemscope" itemtype="http://schema.org/Book">
	<h1 itemprop="name">{$book->name}</h1>
        {if $book->author}<h2>{@wsexport.by@} <span itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person"><a itemprop="url" href="{jurl 'book:index', array('lang' => $book->lang, 'author' => $book->author)}">{$book->author}</a></span></h2>{/if}
<div class="blockcontent">
<div id="picture"></div>
<table id="info">
	<caption>Description</caption>
	<tr>
		<th>{@wsexport.language@}</th>{assign $lang = $book->lang}
		<td><meta content="{$book->lang}" itemprop="inLanguage" /><a href="{jurl 'book:index', array('lang' => $book->lang)}">{@wsexport.lang.$lang@}</a></td>
	</tr>
	{if $book->year}
	<tr>
		<th>{@wsexport.published_in@}</th>
		<td>{if is_numeric($book->year)}<time itemprop="datePublished" datetime="{$book->year}">{$book->year}</time>{else}{$book->year}{/if} {if $book->place}{@wsexport.in@} {$book->place}{/if}</td>
	</tr>
	{/if}
        {if count($book->categories) != 0}
	<tr>
		<th>{@wsexport.categories@}</th>
		<td>{foreach $book->categories as $categorie}{$categorie}, {/foreach}</td>
	</tr>
	{/if}
	{if $book->translator}
	<tr>
		<th>{@wsexport.translator@}</th>
		<td itemprop="translator" itemscope="itemscope" itemtype="http://schema.org/Person"><a itemprop="url" href="{jurl 'book:index', array('lang' => $book->lang, 'translator' => $book->translator)}">{$book->translator}</a></td>
	</tr>
	{/if}
	{if $book->illustrator}
	<tr>
		<th>{@wsexport.illustrator@}</th>
		<td itemprop="illustrator" itemscope="itemscope" itemtype="http://schema.org/Person"><a itemprop="url" href="{jurl 'book:index', array('lang' => $book->lang, 'illustrator' => $book->illustrator)}">{$book->illustrator}</a></td>
	</tr>
	{/if}
</table>
<aside id="download-links">
        <h5>{@wsexport.download@}</h5>
        <ul>
                <li><a href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'epub', 'title' => $book->title)}">EPUB</a></li>
                <li><a href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'xhtml', 'title' => $book->title)}">XHTML</a></li>
                <li><a href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'odt', 'title' => $book->title)}">ODT</a></li>
        </ul>
</aside>
</div>
</article>
