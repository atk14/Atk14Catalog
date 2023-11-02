<article>

	{if $page}
		
		{render partial="shared/layout/content_header" title=$page->getTitle() teaser=$page->getTeaser()|markdown}
		{admin_menu for=$page}
		
		{!$page->getBody()|markdown}
			
	{else}

		{render partial="shared/layout/content_header" title=$page_title}

	{/if}
	<div class="text-center text-strong bg-dark text-light mb-4" style="padding: 100px 1vw; font-size: min(240px, 17vw); letter-spacing: -0.07em; line-height: 1;">Atk14 Skelet</div>
	<div class="container-fluid">
		<div class="row text-center mb-5">
			<div class="col-12 col-md-5 p-4 bg-warning h2 text-light rounded-4">Bootstrap 5</div>
			<div class="col-12 col-md-2 p-4 h2 text-info rounded-4"><i class="fa-solid fa-circle-plus"></i></div>
			<div class="col-12 col-md-5 p-4 bg-success h2 text-light rounded-4">Webpack</div>
		</div>
	</div>
	<section class="border-top-0">
		<h3>{t}The Catalog contains mainly{/t}</h3>
		<ul>
			<li><a href="{"catalog"|link_to_category}">{t}List of categories{/t}</a></li>
			<li>{a controller="brands"}{t}List of brands{/t}{/a}</li>
			<li>{a controller="collections"}{t}List of collections{/t}{/a}</li>
			<li>{a controller="stores"}{t}List of stores{/t}{/a}</li>
			<li><a href="{"about_us"|link_to_page}">{t}Pages with a hierarchical structure{/t}</a></li>
			<li><a href="{"contact"|link_to_page}">{t}Contact page with fast contact form{/t}</a></li>
			<li>{a action="articles/index"}{t}Articles section{/t}{/a}</li>
			<li>{t}Manageable link lists in header and footer{/t}</li>
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

