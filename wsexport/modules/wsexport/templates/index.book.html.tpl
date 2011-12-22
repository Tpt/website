<div class="block">
{foreach $books as $book}
<article itemscope="itemscope" itemtype="http://schema.org/Book">
	<h3><a itemprop="url" href="{jurl 'book:view', array('lang' => $book->lang, 'title' => $book->title)}">{$book->name}</a></h3>
        {if $book->author}<h4>{@wsexport.by@} <span itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person"><a  itemprop="url" href="{jurl 'book:index', array('lang' => $book->lang, 'author' => $book->author)}">{$book->author}</a></span></h4>{/if}
        <div><a href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'epub', 'title' => $book->title)}">{@wsexport.download@}</a></div>
</article>
{/foreach}
<p>{pagelinks 'book:index', $params, $count, $offset, $itemPerPage, 'offset'}</p>
</div>
