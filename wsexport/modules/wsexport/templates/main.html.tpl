{meta_html css 'http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css'}
{meta_html jquery_ui 'theme'}
{meta_html css $j_basepath.'main.css'}
{meta_html jquery}
{meta_html js $j_basepath.'main.js'}
{meta_html jquery_ui 'components', array('widget', 'position', 'autocomplete')}
{literal}
<script type="text/javascript">
// <![CDATA[
        var wsexport = {
                url: {
                        booksearch: "{/literal}{jurl 'book:search', array('lang' => $lang)}{literal}"
                }
        };
// ]]>
</script>
{/literal}
<div role="document">
        <header class="topbar" role="banner">
                <div class="topbar-inner">
                        <div class="container-fluid">
                                <a class="brand" href="{jurl 'default:home', array('lang' => $lang)}">{@wsexport.site.short_name@}</a>
                                <ul class="nav">
                                        <li {if $action == 'home'}class="active"{/if}><a href="{jurl 'default:home', array('lang' => $lang)}" rel="home">{@wsexport.home@}</a></li>
                                        <li {if $action == 'about'}class="active"{/if}><a href="{jurl 'default:about', array('lang' => $lang)}">{@wsexport.about@}</a></li>
                                </ul>
                                <form action="{jurl 'wsexport~book:search', array('lang' => $lang)}" method="GET" id="quick-search" role="search" class="pull-right"><input type="search" name="q" id="search-box" placeholder="{@wsexport.search@}" /><input type="submit" value="{@wsexport.search@}" /></form>
                        </div>
                </div>
        </header>
        <div class="container-fluid">
                <div class="sidebar">
                        <nav class="well" role="navigation">
                                <h5>{@wsexport.navigation@}</h5>
                                <ul>
                                        <li><a href="{jurl 'book:index', array('lang' => $lang, 'order' => 'name', 'asc' => 'true')}" rel="directory">{@wsexport.all_books@}</a></li>
                                        <li><a href="{jurl 'book:index', array('lang' => $lang, 'order' => 'downloads', 'asc' => 'false')}" rel="directory">{@wsexport.popular_publications@}</a></li>
                                        <li><a href="{jurl 'book:index', array('lang' => $lang, 'order' => 'created', 'asc' => 'false')}" rel="directory">{@wsexport.new_publications@}</a></li>
                                        <li><a href="{jurl 'book:index', array('lang' => $lang, 'order' => 'downloads', 'asc' => 'true')}" rel="directory">{@wsexport.unpopular_publications@}</a></li>
                                        <li><a href="{jurl 'book:random', array('lang' => $lang)}">{@wsexport.random_book@}</a></li>
                                </ul>
                        </nav>
                </div>
                <div class="content" role="main">
                        {jmessage}
                        {$MAIN}

                        <footer class="footer">
                                <div class="pull-right"><img src="{$j_basepath}/jelix/design/images/jelix_powered.png" alt="jelix powered" /></div>
                        </footer>
                </div>
        </div>
</div>
