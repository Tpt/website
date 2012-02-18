<div class="booklist">
{foreach $books as $book}
<article itemscope="itemscope" itemtype="http://schema.org/Book" class="row">
  <div class="span3">
    {if $book->coverUrl != ''}
    <img itemprop="image" src="{$book->iconUrl}" alt="{@wsexport.cover@}" title="{@wsexport.cover@}" class="cover" height="100" />
    {else}
    <p class="cover"><span>?</span></p>
    {/if}
  </div>
  <div class="span6">
    <h4><a itemprop="url" href="{jurl 'book:view', array('lang' => $lang, 'format' => 'html', 'title' => $book->title)}">{$book->name|eschtml}{if $book->volume != ''}, {$book->volume|escxml}{/if}</a></h4>
    {if $book->author}
    <h6>
      {@wsexport.by@}
      <span itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">
	<a  itemprop="url" href="{jurl 'person:view', array('lang' => $lang, 'format' => 'html', 'title' => $book->author)}">{$book->author|eschtml}</a>
      </span>
    </h6>
    {/if}
  </div>
  <div class="span3">
    <p><a href="{jurl 'book:get', array('lang' => $lang, 'format' => 'epub', 'title' => $book->title)}" class="btn download">{@wsexport.download@}</a></p>
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
