<?php

/** @var yii\web\View $this */
/** @var string $type */

use app\models\ApplicattionTypes;

$this->title = 'Новая заявка "'. ApplicattionTypes::$typeArr[$type].'"';
?>
<!-- Навигация между экранами -->
<div id="form-navigation" class="mb-4">
    <button class="btn btn-secondary" onclick="showSection(1)">Экран 1</button>
    <button class="btn btn-secondary" onclick="showSection(2)">Экран 2</button>
    <button class="btn btn-secondary" onclick="showSection(3)">Экран 3</button>
    <button class="btn btn-secondary" onclick="showSection(4)">Экран 4</button>
</div>

<!-- Экран 1 -->
<div id="screen1" class="form-section active">
    <h4>Экран 1</h4>
    <div class="mb-3">
        <label for="applicantName" class="form-label required">Наименование соискателя</label>
        <input type="text" class="form-control" id="applicantName" required>
    </div>
    <div class="mb-3">
        <label for="projectName" class="form-label required">Название проекта</label>
        <input type="text" class="form-control" id="projectName" required>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <button class="btn btn-secondary" onclick="showSection(2)">Далее</button>
            <button class="btn btn-secondary" style="display: none;">Назад</button>
        </div>
        <button class="btn btn-primary" onclick="saveDraft()">Сохранить</button>
    </div>
</div>

<!-- Экран 2 -->
<div id="screen2" class="form-section">
    <h4>Экран 2</h4>
    <div class="mb-3">
        <label for="fullApplicantName" class="form-label required">Полное наименование соискателя</label>
        <input type="text" class="form-control" id="fullApplicantName" required>
        <div class="info-note">В строгом соответствии с базой</div>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <button class="btn btn-secondary" onclick="showSection(1)">Назад</button>
            <button class="btn btn-secondary" onclick="showSection(3)">Далее</button>
        </div>
        <button class="btn btn-primary" onclick="saveDraft()">Сохранить</button>
    </div>
</div>

<!-- Экран 3 -->
<div id="screen3" class="form-section">
    <h4>Экран 3</h4>
    <div class="mb-3">
        <label for="founders" class="form-label required">Учредители</label>
        <input type="text" class="form-control" id="founders" required>
        <button type="button" class="btn btn-link" onclick="addFounder()">Добавить ещё</button>
        <div class="info-note">Добавляйте сколько хотите</div>
    </div>
    <div id="additionalFounders"></div>
    <div class="d-flex justify-content-between">
        <div>
            <button class="btn btn-secondary" onclick="showSection(2)">Назад</button>
            <button class="btn btn-secondary" onclick="showSection(4)">Далее</button>
        </div>
        <button class="btn btn-primary" onclick="saveDraft()">Сохранить</button>
    </div>
</div>

<!-- Экран 4 -->
<div id="screen4" class="form-section">
    <h4>Экран 4</h4>
    <div class="mb-3">
        <label for="phone" class="form-label required">Телефон</label>
        <input type="text" class="form-control" id="phone" required placeholder="+7 (___) ___ __ __">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label required">Электронная почта</label>
        <input type="email" class="form-control" id="email" required>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <button class="btn btn-secondary" onclick="showSection(3)">Назад</button>
            <button class="btn btn-secondary" onclick="submitForm()">Отправить</button>
        </div>
        <button class="btn btn-primary" onclick="saveDraft()">Сохранить</button>
    </div>
</div>