{meta_html css $j_basepath.'bootstrap/css/bootstrap.min.css'}
{meta_html css $j_basepath.'bootstrap/css/bootstrap-responsive.min.css'}
{meta_html css $j_basepath.'main.css'}
{meta_html js $j_basepath.'jquery.min.js'}
{meta_html js $j_basepath.'bootstrap/js/bootstrap.min.js'}
{meta_html js $j_basepath.'main.js'}
{literal}
<script type="text/javascript">
// <![CDATA[
    var wsexport = {
        url: {
            booksearch: "{/literal}{jurl 'book:search', array('lang' => $lang, 'format' => 'opensearchsuggestions')}{literal}"
        }
    };
// ]]>
</script>
{/literal}
<div role="document">
    <header class="navbar navbar-fixed-top" role="banner">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="{jurl 'default:home', array('lang' => $lang, 'format' => 'html')}">{@wsexport.site.short_name@}</a>
                <div class="nav-collapse">
                    <ul class="nav">
                        <li {if $action == 'home'}class="active"{/if}><a href="{jurl 'default:home', array('lang' => $lang, 'format' => 'html')}" rel="home">{@wsexport.home@}</a></li>
                        <li {if $action == 'about'}class="active"{/if}><a href="{jurl 'default:about', array('lang' => $lang, 'format' => 'html')}">{@wsexport.about@}</a></li>
                    </ul>
                </div>
                <form action="{jurl 'wsexport~book:search', array('lang' => $lang, 'format' => 'html')}" method="GET" id="quick-search" role="search" class="navbar-search pull-right"><input type="search" name="q" id="search-box" placeholder="{@wsexport.search@}" class="search-query" /></form>
            </div>
        </div>
    </header>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3">
                <nav class="well sidebar-nav" role="navigation">
                    <ul class="nav nav-list">
                        <li class="nav-header">{@wsexport.books@}</li>
                        <li><a href="{jurl 'book:index', array('lang' => $lang, 'format' => 'html', 'order' => 'name', 'asc' => 'true')}" rel="directory">{@wsexport.all_books@}</a></li>
                        <li><a href="{jurl 'book:index', array('lang' => $lang, 'format' => 'html', 'order' => 'downloads', 'asc' => 'false')}" rel="directory">{@wsexport.popular_publications@}</a></li>
                        <li><a href="{jurl 'book:index', array('lang' => $lang, 'format' => 'html', 'order' => 'created', 'asc' => 'false')}" rel="directory">{@wsexport.new_publications@}</a></li>
                        <li><a href="{jurl 'book:index', array('lang' => $lang, 'format' => 'html', 'order' => 'downloads', 'asc' => 'true')}" rel="directory">{@wsexport.unpopular_publications@}</a></li>
                        <li><a href="{jurl 'book:random', array('lang' => $lang, 'format' => 'html')}">{@wsexport.random_book@}</a></li>
                        <li class="nav-header">{@wsexport.authors@}</li>
                        <li><a href="{jurl 'person:index', array('lang' => $lang, 'format' => 'html', 'order' => 'name', 'asc' => 'true')}" rel="directory">{@wsexport.all_authors@}</a></li>
                        <li><a href="{jurl 'person:index', array('lang' => $lang, 'format' => 'html', 'order' => 'birthDate', 'asc' => 'true')}" rel="directory">{@wsexport.authors_in_chronological_order@}</a></li>
                        <li><a href="{jurl 'person:index', array('lang' => $lang, 'format' => 'html', 'order' => 'birthDate', 'asc' => 'false')}" rel="directory">{@wsexport.authors_in_reverse_chronological_order@}</a></li>
                        <li><a href="{jurl 'person:random', array('lang' => $lang, 'format' => 'html')}">{@wsexport.random_author@}</a></li>
                        <li class="nav-header">{@wsexport.languages@}</li>
                        {foreach $languages as $language}
                            <li {if $language == $lang}class="active"{/if}><a href="{jurl 'default:home', array('lang' => $language, 'format' => 'html')}">{jlocale 'wsexport.lang.'.$language}</a></li>
                        {/foreach}
                    </ul>
                </nav>
            </div>
            <div class="span9" role="main">
                {jmessage}
                {$MAIN}
            </div>
        </div>
        <footer class="footer">
            <div class="pull-right"><img src="{$j_basepath}/jelix/design/images/jelix_powered.png" alt="jelix powered" /></div>
        </footer>
    </div>
</div>
