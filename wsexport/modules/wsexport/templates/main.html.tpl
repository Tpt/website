{meta_html jquery}
{meta_html jquery_ui 'theme'}
{meta_html css $j_basepath.'main.css'}
{meta_html js $j_basepath.'main.js'}
{meta_html jquery_ui 'components', array('widget', 'position', 'autocomplete')}
{literal}
<script type="text/javascript">
// <![CDATA[
        var wsexport = {
                url: {
                        booksearch: "{/literal}{jurl 'book:search'}{literal}"
                }
        };
// ]]>
</script>
{/literal}
<div id="page" role="document">
	<header id="header" role="banner">
		<hgroup id="title"><h1>{@wsexport.site.short_name@},</h1> <h2>le catalogue des livres de <a href="http://fr.wikisource.org">Wikisource</a>, la bibliothèque libre</h2></hgroup>
		<form action="{jurl 'wsexport~book:search'}" method="GET" id="quick-search" role="search"><input type="search" name="q" id="search-box" /><input type="submit" value="{@wsexport.search@}" /></form>
	</header>
	<div id="main">
		<nav id="nav" role="navigation">
			<h5>{@wsexport.navigation@}</h5>
			<ul>
				<li><a href="{jurl ''}" rel="home">{@wsexport.mainpage@}</a></li>
				<li><a href="{jurl 'book:index', array('order' => 'name', 'asc' => 'true')}" rel="directory">{@wsexport.all_books@}</a></li>
				<li><a href="{jurl 'book:index', array('order' => 'downloads', 'asc' => 'false')}" rel="directory">{@wsexport.popular_publications@}</a></li>
				<li><a href="{jurl 'book:index', array('order' => 'created', 'asc' => 'false')}" rel="directory">{@wsexport.new_publications@}</a></li>
				<li><a href="{jurl 'book:index', array('order' => 'downloads', 'asc' => 'true')}" rel="directory">{@wsexport.unpopular_publications@}</a></li>
				<li><a href="{jurl 'book:random'}">{@wsexport.random_book@}</a></li>
			</ul>
		</nav>
		{jmessage}
		<div id="body" role="main">
			{$MAIN}
		</div>
	</div>
	<footer id="footer">
		<img src="{$j_basepath}/jelix/design/images/jelix_powered.png" alt="jelix powered" />
	</footer>
</div>
