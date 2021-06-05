<?php if (count($search) > 0) : ?>
    <main class="page__main page__main--search-results">
        <h1 class="visually-hidden">Страница результатов поиска</h1>
        <section class="search">
            <h2 class="visually-hidden">Результаты поиска</h2>
            <div class="search__query-wrapper">
                <div class="search__query container">
                    <span>Вы искали:</span>
                    <span class="search__query-text"><?= $get_search ?></span>
                </div>
            </div>
            <div class="search__results-wrapper">
                <div class="container">
                    <div class="search__content">
    <?php foreach ($search as $result) : ?>
        <?php $result_id = isset($result['id']) ? $result['id'] : null; ?>
        <?php $result_type = isset($result['type']) ? $result['type'] : null; ?>
        <?php $result_user_id = isset($result['user_id']) ? $result['user_id'] : null; ?>
        <?php $result_avatar = isset($result['avatar']) ? $result['avatar'] : null; ?>
        <?php $result_name = isset($result['name']) ? $result['name'] : null; ?>
        <?php $result_dt = isset($result['dt']) ? $result_dt : null; ?>
        <?php $result_header = isset($result['header']) ? $result['header'] : null; ?>
        <?php $result_image_url = isset($result['image_url']) ? $result['image_url'] : null; ?>
        <?php $result_text = isset($result['text']) ? $result['text'] : null; ?>
        <?php $result_author = isset($result['author']) ? $result['author'] : null; ?>
        <?php $result_site_url = isset($result['site_url']) ? $result['site_url'] : null; ?>
        <?php $result_likes_count = isset($result['likes_count']) ? $result['likes_count'] : null; ?>
        <?php $result_comments_count = isset($result['comments_count']) ? $result['comments_count'] : null; ?>
                        <article class="search__post post post-<?= $result_type ?>">
                            <header class="post__header post__author">
                                <a class="post__author-link" href="profile.php?id=<?= $result_user_id ?>"
                                   title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="img/userpic-<?= $result_avatar ?>"
                                             alt="Аватар пользователя" width="60" height="60">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name">
                                            <?= $result_name ?>
                                        </b>
                                        <span class="post__time">
                                            <?= datetime_relative($result_dt) ?> назад
                                        </span>
                                    </div>
                                </a>
                            </header>
        <?php if ($result_type === 'photo') : ?>
                            <div class="post__main">
                                <h2><a href="post.php?id=<?= $result_id ?>"><?= $result_header ?></a></h2>
                                <div class="post-photo__image-wrapper">
                                    <img src="img/<?= $result_image_url ?>" alt="Фото от пользователя"
                                         width="760" height="396">
                                </div>
                            </div>
        <?php elseif ($result_type === 'text') : ?>
                            <div class="post__main">
                                <h2><a href="post.php?id=<?= $result_id ?>"><?= $result_header ?></a></h2>
                                <p>
                                    <?= short_text($result_text, 300) ?>
                                </p>
                                <a class="post-text__more-link" href="post.php?id=<?= $result_id ?>">
                                    Читать далее
                                </a>
                            </div>
        <?php elseif ($result_type === 'video') : ?>
                            <div class="post__main">
                                <div class="post-video__block">
                                    <div class="post-video__preview">
                                        <img src="img/coast.jpg" alt="Превью к видео" width="760" height="396">
                                    </div>
                                    <div class="post-video__control">
                                        <button class="post-video__play
                                                post-video__play--paused
                                                button button--video"
                                                type="button">
                                            <span class="visually-hidden">Запустить видео</span>
                                        </button>
                                        <div class="post-video__scale-wrapper">
                                            <div class="post-video__scale">
                                                <div class="post-video__bar">
                                                    <div class="post-video__toggle"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="post-video__fullscreen
                                                post-video__fullscreen--inactive
                                                button button--video"
                                                type="button">
                                            <span class="visually-hidden">Полноэкранный режим</span>
                                        </button>
                                    </div>
                                    <button class="post-video__play-big button" type="button">
                                        <svg class="post-video__play-big-icon" width="27" height="28">
                                            <use xlink:href="#icon-video-play-big"></use>
                                        </svg>
                                        <span class="visually-hidden">Запустить проигрыватель</span>
                                    </button>
                                </div>
                            </div>
        <?php elseif ($result_type === 'quote') : ?>
                            <div class="post__main">
                                <blockquote>
                                    <p>
                                        <?= $result_text ?>
                                    </p>
                                    <cite><?= $result_author ?></cite>
                                </blockquote>
                            </div>
                                <?php elseif ($result_type === 'link') : ?>
                            <div class="post__main">
                                <div class="post-link__wrapper">
                                    <a class="post-link__external" href="<?= $result_site_url ?>"
                                       title="Перейти по ссылке">
                                        <div class="post-link__icon-wrapper">
                                            <img src="img/<?= $result_image_url ?>" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= $result_header ?></h3>
                                            <p><?= $result_text ?></p>
                                            <span><?= $result_site_url ?></span>
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
                                            <svg class="post__indicator-icon post__indicator-icon--like-active"
                                                 width="20" height="17">
                                                <use xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span><?= $result_likes_count ?></span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </a>
                                        <a class="post__indicator post__indicator--comments button"
                                           href="post.php?id=<?= $result['id'] ?>" title="Комментарии">
                                            <svg class="post__indicator-icon" width="19" height="17">
                                                <use xlink:href="#icon-comment"></use>
                                            </svg>
                                            <span><?= $result_comments_count ?></span>
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
<?php else : ?>
    <main class="page__main page__main--search-results">
        <h1 class="visually-hidden">Страница результатов поиска (нет результатов)</h1>
        <section class="search">
            <h2 class="visually-hidden">Результаты поиска</h2>
            <div class="search__query-wrapper">
                <div class="search__query container">
                    <span>Вы искали:</span>
                    <span class="search__query-text"><?= $get_search ?></span>
                </div>
            </div>
            <div class="search__results-wrapper">
                <div class="search__no-results container">
                    <p class="search__no-results-info">К сожалению, ничего не найдено.</p>
                    <p class="search__no-results-desc">
                        Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;, там
                        живет самый крутой контент.
                    </p>
                    <div class="search__links">
                        <a class="search__popular-link button button--main" href="popular.php">Популярное</a>
                        <a class="search__back-link" href="<?= $_SERVER['HTTP_REFERER'] ?>">Вернуться назад</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php endif ?>

