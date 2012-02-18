<article itemscope="itemscope" itemtype="http://schema.org/Person">
        <h1 itemprop="name">{$person->name|eschtml}</h1>
        {if $person->birthDate || $person->deathDate}<h2 class="date">({if is_numeric($person->birthDate)}<time itemprop="birthDate" datetime="{$person->birthDate}">{$person->birthDate}</time>{else}{$person->birthDate|eschtml}{/if} - {if is_numeric($person->deathDate)}<time itemprop="deathDate" datetime="{$person->deathDate}">{$person->deathDate}</time>{else}{$person->deathDate|eschtml}{/if})</h2>{/if}
	<hr/>
        {if $person->title}<div class="row">
                <div class="span3">
                        {if $person->imageUrl != ''}<img itemprop="image" src="{$person->imageUrl}" alt="{$person->name|eschtml}" title="{$person->name|eschtml}" width="200" />{/if}
                </div>
                <div itemprop="description" class="span6"><h5>{@wsexport.about@}</h5>
		  {$person->description|eschtml}
		</div>
                <aside class="span3">
                        <h5>{@wsexport.links@}</h5>
                        <ul>
                                {if $person->title}<li><a href="http://{$person->lang}.wikisource.org/wiki/Auteur:{$person->title|eschtml}">Wikisource</a></li>{/if}
                                {if $person->wikipedia}<li><a href="http://{$person->lang}.wikipedia.org/wiki/{$person->wikipedia|eschtml}">Wikipedia</a></li>{/if}
                                {if $person->wikiquote}<li><a href="http://{$person->lang}.wikiquote.org/wiki/{$person->wikiquote|eschtml}">Wikiquote</a></li>{/if}
                                {if $person->commons}<li><a href="http://commons.wikimedia.org/wiki/{$person->commons|eschtml}">Wikimedia commons</a></li>{/if}
                        </ul>
                </aside>
        </div>{/if}
        {include 'index.book.html'}
</article>
