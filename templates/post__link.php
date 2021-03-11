<div class="post__main">
  <div class="post-link__wrapper">
	<a class="post-link__external" href="http://<?=$post['site_url']?>" title="Перейти по ссылке">
	  <div class="post-link__info">
		<h3><?=$post['post_content']?></h3>
		<span><?=$post['site_url']?></span>
	  </div>
	  <svg class="post-link__arrow" width="11" height="16">
		<use xlink:href="#icon-arrow-right-ad"></use>
	  </svg>
	</a>
  </div>
</div>
