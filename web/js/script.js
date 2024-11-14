// Функция для переключения между экранами
function showSection(screenNumber) {
    // Скрываем все секции
    const sections = document.querySelectorAll('.form-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    // Показываем нужную секцию
    document.getElementById('screen' + screenNumber).classList.add('active');
    // Подсвечиваем активную кнопку
    const buttons = document.querySelectorAll('#form-navigation button');
    buttons.forEach(button => {
        button.classList.remove('active');
    });
    buttons[screenNumber - 1].classList.add('active');
}

// Функция для добавления дополнительных полей учредителей
function addFounder() {
    const additionalDiv = document.getElementById('additionalFounders');
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control mb-2';
    input.placeholder = 'Учредитель';
    additionalDiv.appendChild(input);
}

// Функция для отправки формы
function submitForm() {
    alert('Заявка отправлена!');
    // Здесь можно добавить логику отправки формы
}

// Функция для сохранения заявки в черновик
function saveDraft() {
    alert('Заявка сохранена в черновик!');
    // Здесь можно добавить логику сохранения формы в черновик
}