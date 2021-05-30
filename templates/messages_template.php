<main class="page__main page__main--messages">
  <h1 class="visually-hidden">Личные сообщения</h1>
  <section class="messages tabs">
	<h2 class="visually-hidden">Сообщения</h2>
	<div class="messages__contacts">
	  <ul class="messages__contacts-list tabs__list">
		
		<?php foreach($users as $key => $val): ?>
		<?php if($val['id'] !== $user_id): ?>
		<li class="messages__contacts-item">
		  <a class="messages__contacts-tab<?php if($get_id == $val['id']): ?> messages__contacts-tab--active tabs__item tabs__item--active<?php endif; ?>" href="messages.php?id=<?=$val['id']?>">
			<div class="messages__avatar-wrapper">
			  <img class="messages__avatar" src="img/userpic-<?=$val['avatar']?>" alt="Аватар пользователя">
			</div>
			<div class="messages__info">
			  <span class="messages__contact-name">
				<?=$val['name']?>
			  </span>
			  <div class="messages__preview">
				<p class="messages__preview-text">
				  <?=short_text($val['message'], 20)?>
				</p>
				<time class="messages__preview-time" datetime="<?=datetime_format($val['dt_add'],3)?>">
				  <?=time_format($val['dt_add'])?>
				</time>
			  </div>
			</div>
		  </a>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>

	  </ul>
	</div>
	
	<div class="messages__chat">
	  <div class="messages__chat-wrapper">
		<ul class="messages__list tabs__content tabs__content--active">
			
		  <?php foreach($messages as $key => $val): ?>
		  
		  <li class="messages__item <?php if($val['sender_id'] == $user_id): ?> messages__item--my<?php endif; ?>">
			<div class="messages__info-wrapper">
			  <div class="messages__item-avatar">
				<a class="messages__author-link" href="#">
				  <img class="messages__avatar" src="img/userpic-<?=$val['avatar']?>" alt="Аватар пользователя">
				</a>
			  </div>
			  <div class="messages__item-info">
				<a class="messages__author" href="profile.php?id=<?=$val['sender_id']?>">
				  <?=$val['username']?>
				</a>
				<time class="messages__time" datetime="2019-05-01T14:40">
				  <?=datetime_relative($val['dt_add'])?> назад
				</time>
			  </div>
			</div>
			<p class="messages__text">
			  <?=$val['message']?>
			</p>
		  </li>
		  
		  <?php endforeach; ?>
		  
		</ul>
	  </div>
	  <?php if($get_id !== $user_id): ?>
	  <div class="comments">
		<form class="comments__form form" action="messages.php?id=<?=$get_id?>" method="post">
		  <div class="comments__my-avatar">
			<img class="comments__picture" src="img/userpic-medium.jpg" alt="Аватар пользователя">
		  </div>
		  <div class="form__input-section<?php if($errors === 1): ?> form__input-section--error<?php endif; ?>">
			<input type="hidden" name="id" value="<?=$get_id?>">
			<textarea class="comments__textarea form__textarea form__input" placeholder="Ваше сообщение" name="message"></textarea>
			<label class="visually-hidden">Ваше сообщение</label>
			<?php if($errors === 1): ?>
			<button class="form__error-button button" type="button">!</button>
			<div class="form__error-text">
			  <h3 class="form__error-title">Ошибка валидации</h3>
			  <p class="form__error-desc">Это поле обязательно к заполнению</p>
			</div>
			<?php endif; ?>
		  </div>
		  <button class="comments__submit button button--green" type="submit" name="submit">Отправить</button>
		</form>
	  </div>
	  <?php endif; ?>
	</div>
  </section>
</main>
