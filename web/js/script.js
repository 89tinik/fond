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

// Функция для добавления дополнительных полей
function addField(containerId, placeholder, dataField) {
    const container = document.getElementById(containerId);
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control mb-2';
    input.placeholder = placeholder;
    input.setAttribute('name', 'ApplicationsForm[fields][' + dataField + '][]');
    container.appendChild(input);
}

// Объект для хранения выбранных файлов по ID input
const selectedFiles = {};

// Обработчик для всех input типа file
document.addEventListener('change', function (event) {
    if (event.target.matches('input[type="file"].multifile')) {
        const input = event.target;
        const inputId = input.id;

        const newFiles = Array.from(input.files);

        if (!selectedFiles[inputId]) {
            selectedFiles[inputId] = [];
        }

        selectedFiles[inputId] = selectedFiles[inputId].concat(newFiles);

        renderFileList(inputId);

        input.value = '';
    }
});

// Функция рендеринга списка файлов с кнопками удаления
function renderFileList(inputId) {
    const fileListContainer = document.getElementById(`${inputId}-list`);
    fileListContainer.innerHTML = '';

    const files = selectedFiles[inputId] || [];

    files.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item d-flex align-items-center mb-1';
        fileItem.innerHTML = `
        <span class="me-2">${file.name}</span>
        <button type="button" class="btn-close" aria-label="Удалить" onclick="removeFile('${inputId}', ${index})"></button>
      `;
        fileListContainer.appendChild(fileItem); // Добавляем элемент в контейнер
    });
}


// Функция удаления незагруженного файла
function removeFile(inputId, index) {
    if (selectedFiles[inputId]) {
        selectedFiles[inputId].splice(index, 1);
        renderFileList(inputId);
    }
}

function saveDraft(sendB24) {
    if (sendB24) {
        let isValid = true;
        $('#application-form :input').removeClass('is-invalid');
        $('#application-form input.required-b24').each(function () {

            const value = $(this).val();
            const isEmpty = (this.type !== 'file' && value === '') || value === null || (this.type === 'number' && isNaN(value));

            if (isEmpty ||
                ($(this).attr('type') === 'file' &&
                    ($(this).siblings('.wrapper-uploaded').children('.file-item').length === 0 &&
                        $(this).siblings('.wrapper-no-loaded').children('.file-item').length === 0))) {
                $(this).addClass('is-invalid'); // Добавляем класс для подсветки
                isValid = false;
            }
        });

        if (isValid) {
            $('#applicationsform-sendb24').val('1');
        } else {
            $('#form-navigation button').eq($('#application-form input.is-invalid:first').closest('.form-section').attr('section-number')).trigger('click');
            return false;
        }
    }
    Cookies.set('tab', $('#form-navigation .active').index());
    const formData = new FormData();
    const currentUrl = window.location.href;
    const lastPath = currentUrl.split('/').pop().split('?')[0];
    let appId = 'new';
    const urlParams = new URLSearchParams(window.location.search);
    if (lastPath === 'update') {
        appId = urlParams.get('id');
    } else {
        formData.append('contest', urlParams.get('type'));
    }

    if (!appId) {
        console.error('Ошибка: параметр "app" отсутствует в URL');
        return;
    }

    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    formData.append('_csrf', csrfToken);
    formData.append('app', appId);

    const filesData = [];

    $.each(selectedFiles, (inputId, files) => {
        const inputElement = $(`#${inputId}`);
        if (inputElement.length) {
            const inputName = inputElement.attr('name');
            files.forEach(file => {
                filesData.push({
                    name: inputName,
                    file: file
                });
            });
        }
    });

    $('input[type="file"]:not(.multifile)').each(function () {
        const input = $(this);
        if (this.files.length > 0) {
            const inputName = input.attr('name');
            $.each(this.files, (_, file) => {
                filesData.push({
                    name: inputName,
                    file: file
                });
            });
        }
    });
    if (filesData.length) {
        $.each(filesData, (index, fileData) => {
            formData.append(`files[${index}][name]`, fileData.name);
            formData.append(`files[${index}][file]`, fileData.file);
        });

        $.ajax({
            url: '/application/upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.error) {
                    console.log('Ошибка:', data.error);
                }
                if (data.newUrl) {
                    $('#application-form').attr('action', data.newUrl);
                }
                $.each(data.files, function (index, file) {
                    $('#applicationsform-fields-' + file.fieldId + '-list').html(''); // Очистка списка для соответствующего поля
                    $('#uploaded-fields-' + file.fieldId + '-list').append('<div class="file-item d-flex align-items-center mb-1">\n' +
                        '                <a href="' + file.fullUploadDir + '" class="me-2" target="_blank">' + file.name + '</a>\n' +
                        '                <button type="button" class="btn-close" aria-label="Удалить" onclick="removeUploadedFile(this,\'' + file.idx + '\',\'' + appId + '\',\'' + file.fieldId + '\')"></button>\n' +
                        '            </div>')
                });
                console.log('Файлы успешно загружены:', data);
                $('#application-form').submit();
            },
            error: function (xhr) {
                console.error(`Ошибка: ${xhr.status} ${xhr.statusText}`);
            }
        });
    } else {
        $('#application-form').submit();
    }
}

function removeUploadedFile(button, idx, app, field) {
    $.ajax({
        url: '/application/delete-file',
        type: 'POST',
        data: {
            idx: idx,
            app: app,
            field: field
        },
        success: function (data) {
            $(button).closest('.file-item').remove();
            console.log('Файлы успешно удалены');
        },
        error: function (xhr) {
            console.error(`Ошибка: ${xhr.status} ${xhr.statusText}`);
        }
    });
}

$(function () {
    if (Cookies.get('tab')) {
        $('#form-navigation button').eq(Cookies.get('tab')).trigger('click');
        Cookies.remove('tab');
    }

    $('#applicationsform-companyid').on('change', function () {
        const companyId = $(this).val();
        if (companyId !== '') {
            $.ajax({
                url: '/application/get-company',
                type: 'POST',
                data: {
                    id: companyId
                },
                success: function (response) {
                    let data;
                    try {
                        data = typeof response === 'string' ? JSON.parse(response) : response;
                        if (typeof data.error === 'string') {
                            console.log('Ошибка :' + response);
                            return;
                        }
                    } catch (error) {
                        console.error('Ошибка парсинга JSON:', error);
                        return;
                    }

                    $.each(data, function (key, value) {
                        const inputField = $(`#applicationsform-fields-${key}`);
                        if (inputField.length) {
                            if (typeof value === 'object' && value !== null) {
                                const inputsContainer = inputField.closest('.wrapper-multi');
                                const addButton = inputsContainer.next('button');
                                let firstIteration = true;
                                inputsContainer.children(':not(:first-child)').remove();
                                $.each(value, function (k, val) {
                                    if (typeof val === 'object' && val !== null) {
                                        if (typeof val.VALUE === 'string') {
                                            val = val.VALUE;
                                        }
                                    }
                                    if (firstIteration) {
                                        inputField.val(val);
                                        firstIteration = false;
                                    } else {
                                        addButton.trigger('click');
                                        inputsContainer.children('input:last').val(val);
                                    }
                                });
                            } else {
                                inputField.val(value);
                            }
                        }
                    });

                    $('#application-form .company-field').attr('readonly', 'readonly').removeClass('required-b24');
                    console.log('Данные успешно обработаны:', data);
                },
                error: function (xhr) {
                    console.error(`Ошибка: ${xhr.status} ${xhr.statusText}`);
                }
            });
        } else {
            $('#application-form .company-field').removeAttr('readonly').addClass('required-b24').val('');
        }

    });
});