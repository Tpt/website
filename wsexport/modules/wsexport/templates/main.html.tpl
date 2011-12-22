{meta_html jquery}
{meta_html jquery_ui 'theme'}
{meta_html css $j_basepath.'main.css'}
{meta_html jquery_ui 'components', array('widget', 'position', 'autocomplete')}
{literal}
<script type="text/javascript">
// <![CDATA[
        $(function() {
                $("#search-box").autocomplete({
                        source: function(request, response) {
			        $.ajax({
					url: "{/literal}{jurl 'wsexport~book:search'}{literal}",
					dataType: "json",
					data: {
						format: "opensearchsuggestions",
						limit: 10,
						q: request.term
					},
					success: function(data) {
						response( $.map( data[1], function(item, i) {
							return {
								label: data[2][i],
								value: data[1][i]
							}
						}));
					}
				});
			},
			minLength: 2,
			select: function(event, ui) { //TODO Redirect to the content page
				this.value = ui.item.value;
				$("#quick-search").submit();
			}
		});
    });
// ]]>
</script>
{/literal}
<div id="page" role="document">
	<header id="header" role="banner">
		<hgroup id="title"><h1>WsExport,</h1> <h2>le catalogue des livres de <a href="http://fr.wikisource.org">Wikisource</a>, la bibliothèque libre</h2></hgroup>
		<form action="{jurl 'wsexport~book:search'}" method="GET" id="quick-search" role="search"><input type="search" name="q" id="search-box" /><input type="submit" value="{@wsexport.search@}" /></form>
	</header>
	<div id="main">
		<nav id="nav" role="navigation">
			<h5>{@wsexport.navigation@}</h5>
			<ul>
				<li><a href="{jurl ''}" rel="home">{@wsexport.mainpage@}</a></li>
				<li><a href="{jurl 'book:index'}" rel="directory">{@wsexport.all_books@}</a></li>
				<li><a href="{jurl 'book:index', array('order' => 'downloads', 'asc' => 'false')}" rel="directory">{@wsexport.popular_publications@}</a></li>
				<li><a href="{jurl 'book:index', array('order' => 'created', 'asc' => 'false')}" rel="directory">{@wsexport.new_publications@}</a></li>
				<li><a href="{jurl 'book:index', array('order' => 'downloads', 'asc' => 'true')}" rel="directory">{@wsexport.unpopular_publications@}</a></li>
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
