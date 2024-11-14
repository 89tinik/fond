<?php

/** @var yii\web\View $this */

$this->title = 'Мои заявки';
?>
<!-- Секция Черновики -->
<h4 class="section-title">Черновики</h4>

<!-- Черновик заявки -->
<div class="application-block" onclick="location.href='#';">
    <div class="application-info">
        <h6>Археология</h6>
        <span>Дата изменения: 14.10.2024</span>
    </div>
    <button class="delete-btn">Удалить</button>
</div>

<!-- Пример ещё одного черновика заявки -->
<div class="application-block" onclick="location.href='#';">
    <div class="application-info">
        <h6>Видеоконтент</h6>
        <span>Дата изменения: 13.10.2024</span>
    </div>
    <button class="delete-btn">Удалить</button>
</div>

<!-- Секция Отправленные заявки -->
<h4 class="section-title">Отправленные заявки</h4>

<!-- Отправленная заявка -->
<div class="application-block" onclick="location.href='#';">
    <div class="application-info">
        <h6>Выставки</h6>
        <span>Дата отправки: 12.10.2024</span>
    </div>
</div>

<!-- Пример ещё одной отправленной заявки -->
<div class="application-block" onclick="location.href='#';">
    <div class="application-info">
        <h6>Мероприятия</h6>
        <span>Дата отправки: 10.10.2024</span>
    </div>
</div>