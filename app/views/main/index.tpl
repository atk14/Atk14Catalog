<article>

	{if $page}

		<header>
			<h1>{$page->getTitle()}</h1>
			<div class="teaser">
			{!$page->getTeaser()|markdown}
			</div>
		</header>
		
		{!$page->getBody()|markdown}
			
	{else}

		<header>
			<h1>{$page_title}</h1>
		</header>

	{/if}

	<section class="border-top-0">
		<h3>{t}The Catalog contains mainly{/t}</h3>
		<ul>
			<li>{a controller="categories"}{t}List of categories{/t}{/a}</li>
			<li>{a controller="brands"}{t}List of brands{/t}{/a}</li>
			<li>{a controller="collections"}{t}List of collections{/t}{/a}</li>
			<li>{a controller="stores"}{t}List of stores{/t}{/a}</li>
			<li>{a action="pages/detail" id=1}{t}Pages with a hierarchical structure{/t}{/a}</li>
			<li>{a action="main/contact"}{t}Contact page with fast contact form{/t}{/a}</li>
			<li>{a action="articles/index"}{t}Articles section{/t}{/a}</li>
			<li>{a action="users/create_new"}{t}User registration{/t}{/a} ({t}with strong blowfish passwords hashing{/t})</li>
			<li>{a namespace="admin"}{t}Basic administration{/t}{/a}</li>
			<li>{a namespace="api"}{t}RESTful API{/t}{/a}</li>
			<li>{t}Sitemap{/t} ({a action="sitemaps/detail"}HTML{/a}, {a action="sitemaps/index"}XML{/a})</li>
			<li>
				{t}Localization{/t}
				{capture assign=url_en}{link_to lang=en}{/capture}
				{capture assign=url_cs}{link_to lang=cs}{/capture}
				(<a href="{$url_en}">{t}English{/t}</a>, <a href="{$url_cs}">{t}Czech{/t}</a>)
			</li>
		</ul>
	</section>

	<section>
		<h3>{t}Installation{/t}</h3>

		<p>{t}Are you planning to kick up a new project from the Atk14Catalog? Great! Just run the following commands.{/t}</p>


		<pre><code>cd path/to/projects/
git clone https://github.com/atk14/Atk14Catalog.git my_project
cd my_project
git submodule init
git submodule update
./local_scripts/update_project_name
git add .
git commit -m "Updating project name to My Project"
git remote set-url origin git@my.server.com:my_project.git
git push</code></pre>
	
		<p>
			{t escape=no}You can find more information in <a href="https://github.com/atk14/Atk14Catalog/blob/master/README.md#installation">the installation instructions.</a>{/t}
		</p>

		<p>
			{t escape=no}If you want to help us to improve the Catalog, <a href="https://github.com/atk14/Atk14Catalog">fork it on GitHub.</a>{/t}
		</p>
	</section>

	<section>
		<h3>{t}Further Reading & Resources{/t}</h3>
		<ul>
			<li><a href="http://www.atk14.net/">{t}ATK14 Project{/t}</a></li>
			<li><a href="http://book.atk14.net/">{t}ATK14 Book{/t}</a></li>
			<li><a href="http://api.atk14.net/">{t}API Reference{/t}</a></li>
			<li><a href="https://github.com/atk14/Atk14">{t}ATK14 on GitHub{/t}</a></li>
			<li><a href="https://github.com/atk14/Atk14Catalog">{t}ATK14 Catalog on GitHub{/t}</a></li>
		</ul>
	</section>
</article>
