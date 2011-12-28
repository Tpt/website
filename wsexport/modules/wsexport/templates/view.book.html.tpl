<article itemscope="itemscope" itemtype="http://schema.org/Book">
        <h1 itemprop="name">{$book->name|eschtml}</h1>
        {if $book->author}<h2>{@wsexport.by@} <span itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person"><a itemprop="url" href="{jurl 'person:view', array('lang' => $book->lang, 'title' => $book->author)}">{$book->author|eschtml}</a></span></h2>{/if}
        <div class="row">
                <div class="span4">
                        {if $book->coverUrl != ''}<img itemprop="image" src="{$book->coverUrl}" alt="{@wsexport.cover@}" title="{@wsexport.cover@}" width="200" />{/if}
                </div>
                <div class="span8">
                        <table id="info">
                                <caption>Description</caption>
                                <tr>
                                        <th>{@wsexport.language@}</th>{assign $lang = $book->lang}
                                        <td><meta content="{$book->lang}" itemprop="inLanguage" /><a href="{jurl 'book:index', array('lang' => $book->lang)}">{@wsexport.lang.$lang@}</a></td>
                                </tr>
                                {if $book->year}
                                <tr>
                                        <th>{@wsexport.published_in@}</th>
                                        <td>{if is_numeric($book->year)}<time itemprop="datePublished" datetime="{$book->year}">{$book->year}</time>{else}{$book->year|eschtml}{/if} {if $book->place}{@wsexport.in@} {$book->place|eschtml}{/if}</td>
                                </tr>
                                {/if}
                                {if count($book->categories) != 0}
                                <tr>
                                        <th>{@wsexport.categories@}</th>
                                        <td>{foreach $book->categories as $categorie}{$categorie|eschtml}, {/foreach}</td>
                                </tr>
                                {/if}
                                {if $book->translator}
                                <tr>
                                        <th>{@wsexport.translator@}</th>
                                        <td itemprop="translator" itemscope="itemscope" itemtype="http://schema.org/Person"><a itemprop="url" href="{jurl 'person:view', array('lang' => $book->lang, 'title' => $book->translator)}">{$book->translator|eschtml}</a></td>
                                </tr>
                                {/if}
                                {if $book->illustrator}
                                <tr>
                                        <th>{@wsexport.illustrator@}</th>
                                        <td itemprop="illustrator" itemscope="itemscope" itemtype="http://schema.org/Person"><a itemprop="url" href="{jurl 'person:view', array('lang' => $book->lang, 'title' => $book->illustrator)}">{$book->illustrator|eschtml}</a></td>
                                </tr>
                                {/if}
                        </table>
                </div>
                <aside class="span4">
                        <h5>{@wsexport.download@}</h5>
                        <ul>
                                <li><a href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'epub', 'title' => $book->title)}">EPUB</a></li>
                                <li><a href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'xhtml', 'title' => $book->title)}">XHTML</a></li>
                                <li><a href="{jurl 'book:get', array('lang' => $book->lang, 'format' => 'odt', 'title' => $book->title)}">ODT</a></li>
                        </ul>
                        <h5>{@wsexport.source@}</h5>
                        <ul>
                                <li><a href="http://{$book->lang}.wikisource.org/wiki/{$book->title|escurl}">Wikisource</a></li>
                        </ul>
                </aside>
        </div>
</article>
