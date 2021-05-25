<main class="page__main page__main--feed">
  <div class="container">
	<h1 class="page__title page__title--feed">Моя лента</h1>
  </div>
  <div class="page__main-wrapper container">
	<section class="feed">
	  <h2 class="visually-hidden">Лента</h2>
	  <div class="feed__main-wrapper">
		<div class="feed__wrapper">
		  
		  <?php foreach($posts as $key => $val): ?>
		  
		  <article class="feed__post post post-<?=$val['type']?>">
			
			<header class="post__header post__author">
			  <a class="post__author-link" href="profile.php?id=<?php if($val['repost'] !== NULL):?><?=$val['original_author']?><?php else:?><?=$val['user_id']?><?php endif;?>" title="Автор">
				<div class="post__avatar-wrapper<?php if($val['repost'] !== NULL):?> post__avatar-wrapper--repost<?php endif;?>">
				  <?php if($val['repost'] !== NULL):?>
				  <img class="post__author-avatar" src="img/userpic-<?=$val['author_avatar']?>" alt="Аватар пользователя" width="60" height="60">
				  <?php else: ?>
				  <img class="post__author-avatar" src="img/userpic-<?=$val['avatar']?>" alt="Аватар пользователя" width="60" height="60">
				  <?php endif; ?>
				</div>
				<div class="post__info">
				  <b class="post__author-name">
					<?php if($val['repost'] !== NULL):?>
					<a href="profile.php?id=<?=$val['original_author']?>">Репост: <?=$val['author_name']?></a>
					<?php else: ?>
					<a href="profile.php?id=<?=$val['user_id']?>"><?=$val['username']?></a>
					<?php endif; ?>
				  </b>
				  <span class="post__time"><?=datetime_relative($val['dt_add'])?> назад</span>
				</div>
			  </a>
			  
			  
			</header>
			
			<div class="post__main">
			  <h2><a href="post.php?id=<?=$val['post_id']?>"><?=$val['header']?></a></h2>
			
			<?php if($val['type'] == 'text'): ?>

			    <p>
				  <?=short_text($val['text'])?>
			    </p>
			    <a class="post-text__more-link" href="post.php?id=<?=$val['id']?>">Читать далее</a>
			  
			  <?php elseif($val['type'] == 'quote'): ?>
			  
                <blockquote>
                  <p>
                    <?=$val['text']?>
                  </p>
                  <cite><?=$val['author']?></cite>
                </blockquote>
			  
			  <?php elseif($val['type'] == 'photo'): ?>
			  
				<div class="post-photo__image-wrapper">
				  <img src="img/<?=$val['image_url']?>" alt="Фото от пользователя" width="760" height="396">
				</div>
			  
			  <?php elseif($val['type'] == 'video'): ?>
			  
                <div class="post-video__block">
                  <div class="post-video__preview">
                    <img src="img/coast.jpg" alt="Превью к видео" width="760" height="396">
                  </div>
                  <div class="post-video__control">
                    <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                    <div class="post-video__scale-wrapper">
                      <div class="post-video__scale">
                        <div class="post-video__bar">
                          <div class="post-video__toggle"></div>
                        </div>
                      </div>
                    </div>
                    <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                  </div>
                  <button class="post-video__play-big button" type="button">
                    <svg class="post-video__play-big-icon" width="27" height="28">
                      <use xlink:href="#icon-video-play-big"></use>
                    </svg>
                    <span class="visually-hidden">Запустить проигрыватель</span>
                  </button>
                </div>
			  
			  <?php elseif($val['type'] == 'link'): ?>
			  
                <div class="post-link__wrapper">
                  <a class="post-link__external" href="http://www.vitadental.ru" title="Перейти по ссылке">
                    <div class="post-link__icon-wrapper">
                      <img src="img/logo-vita.jpg" alt="Иконка">
                    </div>
                    <div class="post-link__info">
                      <h3><?=$val['header']?></h3>
                      <p><?=$val['text']?></p>
                      <span><?=$val['site_url']?></span>
                    </div>
                    <svg class="post-link__arrow" width="11" height="16">
                      <use xlink:href="#icon-arrow-right-ad"></use>
                    </svg>
                  </a>
                </div>
			  
			  <?php endif; ?>
			  
			</div>
			
			<footer class="post__footer post__indicators">
			  <div class="post__buttons">
				<a class="post__indicator post__indicator--likes button" href="feed.php?likepost=<?=$val['post_id']?>" title="Лайк">
				  <svg class="post__indicator-icon" width="20" height="17">
					<use xlink:href="#icon-heart"></use>
				  </svg>
				  <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
					<use xlink:href="#icon-heart-active"></use>
				  </svg>
				  <span><?=$val['count_likes']?></span>
				  <span class="visually-hidden">количество лайков</span>
				</a>
				<a class="post__indicator post__indicator--comments button" href="post.php?id=<?=$val['post_id']?>" title="Комментарии">
				  <svg class="post__indicator-icon" width="19" height="17">
					<use xlink:href="#icon-comment"></use>
				  </svg>
				  <span><?=$val['count_comments']?></span>
				  <span class="visually-hidden">количество комментариев</span>
				</a>
				<a class="post__indicator post__indicator--repost button" href="profile.php?id=<?=$_SESSION['user']?>&repost=<?=$val['post_id']?>" title="Репост">
				  <svg class="post__indicator-icon" width="19" height="17">
					<use xlink:href="#icon-repost"></use>
				  </svg>
				  <span><?=$val['count_reposts']?></span>
				  <span class="visually-hidden">количество репостов</span>
				</a>
			  </div>
			</footer>
			<?php if(isset($hashtags[$val['post_id']])): ?>
			<ul class="post__tags">
			  <?php foreach($hashtags[$val['post_id']] as $key => $val): ?>
			  <li><a href="search.php?s=%23<?=$val?>">#<?=$val?></a></li>
			  <?php endforeach; ?>
			</ul>
			<?php endif; ?>
		  </article>
		
		  <?php endforeach; ?>		  
		</div>
	  </div>
	  <ul class="feed__filters filters">
		  
		<li class="feed__filters-item filters__item">
		  <a class="filters__button<?php if($get_type === 0):?> filters__button--active<?php endif;?>" href="feed.php?id=0">
			<span>Все</span>
		  </a>
		</li>
		
		<?php foreach($content_types as $key => $val): ?>
		<li class="feed__filters-item filters__item">
		  <a class="filters__button filters__button--<?=$val['class']?> button<?php if($get_type === $val['id']):?> filters__button--active<?php endif;?>" href="feed.php?type=<?=$val['id']?>">
			<span class="visually-hidden"><?=$val['name']?></span>
			<svg class="filters__icon" width="22" height="18">
			  <use xlink:href="#icon-filter-<?=$val['class']?>"></use>
			</svg>
		  </a>
		</li>
		<?php endforeach; ?>
		
	  </ul>
	</section>
	<aside class="promo">
	  <article class="promo__block promo__block--barbershop">
		<h2 class="visually-hidden">Рекламный блок</h2>
		<p class="promo__text">
		  Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
		</p>
		<a class="promo__link" href="#">
		  Подробнее
		</a>
	  </article>
	  <article class="promo__block promo__block--technomart">
		<h2 class="visually-hidden">Рекламный блок</h2>
		<p class="promo__text">
		  Товары будущего уже сегодня в онлайн-сторе Техномарт!
		</p>
		<a class="promo__link" href="#">
		  Перейти в магазин
		</a>
	  </article>
	  <article class="promo__block">
		<h2 class="visually-hidden">Рекламный блок</h2>
		<p class="promo__text">
		  Здесь<br> могла быть<br> ваша реклама
		</p>
		<a class="promo__link" href="#">
		  Разместить
		</a>
	  </article>
	</aside>
  </div>
</main>
