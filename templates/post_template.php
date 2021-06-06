<?php $post_id = isset($post['id']) ? $post['id'] : null; ?>
<?php $post_header = isset($post['header']) ? $post['header'] : null; ?>
<?php $post_content_type = isset($post['content_type']) ? $post['content_type'] : null; ?>
<?php $post_likes_count = isset($post['likes_count']) ? $post['likes_count'] : null; ?>
<?php $post_comments_count = isset($post['comments_count']) ? $post['comments_count'] : null; ?>
<?php $post_user_id = isset($post['user_id']) ? $post['user_id'] : null; ?>
<?php $post_reposts_count = isset($post['reposts_count']) ? $post['reposts_count'] : null; ?>
<?php $post_num_views = isset($post['num_views']) ? $post['num_views'] : null; ?>
<?php $post_comments_count = isset($post['comments_count']) ? $post['comments_count'] : null; ?>
<?php $post_avatar = isset($post['avatar']) ? $post['avatar'] : null; ?>
<?php $post_username = isset($post['username']) ? $post['username'] : null; ?>
<?php $post_user_dt = isset($post['user_dt']) ? $post['user_dt'] : null; ?>
<?php $post_subscribers_count = isset($post['subscribers_count']) ? $post['subscribers_count'] : null; ?>
<?php $post_posts_count = isset($post['posts_count']) ? $post['posts_count'] : null; ?>
<?php $post_content = isset($post['post_content']) ? $post['post_content'] : null; ?>
<?php $post_author = isset($post['author']) ? $post['author'] : null; ?>
<?php $post_site_url = isset($post['site_url']) ? $post['site_url'] : null; ?>

<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?= $post_header ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper post-<?= $post_content_type ?>">
                <div class="post-details__main-block post post--details">
<?php include('post_template__' . $post_content_type . '.php'); ?>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button"
                               href="post.php?id=<?= $get_id ?>&likepost=<?= $get_id ?>" title="Лайк">
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
                            <a class="post__indicator post__indicator--comments button" href="" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?= $post_comments_count ?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <a class="post__indicator post__indicator--repost button"
                               href="profile.php?id=<?= $post_user_id ?>&repost=<?= $get_id ?>" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?= $post_reposts_count ?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <span class="post__view">
                            <?= $post_num_views ?>
                            <?= get_noun_plural_form($post_num_views, 'просмотр', 'просмотра', 'просмотров') ?>
                        </span>
                    </div>
                    <div class="comments">


                        <form class="comments__form form" action="post.php?id=<?= $post_id ?>" method="post">
                            <input type="hidden" name="post_id" value="<?= $post_id ?>">
                            <div class="comments__my-avatar">
    <?php $session_avatar = (isset($_SESSION['avatar'])) ? filter_var($_SESSION['avatar'], FILTER_SANITIZE_SPECIAL_CHARS) : false; ?>
                                <img class="comments__picture" src="img/userpic-<?= $session_avatar ?>"
                                     alt="Аватар пользователя">
                            </div>
                            <div class="form__input-section
<?php if ($errors !== null) : ?>
                                    form__input-section--error
<?php endif; ?>
                            ">
                                <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий" name="comment"></textarea>
<?php if ($errors !== null) : ?>
                                <label class="visually-hidden">Ваш комментарий</label>
                                <button class="form__error-button button" type="button">!</button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <p class="form__error-desc"><?= $errors ?></p>
                                </div>
<?php endif; ?>
                            </div>
                            <button class="comments__submit button button--green" type="submit" name="submit_comment">
                                Отправить
                            </button>
                        </form>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
<?php if ($comments !== 0) : ?>
    <?php foreach ($comments as $comment) : ?>
    <?php $comment_avatar = isset($comment['avatar']) ? $comment['avatar'] : null; ?>
    <?php $comment_name = isset($comment['name']) ? $comment['name'] : null; ?>
    <?php $comment_dt_add = isset($comment['dt_add']) ? $comment['dt_add'] : null; ?>
    <?php $comment_content = isset($comment['content']) ? $comment['content'] : null; ?>
                                    <li class="comments__item user">
                                        <div class="comments__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img class="comments__picture"
                                                        src="img/userpic-<?= $comment_avatar ?>"
                                                        alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name" href="#">
                                                    <span><?= $comment_name ?></span>
                                                </a>
                                                <time class="comments__time" datetime="<?= $comment_dt_add ?>">
                                                    <?= datetime_relative($comment_dt_add) ?> назад
                                                </time>
                                            </div>
                                            <p class="comments__text">
                                                <?= $comment_content ?>
                                            </p>
                                        </div>
                                    </li>
    <?php endforeach ?>
                            </ul>
    <?php if ($post_comments_count > 2) : ?>
                                <a class="comments__more-link" href="#">
                                    <span>Показать все комментарии</span>
                                    <sup class="comments__amount"><?= $post_comments_count ?></sup>
                                </a>
    <?php endif ?>
<?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="#">
                                <img class="post-details__picture user__picture"
                                     src="img/userpic-<?= $post_avatar ?>" alt="Аватар пользователя">
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="profile.php?id=<?= $post_user_id ?>">
                                <span><?= $post_username ?></span>
                            </a>
                            <time class="post-details__time user__time"
                                  datetime="2014-03-20"><?= datetime_relative($post_user_dt) ?> на сайте
                            </time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount">
                                <?= $post_subscribers_count ?>
                            </span>
                            <span class="post-details__rating-text user__rating-text">
                                <?= get_noun_plural_form($post_subscribers_count, 'подписчик', 'подписчика', 'подписчиков') ?>
                            </span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount">
                                <?= $post_posts_count ?>
                            </span>
                            <span class="post-details__rating-text user__rating-text">
                                <?= get_noun_plural_form($post_posts_count, 'публикация', 'публикации', 'публикаций') ?>
                            </span>
                        </p>
                    </div>
                    <form action="post.php?id=<?= $get_id ?>" method="post">
                        <div class="post-details__user-buttons user__buttons">
<?php if ($subscribe === 0) : ?>
                                <button class="user__button user__button--subscription button button--main"
                                        type="submit" name="subscribe">Подписаться
                                </button>
<?php else : ?>
                                <button class="user__button user__button--subscription button button--quartz"
                                        type="submit" name="unsubscribe">Отписаться
                                </button>
                                <a class="user__button user__button--writing button button--green"
                                   href="#">Сообщение</a>
<?php endif; ?>
                        </div>
                    </form>
                </div>
                </form>
            </div>
        </section>
    </div>
</main>
