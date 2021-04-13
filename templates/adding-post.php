<main class="page__main page__main--adding-post">
  <div class="page__main-section">
	<div class="container">
	  <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
	</div>
	<div class="adding-post container">
	  <div class="adding-post__tabs-wrapper tabs">
		<div class="adding-post__tabs filters">
		  <ul class="adding-post__tabs-list filters__list tabs__list">
			<?php foreach($types as $key => $val): ?>
				<li class="adding-post__tabs-item filters__item">
			    <a class="adding-post__tabs-link filters__button filters__button--<?=$val['class']?> tabs__item  button <?php if($val['class'] == $active):?> filters__button--active tabs__item--active<?php endif;?>">
				  <svg class="filters__icon" width="22" height="18">
				    <use xlink:href="#icon-filter-<?=$val['class']?>"></use>
				  </svg>
				  <span><?=$val['name']?></span>
			    </a>
			  </li>
			<?php endforeach ?>
			
		  </ul>
		</div>
		
		<div class="adding-post__tab-content">
		
		  <?php foreach($types as $key => $val): ?>
			<section class="adding-post__<?=$val['class']?> tabs__content <?php if($val['class'] == $active):?>tabs__content--active<?php endif;?>">
			<h2 class="visually-hidden">Форма добавления ссылки</h2>
			<form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
				
			  <input name="type" hidden value="<?=$val['class']?>">
			  <div class="form__text-inputs-wrapper">
				<div class="form__text-inputs">
				  <div class="adding-post__input-wrapper form__input-wrapper <?php if($errors['heading']): ?>form__input-section--error<?php endif; ?>">
					<label class="adding-post__label form__label" for="<?=$val['class']?>-heading">Заголовок <span class="form__input-required">*</span></label>
					<div class="form__input-section">
					  <input class="adding-post__input form__input" id="<?=$val['class']?>-heading" type="text" name="heading" placeholder="Введите заголовок" value="<?=getPostVal('heading')?>">
					  <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
					  <div class="form__error-text">
					    <h3 class="form__error-title"><?=errors_content($errors, 'heading', 'subhead')?></h3>
					    <p class="form__error-desc"><?=errors_content($errors, 'heading', 'submes')?></p>
					  </div>
					</div>
				  </div>
				  
				  <?php if ($val['class'] !== 'text'): // поле content ?>
				  <div class="adding-post__input-wrapper form__input-wrapper">
				  <?php foreach($field_names as $num => $cont): ?>
					<?php if($cont['type'] == $val['class']): ?>
					<label class="adding-post__label form__label" for="<?=$val['class']?>-url"><?=$cont['input_name']?> <?php if($cont['required']): ?><span class="form__input-required">*</span><?endif;?></label>
					<div class="form__input-section <?php if($errors['content']): ?>form__input-section--error<?php endif; ?>">
					  <input class="adding-post__input form__input" id="<?=$val['class']?>-url" type="text" name="content" placeholder="<?=$cont['placeholder']?>" value="<?=getPostVal('content')?>">
				  <?php endif; ?>
				  <?php endforeach; ?>
				  <?php else: ?>
				  <div class="adding-post__textarea-wrapper form__textarea-wrapper">
					<label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
					<div class="form__input-section <?php if($errors['content']): ?>form__input-section--error<?php endif; ?>">
					  <textarea class="adding-post__textarea form__textarea form__input" name="content" id="content" placeholder="Введите текст публикации"><?=getPostVal('content')?></textarea>
				  <?php endif; ?>
				  
					  <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
					  <div class="form__error-text">
					    <h3 class="form__error-title"><?=errors_content($errors, 'content', 'subhead')?></h3>
					    <p class="form__error-desc"><?=errors_content($errors, 'content', 'submes')?></p>
					  </div>
					</div>
				  </div>
				  
				  <?php if ($val['class'] == 'quote'): // если тип quote, то обязательное поле author ?>
				  <div class="adding-post__textarea-wrapper form__input-wrapper">
					<label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
					<div class="form__input-section <?php if($errors['content']): ?>form__input-section--error<?php endif; ?>">
					  <input class="adding-post__input form__input" id="quote-author" type="text" name="author" value="<?=getPostVal('author')?>">
					  <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
					  <div class="form__error-text">
					    <h3 class="form__error-title"><?=errors_content($errors, 'author', 'subhead')?></h3>
					    <p class="form__error-desc"><?=errors_content($errors, 'author', 'submes')?></p>
					  </div>
					</div>
				  </div>
				  <?php endif; ?>
				  
				  <div class="adding-post__input-wrapper form__input-wrapper">
					<label class="adding-post__label form__label" for="link-tags">Теги <span class="form__input-required">*</span></label>
					<div class="form__input-section <?php if($errors['tags']): ?>form__input-section--error<?php endif; ?>">
					  <input class="adding-post__input form__input" id="link-tags" type="text" name="tags" placeholder="Введите теги" value="<?=getPostVal('tags')?>">
					  <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
					  <div class="form__error-text">
					    <h3 class="form__error-title"><?=errors_content($errors, 'tags', 'subhead')?></h3>
					    <p class="form__error-desc"><?=errors_content($errors, 'tags', 'submes')?></p>
					  </div>
					</div>
				  </div>
				</div>
				
				<?php if(count($errors)): // блок с ошибками ?>
				<div class="form__invalid-block">
				  <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
				  <ul class="form__invalid-list">
					<?php foreach($errors as $key => $err_content): ?>
					  <li class="form__invalid-item">
						<?=$err_content['head']?>. <?=$err_content['message']?>
					  </li>
					<? endforeach; ?>
				  </ul>
				</div>
				<?php endif ?>
				
			  </div>
			  
			  <?php if ($val['class'] == 'photo'): // если тип photo, то внизу дополнительный блок для заливки файла ?>
				  
			  <div class="adding-post__input-file-container form__input-container form__input-container--file">
				<div class="adding-post__input-file-wrapper form__input-file-wrapper">
				  <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
					<input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="file" title=" ">
					<div class="form__file-zone-text">
					  <span>Перетащите фото сюда</span>
					</div>
				  </div>
				  <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
					<span>Выбрать фото</span>
					<svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
					  <use xlink:href="#icon-attach"></use>
					</svg>
				  </button>
				</div>
				<div class="adding-post__file adding-post__file--photo form__file dropzone-previews">
				</div>
			  </div>
			  
			  <?php endif; ?>
			  
			  <div class="adding-post__buttons">
				<button class="adding-post__submit button button--main" type="submit" name="submit">Опубликовать</button>
				<a class="adding-post__close" href="#">Закрыть</a>
			  </div>
			</form>
		    </section>
		  <?php endforeach; ?>
		  
		</div>
	  </div>
	</div>
  </div>
</main>
