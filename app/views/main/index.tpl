<div class="row">
	<div class="col-sm-6">
		<h1>{t}Welcome at ATK14 Catalog!{/t}</h1>
		<p>
			{t escape=no}<em>ATK14 Catalog</em> is an skeleton suitable for applications of kind like <em>Products introduction</em>, <em>E-shop</em>, etc.{/t}
			{t escape=no}ATK14 Catalog is built on top of <em>ATK14 Skelet</em>{/t} &mdash; another great skeleton.</p>
		<p>
		<h3>{t}The Catalog contains mainly{/t}</h3>
		<ul>
			<li>{a controller="categories"}List of categories{/a}</li>
			<li>{a controller="brands"}List of brands{/a}</li>
			<li>{a controller="collections"}List of collections{/a}</li>
			<li>{a action="static_pages/detail" id=1}Static pages with a hierarchical structure{/a}</li>
			<li>{a action="main/contact"}Contact page with fast contact form{/a}</li>
			<li>{a action="news/index"}{t}News section{/t}{/a}</li>
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

		<h3>{t}Installation{/t}</h3>

		<p>
			{t escape=no}If you are brave enough to install the Skelet on your computer, check out <a href="https://github.com/atk14/Atk14Skelet/blob/master/README.md#installation">the installation instrunction.</a>{/t}
		</p>

		<p>
			{t escape=no}If you want to help us to improve the Skelet, <a href="https://github.com/atk14/Atk14Skelet">fork it on GitHub.</a>{/t}
		</p>


		<h3>{t}Further Reading & Resources{/t}</h3>
		<ul>
			<li><a href="http://www.atk14.net/">{t}ATK14 Project{/t}</a></li>
			<li><a href="http://book.atk14.net/">{t}ATK14 Book{/t}</a></li>
			<li><a href="http://api.atk14.net/">{t}API Reference{/t}</a></li>
			<li><a href="https://github.com/atk14/Atk14">{t}ATK14 on GitHub{/t}</a></li>
			<li><a href="https://github.com/atk14/Atk14Catalog">{t}ATK14 Catalog on GitHub{/t}</a></li>
		</ul>
	</div>

	<div class="col-sm-6">
		<img src="{$public}images/skelet.png" alt="ATK14 Skelet" title="{t}The ATK14 Skelet at age 5{/t}" class="img-responsive">
		<p style="font-size: 0.7em; text-align: center;">{t escape=no}fig.1 <em>The Creature is pleading for forking on GitHub</em>{/t}</p>
	</div>
</div>
