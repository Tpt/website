<div class="booklist">
{foreach $people as $person}
<article itemscope="itemscope" itemtype="http://schema.org/Person" class="row">
  <div class="span4">
    {if $person->imageUrl != ''}
    <img itemprop="image" src="{$person->imageUrl}" alt="{$person->name|eschtml}" title="{$person->name|eschtml}" height="100" class="cover" />
    {else}
    <p class="cover"><span>?</span></p>
    {/if}
  </div>
  <div class="span8">
    <h5><a itemprop="url" href="{jurl 'person:view', array('lang' => $person->lang, 'title' => $person->title)}">{$person->name|eschtml}</a></h5>
    {if $person->birthDate || $person->deathDate}<h6 class="date">({if is_numeric($person->birthDate)}<time itemprop="birthDate" datetime="{$person->birthDate}">{$person->birthDate}</time>{else}{$person->birthDate|eschtml}{/if} - {if is_numeric($person->deathDate)}<time itemprop="deathDate" datetime="{$person->deathDate}">{$person->deathDate}</time>{else}{$person->deathDate|eschtml}{/if})</h6>{/if}
  </div>
</article>
{/foreach}
</div>
{if $count > $itemPerPage}
<div class="row navigation">
  <p class="span8 offset4">{pagelinks 'person:index', $params, $count, $offset, $itemPerPage, 'offset'}</p>
</div>
{/if}
