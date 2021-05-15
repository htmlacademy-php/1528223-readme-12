<?php if(count($search) > 0): ?>
<main class="page__main page__main--search-results">
  <h1 class="visually-hidden">Страница результатов поиска</h1>
  <section class="search">
	<h2 class="visually-hidden">Результаты поиска</h2>
	<div class="search__query-wrapper">
	  <div class="search__query container">
		<span>Вы искали:</span>
		<span class="search__query-text"><?=$get_id?></span>
	  </div>
	</div>
	<div class="search__results-wrapper">
	  <div class="container">
		<div class="search__content">
			
		
			
		<?php foreach($search as $key => $val): ?>
		
		  <article class="search__post post post-<?=$val['type']?>">
			<header class="post__header post__author">
			  <a class="post__author-link" href="profile.php?id=<?=$val['user_id']?>" title="Автор">
				<div class="post__avatar-wrapper">
				  <img class="post__author-avatar" src="img/userpic-<?=$val['avatar']?>" alt="Аватар пользователя" width="60" height="60">
				</div>
				<div class="post__info">
				  <b class="post__author-name"><?=$val['name']?></b>
				  <span class="post__time"><?=datetime_relative($val['dt'])?> назад</span>
				</div>
			  </a>
			</header>
			
			<?php if($val['type'] == 'photo'): ?>
			
			<div class="post__main">
			  <h2><a href="post.php?id=<?=$val['id']?>"><?=$val['header']?></a></h2>
			  <div class="post-photo__image-wrapper">
				<img src="img/<?=$val['image_url']?>" alt="Фото от пользователя" width="760" height="396">
			  </div>
			</div>
			
			<?php elseif($val['type'] == 'text'): ?>
			
			<div class="post__main">
			  <h2><a href="post.php?id=<?=$val['id']?>"><?=$val['header']?></a></h2>
			  <p>
				<?=short_text($val['text'], 300)?>
			  </p>
			  <a class="post-text__more-link" href="post.php?id=<?=$val['id']?>">Читать далее</a>
			</div>
			
			<?php elseif($val['type'] == 'video'): ?>
			
			<div class="post__main">
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
			</div>
			
			<?php elseif($val['type'] == 'quote'): ?>
			
			<div class="post__main">
			  <blockquote>
				<p>
				  <?=$val['text']?>
				</p>
				<cite><?=$val['author']?></cite>
			  </blockquote>
			</div>
			
			<?php elseif($val['type'] == 'link'): ?>
			
			<div class="post__main">
			  <div class="post-link__wrapper">
				<a class="post-link__external" href="<?=$val['site_url']?>" title="Перейти по ссылке">
				  <div class="post-link__icon-wrapper">
					<img src="img/<?=$val['image_url']?>" alt="Иконка">
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
			</div>
			
			<?php endif; ?>
			
			<footer class="post__footer post__indicators">
			  <div class="post__buttons">
				<a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
				  <svg class="post__indicator-icon" width="20" height="17">
					<use xlink:href="#icon-heart"></use>
				  </svg>
				  <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
					<use xlink:href="#icon-heart-active"></use>
				  </svg>
				  <span><?=$val['likes_count']?></span>
				  <span class="visually-hidden">количество лайков</span>
				</a>
				<a class="post__indicator post__indicator--comments button" href="post.php?id=<?=$val['id']?>" title="Комментарии">
				  <svg class="post__indicator-icon" width="19" height="17">
					<use xlink:href="#icon-comment"></use>
				  </svg>
				  <span><?=$val['comments_count']?></span>
				  <span class="visually-hidden">количество комментариев</span>
				</a>
			  </div>
			</footer>
		  </article>
		
		<?php endforeach; ?>
		
		</div>
	  </div>
	</div>
  </section>
</main>

<?php else: ?>

<main class="page__main page__main--search-results">
  <h1 class="visually-hidden">Страница результатов поиска (нет результатов)</h1>
  <section class="search">
	<h2 class="visually-hidden">Результаты поиска</h2>
	<div class="search__query-wrapper">
	  <div class="search__query container">
		<span>Вы искали:</span>
		<span class="search__query-text"><?=$get_id?></span>
	  </div>
	</div>
	<div class="search__results-wrapper">
	  <div class="search__no-results container">
		<p class="search__no-results-info">К сожалению, ничего не найдено.</p>
		<p class="search__no-results-desc">
		  Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;, там живет самый крутой контент.
		</p>
		<div class="search__links">
		  <a class="search__popular-link button button--main" href="popular.php">Популярное</a>
		  <a class="search__back-link" href="<?=$_SERVER['HTTP_REFERER']?>">Вернуться назад</a>
		</div>
	  </div>
	</div>
  </section>
</main>

<?php endif ?>

