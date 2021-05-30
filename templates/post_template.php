<main class="page__main page__main--publication">
  <div class="container">
    <h1 class="page__title page__title--publication"><?=$post['header']?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-<?=$post['content_type']?>">
        <div class="post-details__main-block post post--details">
		  
		  <?php include('post_template__' . $post['content_type'] . '.php'); ?>
		  
          <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="post.php?id=<?=$get_id?>&likepost=<?=$get_id?>" title="Лайк">
                <svg class="post__indicator-icon" width="20" height="17">
                  <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                  <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?=$post['likes_count']?></span>
                <span class="visually-hidden">количество лайков</span>
              </a>
              <a class="post__indicator post__indicator--comments button" href="" title="Комментарии">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-comment"></use>
                </svg>
                <span><?=$post['comments_count']?></span>
                <span class="visually-hidden">количество комментариев</span>
              </a>
              <a class="post__indicator post__indicator--repost button" href="profile.php?id=<?=$post['user_id']?>&repost=<?=$get_id?>" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span><?=$post['reposts_count']?></span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <span class="post__view"><?=$post['num_views']?> <?=get_noun_plural_form($post['num_views'], 'просмотр', 'просмотра', 'просмотров')?></span>
          </div>
          <div class="comments">
			  
			  
            <form class="comments__form form" action="post.php?id=<?=$post['id']?>" method="post">
              <input type="hidden" name="post_id" value="<?=$post['id']?>">
              <div class="comments__my-avatar">
                <img class="comments__picture" src="img/userpic-<?=$_SESSION['avatar']?>" alt="Аватар пользователя">
              </div>
              <div class="form__input-section form__input-section<?php if($errors !== NULL): ?>--error<?php endif; ?>">
                <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий" name="comment"></textarea>
                
                <?php if($errors !== NULL): ?>
                
                <label class="visually-hidden">Ваш комментарий</label>
                <button class="form__error-button button" type="button">!</button>
                <div class="form__error-text">
                  <h3 class="form__error-title">Ошибка валидации</h3>
                  <p class="form__error-desc"><?=$errors?></p>
                </div>
                
                <?php endif; ?>
                
              </div>
              <button class="comments__submit button button--green" type="submit" name="submit_comment">Отправить</button>
            </form>
            
            
            <div class="comments__list-wrapper">
              <ul class="comments__list">
				<? if($comments !== 0): ?>
					<?php foreach($comments as $key => $val): ?>
						<li class="comments__item user">
							<div class="comments__avatar">
								<a class="user__avatar-link" href="#">
									<img class="comments__picture" src="img/userpic-<?=$val['avatar']?>" alt="Аватар пользователя">
								</a>
							</div>
							<div class="comments__info">
								<div class="comments__name-wrapper">
									<a class="comments__user-name" href="#">
										<span><?=$val['name']?></span>
									</a>
									<time class="comments__time" datetime="<?=$val['dt_add']?>"><?=datetime_relative($val['dt_add'])?> назад</time>
								</div>
								<p class="comments__text">
									<?=$val['content']?>
								</p>
							</div>
						</li>
					<?php endforeach ?>
				  </ul>
				  <?php if($post['comments_count'] > 2): ?>
					<a class="comments__more-link" href="#">
					  <span>Показать все комментарии</span>
					  <sup class="comments__amount"><?=$post['comments_count']?></sup>
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
                <img class="post-details__picture user__picture" src="img/userpic-<?=$post['avatar']?>" alt="Аватар пользователя">
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="profile.php?id=<?=$post['user_id']?>">
                <span><?=$post['username']?></span>
              </a>
              <time class="post-details__time user__time" datetime="2014-03-20"><?=datetime_relative($post['user_dt'])?> на сайте</time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?=$post['subscribers_count']?></span>
              <span class="post-details__rating-text user__rating-text"><?=get_noun_plural_form($post['subscribers_count'], 'подписчик', 'подписчика', 'подписчиков')?></span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?=$post['posts_count']?></span>
              <span class="post-details__rating-text user__rating-text"><?=get_noun_plural_form($post['posts_count'], 'публикация', 'публикации', 'публикаций')?></span>
            </p>
          </div>
          <form action="post.php?id=<?=$get_id?>" method="post">
		  <div class="post-details__user-buttons user__buttons">
            <?php if($subscribe === 0): ?>
            <button class="user__button user__button--subscription button button--main" type="submit" name="subscribe">Подписаться</button>
            <?php else: ?>
            <button class="user__button user__button--subscription button button--quartz" type="submit" name="unsubscribe">Отписаться</button>
            <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
            <?php endif; ?>
          </div>
		  </form>
		</div>
		</form>
      </div>
    </section>
  </div>
</main>
