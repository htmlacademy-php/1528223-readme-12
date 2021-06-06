<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
<?php foreach ($content_types as $type) : // вкладки типов контента ?>
    <?php $type_class = isset($type['class']) ? $type['class'] : null; ?>
    <?php $type_name = isset($type['name']) ? $type['name'] : null; ?>
                        <li class="adding-post__tabs-item filters__item">
                            <a class="adding-post__tabs-link
                                    filters__button
                                    filters__button--<?= $type_class ?>
                                    tabs__item  button
    <?php if ($type_class === $active_tab) : ?>
                                    filters__button--active tabs__item--active
    <?php endif; ?>">
                                    <svg class="filters__icon" width="22" height="18">
                                        <use xlink:href="#icon-filter-<?= $type_class ?>"></use>
                                    </svg>
                                    <span><?= $type_name ?></span>
                                </a>
                            </li>
<?php endforeach ?>
                    </ul>
                </div>

                <div class="adding-post__tab-content">

<?php foreach ($content_types as $type) : ?>
    <?php $type_class = isset($type['class']) ? $type['class'] : null; ?>
                    <section class="adding-post__<?= $type_class ?>
                            tabs__content
    <?php if ($type_class === $active_tab) : ?>
                            tabs__content--active
    <?php endif; ?>">
                        <h2 class="visually-hidden"></h2>
                        <form class="adding-post__form form"
                                action="add.php"
                                method="post"
                                enctype="multipart/form-data">
                            <input name="type" hidden value="<?= $type_class ?>">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper
                                            form__input-wrapper
    <?php if (isset($errors['header'])) : ?>
                                            form__input-section--error
    <?php endif; ?>">
    <?php $header_name = isset($fields_names['header']['input_name']) ? $fields_names['header']['input_name'] : null; ?>
    <?php $header_placeholder = isset($fields_names['header']['placeholder']) ? $fields_names['header']['placeholder'] : null; ?>
                                        <label class="adding-post__label
                                                form__label"
                                                for="<?= $type_class ?>-heading">
                                            <?= $header_name ?>
                                            <span class="form__input-required">*</span>
                                        </label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input
                                                    form__input"
                                                    id="<?= $type_class ?>-heading"
                                                    type="text"
                                                    name="header"
                                                    placeholder="<?= $header_placeholder ?>"
                                                    value="<?= getPostVal('header') ?>">
                                                <button class="form__error-button button" type="button">
                                                    !<span class="visually-hidden">Информация об ошибке</span>
                                                </button>
                                                <div class="form__error-text">
                                                    <h3 class="form__error-title">
        <?php $errors_head = isset($errors['header']['head']) ? $errors['header']['head'] : null; ?>
                                                        <?= $errors_head ?>
                                                    </h3>
                                                    <p class="form__error-desc">
        <?php $errors_message = isset($errors['header']['message']) ? $errors['header']['message'] : null; ?>
                                                        <?= $errors_message ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

    <?php if ($type_class !== 'text') : // поле content для всех типов кроме text  ?>
                                        <div class="adding-post__input-wrapper form__input-wrapper">
        <?php foreach ($content_names as $content_name => $content) : ?>
            <?php $content_input_name = isset($content['input_name']) ? $content['input_name'] : null; ?>
            <?php $content_placeholder = isset($content['placeholder']) ? $content['placeholder'] : null; ?>
            <?php $content_reqiured = isset($content['required']) ? $content['required'] : null; ?>
            <?php if ($content_name === $type_class) : ?>
                                            <label class="adding-post__label
                                                    form__label"
                                                    for="<?= $type_class ?>-url">
                                                <?= $content_input_name ?>
                                                <span class="form__input-required">*</span>
                                            </label>
                                            <div class="form__input-section
                <?php if (isset($errors['content'])) : ?>
                                                    form__input-section--error
                <?php endif; ?>">
                                                <input class="adding-post__input form__input"
                                                        id="<?= $type_class ?>-url" type="text" name="content"
                                                        placeholder="<?= $content_placeholder ?>"
                                                        value="<?= getPostVal('content') ?>">
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else : // поле content для типа text ?>
        <?php $content_input_name = isset($content_names['text']['input_name']) ? $content_names['text']['input_name'] : null; ?>
        <?php $content_placeholder = isset($content_names['text']['placeholder']) ? $content_names['text']['placeholder'] : null; ?>
        <?php $content_reqiured = isset($content_names['text']['reqiured']) ? $content_names['text']['reqiured'] : null; ?>
                                                <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                                    <label class="adding-post__label form__label" for="post-text">
                                                        <?= $content_input_name ?>
                                                        <span class="form__input-required">*</span>
                                                    </label>
                                                    <div class="form__input-section
        <?php if (isset($errors['content'])) : ?>
                                                            form__input-section--error
        <?php endif; ?>">
                                                        <textarea
                                                                class="adding-post__textarea form__textarea form__input"
                                                                name="content"
                                                                id="content"
                                                                placeholder="<?= $content_placeholder ?>"><?= getPostVal('content') ?></textarea>
    <?php endif; ?>
                                                        <button class="form__error-button
                                                                button"
                                                                type="button">
                                                            !
                                                            <span class="visually-hidden">
                                                                Информация об ошибке
                                                            </span>
                                                        </button>
                                                        <div class="form__error-text">
                                                    <h3 class="form__error-title">
        <?php $errors_head = (isset($errors['content']['head'])) ? $errors['content']['head'] : null; ?>
                                                        <?= $errors_head ?>
                                                    </h3>
                                                    <p class="form__error-desc">
        <?php $errors_message = (isset($errors['content']['message'])) ? $errors['content']['message'] : null; ?>
                                                        <?= $errors_message ?>
                                                    </p>
                                                        </div>
                                                    </div>
                                                </div>

    <?php if ($type_class === 'quote') : // если цитата, то поле автора ?>
                                                <div class="adding-post__textarea-wrapper form__input-wrapper">
    <?php $author_name = isset($fields_names['author']['input_name']) ? $fields_names['author']['input_name'] : null; ?>
    <?php $author_placeholder = isset($fields_names['author']['placeholder']) ? $fields_names['author']['placeholder'] : null; ?>
                                                    <label class="adding-post__label
                                                            form__label"
                                                            for="quote-author">
                                                        Автор
                                                        <span class="form__input-required">*</span>
                                                    </label>
                                                    <div class="form__input-section
        <?php if ($errors['author']) : ?>
                                                            form__input-section--error
        <?php endif; ?>
                                                    ">
                                                        <input class="adding-post__input
                                                                form__input"
                                                                id="quote-author"
                                                                type="text"
                                                                name="author"
                                                                value="<?= getPostVal('author') ?>"
                                                                placeholder="<?= $author_placeholder ?>">
                                                        <button class="form__error-button button" type="button">
                                                            !<span class="visually-hidden">
                                                                Информация об ошибке
                                                            </span>
                                                        </button>
                                                        <div class="form__error-text">
                                                    <h3 class="form__error-title">
        <?php $errors_head = (isset($errors['author']['head'])) ? $errors['author']['head'] : null; ?>
                                                        <?= $errors_head ?>
                                                    </h3>
                                                    <p class="form__error-desc">
        <?php $errors_message = (isset($errors['author']['message'])) ? $errors['author']['message'] : null; ?>
                                                        <?= $errors_message ?>
                                                    </p>
                                                        </div>
                                                    </div>
                                                </div>
    <?php endif; ?>
                                                <div class="adding-post__input-wrapper form__input-wrapper">
    <?php $tags_name = isset($fields_names['tags']['input_name']) ? $fields_names['tags']['input_name'] : null; ?>
    <?php $tags_placeholder = isset($fields_names['tags']['placeholder']) ? $fields_names['tags']['placeholder'] : null; ?>
                                                    <label class="adding-post__label form__label" for="link-tags">
                                                        <?= $tags_name ?>
                                                        <span class="form__input-required">*</span>
                                                    </label>
                                                    <div class="form__input-section
    <?php if (isset($errors['tags'])) : ?>
                                                            form__input-section--error
    <?php endif; ?>">
                                                        <input class="adding-post__input form__input"
                                                                id="link-tags"
                                                                type="text"
                                                                name="tags"
                                                                placeholder="<?= $tags_placeholder ?>"
                                                                value="<?= getPostVal('tags') ?>">
                                                        <button class="form__error-button button" type="button">
                                                            !
                                                            <span class="visually-hidden">
                                                                Информация об ошибке
                                                            </span>
                                                        </button>
                                                        <div class="form__error-text">
                                                            <h3 class="form__error-title">
    <?php $errors_head = isset($errors['tags']['head']) ? $errors['tags']['head'] : null; ?>
                                                                <?= $errors_head ?>
                                                            </h3>
                                                            <p class="form__error-desc">
    <?php $errors_message = isset($errors['tags']['message']) ? $errors['tags']['message'] : null; ?>
                                                                <?= $errors_message ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

    <?php if (count($errors)) : // блок с ошибками ?>
                                            <div class="form__invalid-block">
                                                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                                <ul class="form__invalid-list">
        <?php foreach ($errors as $error) : ?>
                                                    <li class="form__invalid-item">
            <?php $errors_subhead = isset($error['subhead']) ? $error['subhead'] : null; ?>
            <?php $errors_details = isset($error['details']) ? $error['details'] : null; ?>
                                                        <?= $errors_subhead ?>.
                                                        <?= $errors_details ?>
                                                    </li>
        <?php endforeach; ?>
                                                </ul>
                                            </div>
    <?php endif ?>

                                        </div>

                                        <?php if ($type_class === 'photo') : // внизу доп блок для заливки файла ?>
                                            <div class="adding-post__input-file-container
                                                form__input-container form__input-container--file">
                                                <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                                                    <div class="adding-post__file-zone adding-post__file-zone--photo
                                                    form__file-zone dropzone">
                                                        <input class="adding-post__input-file form__input-file"
                                                                id="userpic-file-photo"
                                                                type="file"
                                                                name="file">
                                                        <div class="form__file-zone-text">
                                                            <span>Перетащите фото сюда</span>
                                                        </div>
                                                    </div>
                                                    <button class="adding-post__input-file-button
                                                            form__input-file-button
                                                            form__input-file-button--photo button"
                                                            type="button">
                                                        <span>Выбрать фото</span>
                                                        <svg class="adding-post__attach-icon form__attach-icon"
                                                             width="10" height="20">
                                                            <use xlink:href="#icon-attach"></use>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="adding-post__file adding-post__file--photo
                                                        form__file dropzone-previews">
                                                </div>
                                            </div>

                                        <?php endif; ?>

                                        <div class="adding-post__buttons">
                                            <button class="adding-post__submit button button--main" type="submit"
                                                    name="submit">Опубликовать
                                            </button>
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
