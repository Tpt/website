<div class="booklist">
{foreach $books as $book}
<article itemscope="itemscope" itemtype="http://schema.org/Book" class="row">
  <div class="span4">
    {if $book->coverUrl != ''}
    <img itemprop="image" src="{$book->iconUrl}" alt="{@wsexport.cover@}" title="{@wsexport.cover@}" class="cover" height="100" />
    {else}
    <p class="cover"><span>?</span></p>
    {/if}
  </div>
  <div class="span8">
    <h5><a itemprop="url" href="{jurl 'book:view', array('lang' => $book->lang, 'title' => $book->title)}">{$book->name|eschtml}</a></h5>
    {if $book->author}
    <h6>
      {@wsexport.by@}
      <span itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">
	<a  itemprop="url" href="{jurl 'book:index', array('lang' => $book->lang, 'author' => $book->author)}">{$book->author|eschtml}</a>
      </span>
    </h6>
    {/if}
  </div>
  <div class="span4">
    <p><a href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'epub', 'title' => $book->title)}" class="btn download">{@wsexport.download@}</a></p>
    <p>{jlocale "wsexport.downloads", array($book->downloads)}</p>
  </div>
</article>
{/foreach}
</div>
{if $count > $itemPerPage}
<div class="row navigation">
  <p class="span8 offset4">{pagelinks 'book:index', $params, $count, $offset, $itemPerPage, 'offset'}</p>
</div>
{/if}
