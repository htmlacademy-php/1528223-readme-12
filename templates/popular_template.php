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
                        <a class="filters__button filters__button--ellipse filters__button--all<?= $active_all ?>"
                           href="?type=0">
                            <span>Все</span>
                        </a>
                    </li>

<?php foreach ($content_types as $type) : ?>
    <?php $type_id = isset($type['id']) ? $type['id'] : null; ?>
    <?php $type_name = isset($type['name']) ? $type['name'] : null; ?>
    <?php $type_class = isset($type['class']) ? $type['class'] : null; ?>
    <?php $active_type = ''; // по умолчанию кнопки неактивны ?>
    <?php if ($type_id === $get_type) : // если переменная $get_type равна id типа: ?>
        <?php $active_type = ' filters__button--active'; // то эта кнопка активна ?>
    <?php endif; ?>
                        <li class="popular__filters-item filters__item">
                            <a class="filters__button filters__button--<?= $type_class ?> button<?= $active_type ?>"
                               href="?type=<?= $type_id ?>">
                                <span class="visually-hidden"><?= $type_name ?></span>
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-<?= $type_class ?>"></use>
                                </svg>
                            </a>
                        </li>
<?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
<?php foreach ($posts as $post) : ?>
    <?php $post_id = isset($post['id']) ? $post['id'] : null; ?>
    <?php $post_type = isset($post['type']) ? $post['type'] : null; ?>
    <?php $post_header = isset($post['header']) ? $post['header'] : null; ?>
    <?php $post_content = isset($post['content']) ? $post['content'] : null; ?>
    <?php $post_author = isset($post['author']) ? $post['author'] : null; ?>
    <?php $post_url = isset($post['url']) ? $post['url'] : null; ?>
    <?php $post_user_id = isset($post['user_id']) ? $post['user_id'] : null; ?>
    <?php $post_avatar = isset($post['avatar']) ? $post['avatar'] : null; ?>
    <?php $post_username = isset($post['username']) ? $post['username'] : null; ?>
    <?php $post_datetime = isset($post['datetime']) ? $post['datetime'] : null; ?>
    <?php $post_likes_count = isset($post['likes_count']) ? $post['likes_count'] : null; ?>
    <?php $post_comments_count = isset($post['comments_count']) ? $post['comments_count'] : null; ?>
                <article class="popular__post post post-<?= $post_type ?>">
                    <header class="post__header">
                        <h2><a href="post.php?id=<?= $post_id ?>"><?= $post_header ?></a></h2>
                    </header>
                    <div class="post__main">
    <?php if ($post_type === 'quote') : ?>
                            <blockquote>
                                <p><?= $post_content ?></p>
                                <cite><?= $post_author ?></cite>
                            </blockquote>
    <?php elseif ($post_type === 'text') : ?>
                            <p><?= short_text($post_content) ?></p>
    <?php elseif ($post_type === 'photo') : ?>
                            <div class="post-photo__image-wrapper">
                                <img src="img/<?= $post_content ?>" alt="Фото от пользователя" width="360"
                                     height="240">
                            </div>
    <?php elseif ($post_type === 'link') : ?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="http://<?= $post_url ?>"
                                   title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                                                 alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= $post_header ?></h3>
                                        </div>
                                    </div>
                                    <span><?= $post_content ?></span>
                                </a>
                            </div>
    <?php elseif ($post_type === 'video') : ?>
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?= embed_youtube_cover($post_content) ?>
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
                            <a class="post__author-link" href="profile.php?id=<?= $post_user_id ?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <img class="post__author-avatar" src="img/userpic-<?= $post_avatar ?>"
                                         alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?= $post_username ?></b>
                                    <time class="post__time" datetime="<?= $post_datetime ?>"
                                            title="<?= datetime_format($post_datetime) ?>">
                                        <?= datetime_relative($post_datetime) ?> назад
                                    </time>
                                </div>
                            </a>
                        </div>
                        <div class="post__indicators">
                            <div class="post__buttons">
                                <a class="post__indicator post__indicator--likes button"
                                   href="popular.php?likepost=<?= $post_id ?>" title="Лайк">
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                         height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span><?= $post_likes_count ?></span>
                                    <span class="visually-hidden">количество лайков</span>
                                </a>
                                <a class="post__indicator post__indicator--comments button"
                                   href="post.php?id=<?= $post_id ?>" title="Комментарии">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-comment"></use>
                                    </svg>
                                    <span><?= $post_comments_count ?></span>
                                    <span class="visually-hidden">количество комментариев</span>
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>
<?php endforeach; ?>
        </div>
        <div class="popular__page-links">
<?php if ($prev_link !== null) : ?>
                <a class="popular__page-link popular__page-link--prev button button--gray"
                   href="?page=<?= $prev_link ?>">Предыдущая страница</a>
<?php endif; ?>
<?php if ($next_link !== null) : ?>
                <a class="popular__page-link popular__page-link--next button button--gray"
                   href="?page=<?= $next_link ?>">Следующая страница</a>
<?php endif; ?>
        </div>
    </div>
</section>
