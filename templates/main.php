<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all filters__button--active" href="#">
                            <span>Все</span>
                        </a>
                    </li>
                    
                    <?php foreach ($content_types as $type): // 4. Используйте эти данные для показа списка постов и списка типов контента на главной странице. ?>
						<li class="popular__filters-item filters__item">
							<a class="filters__button filters__button--<?=$type['class']?> button" href="#">
								<span class="visually-hidden"><?$type['name']?></span>
								<svg class="filters__icon" width="22" height="18">
									<use xlink:href="#icon-filter-<?=$type['class']?>"></use>
								</svg>
							</a>
						</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            
            <?php foreach($popular_posts as $key => $val): // 4. Используйте эти данные для показа списка постов и списка типов контента на главной странице. ?>

            <article class="popular__post post post-<?=$val['type']?>">

                <header class="post__header">
                    <h2><?=$val['header']?></h2>
                </header>
                <div class="post__main">
					<?php if($val['type'] === 'quote'): ?>
						<!--содержимое для поста-цитаты-->
						<blockquote>
							<p><?=$val['content']?></p>
							<cite><?=$val['author']?></cite>
						</blockquote>
					<?php elseif($val['type'] === 'text'): ?>
						<!--содержимое для поста-текста-->
						<p><?=short_text($val['content'])?></p>
					<?php elseif($val['type'] === 'photo'): ?>
						<!--содержимое для поста-фото-->
						<div class="post-photo__image-wrapper">
							<img src="img/<?=$val['content']?>" alt="Фото от пользователя" width="360" height="240">
						</div>
					<?php elseif($val['type'] === 'link'): ?>
						<!--содержимое для поста-ссылки-->
						<div class="post-link__wrapper">
							<a class="post-link__external" href="http://<?=$val['url']?>" title="Перейти по ссылке">
								<div class="post-link__info-wrapper">
									<div class="post-link__icon-wrapper">
										<img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
									</div>
									<div class="post-link__info">
										<h3><?=$val['header']?></h3>
									</div>
								</div>
								<span><?=$val['content']?></span>
							</a>
						</div>
						
					<?php elseif($val['type'] === 'video'): ?>
						<!--содержимое для поста-видео-->
						<div class="post-video__block">
							<div class="post-video__preview">
								<?=embed_youtube_cover($val['content'])?>
							</div>
							<a href="post-details.html" class="post-video__play-big button">
								<svg class="post-video__play-big-icon" width="14" height="14">
									<use xlink:href="#icon-video-play-big"></use>
								</svg>
								<span class="visually-hidden">Запустить проигрыватель</span>
							</a>
						</div>
						
					<?php endif ?>

                </div>
                <footer class="post__footer">
                    <div class="post__author">
                        <a class="post__author-link" href="#" title="Автор">
                            <div class="post__avatar-wrapper">
                                <!--укажите путь к файлу аватара-->
                                <img class="post__author-avatar" src="img/userpic-<?=$val['avatar']?>" alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name"><?=$val['username']?></b>
                                <time class="post__time" datetime="<?=$val['datetime']?>" title="<?=datetime_format($val['datetime'])?>"><?=datetime_relative($val['datetime'])?></time>
                            </div>
                        </a>
                    </div>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span>0</span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span>0</span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                        </div>
                    </div>
                </footer>
            </article>
            
            <?php endforeach; ?>       
            
        </div>
    </div>
</section>
