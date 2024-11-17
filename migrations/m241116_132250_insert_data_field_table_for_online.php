<?php

use yii\db\Migration;

/**
 * Class m241116_132250_insert_data_field_table_for_online
 */
class m241116_132250_insert_data_field_table_for_online extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $contestId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%contests}}')
            ->where(['name' => 'Онлайн-проекты'])
            ->scalar();

        if (!$contestId) {
            echo "Конкурс 'Онлайн-проекты' не найден.\n";
            return;
        }

        $sections = [];
        for ($i = 1; $i <= 6; $i++) {
            $sections[$i] = (new \yii\db\Query())
                ->select('id')
                ->from('{{%sections}}')
                ->where(['contest_id' => $contestId, 'name' => "Экран $i"])
                ->scalar();
        }

        $fieldsData = [
            $sections[1] => [
                ['name' => 'applicant_name', 'label' => 'Наименование соискателя', 'type' => 'text'],
                ['name' => 'project_name', 'label' => 'Название проекта', 'type' => 'text'],
                ['name' => 'priority_theme', 'label' => 'Приоритетная тематика', 'type' => 'select', 'options' => '["\u0422\u0435\u043c\u0430\u0442\u0438\u043a\u0430 1", "\u0422\u0435\u043c\u0430\u0442\u0438\u043a\u0430 2", "\u0422\u0435\u043c\u0430\u0442\u0438\u043a\u0430 3"]'],
            ],
            $sections[2] => [
                ['name' => 'full_applicant_name', 'label' => 'Полное наименование соискателя (в строгом соответствии со свидетельством о внесении записи в ЕГРЮЛ)', 'type' => 'text'],
                ['name' => 'full_project_name', 'label' => 'Полное название проекта', 'type' => 'text'],
                ['name' => 'project_summary', 'label' => 'Краткая аннотация проекта', 'type' => 'text'],
                ['name' => 'project_start_date', 'label' => 'Дата запуска онлайн-проекта', 'type' => 'text'],
                ['name' => 'project_cost', 'label' => 'Полная стоимость проекта', 'type' => 'text'],
                ['name' => 'requested_funding', 'label' => 'Сумма запрашиваемой финансовой поддержки', 'type' => 'text'],
            ],
            $sections[3] => [
                ['name' => 'applicants_name', 'label' => 'Наименование соискателя', 'type' => 'text'],
                ['name' => 'legal_status', 'label' => 'Организационно-правовой статус', 'type' => 'text'],
                ['name' => 'address', 'label' => 'Адрес организации', 'type' => 'text'],
                ['name' => 'founders', 'label' => 'Учредители', 'type' => 'multi'],
                ['name' => 'date_registr', 'label' => 'Дата регистрации ', 'type' => 'text'],
                ['name' => 'inn', 'label' => 'ИНН', 'type' => 'text'],
                ['name' => 'okved', 'label' => 'ОКВЭД', 'type' => 'text'],
                ['name' => 'kpp', 'label' => 'КПП', 'type' => 'text'],
                ['name' => 'ogrn', 'label' => 'ОГРН', 'type' => 'text'],
                ['name' => 'okpo', 'label' => 'ОКПО', 'type' => 'text'],
                ['name' => 'phone', 'label' => 'Телефон', 'type' => 'text'],
                ['name' => 'web', 'label' => 'Интернет-страница', 'type' => 'text'],
                ['name' => 'bank_name', 'label' => 'Название банка', 'type' => 'text'],
                ['name' => 'account_number', 'label' => 'Расчётный счёт', 'type' => 'text'],
                ['name' => 'correspondent_number', 'label' => 'Корреспондентский счёт', 'type' => 'text'],
                ['name' => 'bik', 'label' => 'БИК', 'type' => 'text'],
                ['name' => 'inn_kpp', 'label' => 'ИНН/КПП банка', 'type' => 'text'],
                ['name' => 'lastname', 'label' => 'Фамилия', 'type' => 'text'],
                ['name' => 'firstname', 'label' => 'Имя', 'type' => 'text'],
                ['name' => 'surname', 'label' => 'Отчество', 'type' => 'text'],
                ['name' => 'position', 'label' => 'Должность', 'type' => 'text'],
                ['name' => 'phone_contact', 'label' => 'Телефон контакт', 'type' => 'text'],
                ['name' => 'email', 'label' => 'Эл. почта', 'type' => 'text'],
                ['name' => 'fio_director', 'label' => 'ФИО руководителя организации', 'type' => 'text'],
                ['name' => 'fio_accountant', 'label' => 'ФИО бухгалтера организации', 'type' => 'text'],
            ],
            $sections[4] => [
                ['name' => 'charter_goal', 'label' => 'Уставная цель', 'type' => 'text'],
                ['name' => 'main_activities', 'label' => 'Основные сферы деятельности', 'type' => 'multi'],
                ['name' => 'main_projects', 'label' => 'Основные проекты соискателя по направлению конкурса', 'type' => 'multi'],
            ],
            $sections[5] => [
                ['name' => 'project_title', 'label' => 'Название проекта', 'type' => 'text'],
                ['name' => 'start_date', 'label' => 'Дата начала реализации', 'type' => 'text'],
                ['name' => 'finish_date', 'label' => 'Дата завершения реализации', 'type' => 'text'],
                ['name' => 'project_goal', 'label' => 'Цель проекта', 'type' => 'text'],
                ['name' => 'project_description_full', 'label' => 'Развёрнутое описание проекта', 'type' => 'text'],
                ['name' => 'education_value', 'label' => 'Просветительское значение проекта', 'type' => 'text'],
                ['name' => 'short_plan', 'label' => 'Краткий план реализации (мультиполе)', 'type' => 'multi'],
                ['name' => 'main_performers', 'label' => 'Информация об основных исполнителях проекта', 'type' => 'text'],
                ['name' => 'support_organisation', 'label' => 'Организации, принимающие участие в поддержке', 'type' => 'text'],
                ['name' => 'support_price', 'label' => 'Сумма поддержки за счет других источников финансирования ', 'type' => 'text'],
            ],
            $sections[6] => [
                ['name' => 'registration_certificate', 'label' => 'Свидетельство о государственной регистрации (ОГРН)', 'type' => 'file'],
                ['name' => 'tax_certificate', 'label' => 'Свидетельство о постановке на налоговый учет(ИНН)', 'type' => 'file'],
                ['name' => 'tax_certificate', 'label' => 'Устав ,а также все действующие изменения и дополнения к нему', 'type' => 'file'],
                ['name' => 'charter_amendments', 'label' => 'Свидетельство', 'type' => 'file'],
                ['name' => 'document_confirming', 'label' => 'Документ,подтверждающий полномочия лица на осуществление действий от имени ЮЛ', 'type' => 'file'],
                ['name' => 'project_description_file', 'label' => 'Описание проекта', 'type' => 'file'],
                ['name' => 'estimate', 'label' => 'Смета (заполненная и заверенная)', 'type' => 'file'],
                ['name' => 'experience_projects', 'label' => 'Опыт в реализации проектов подобного рода(предметные результаты деятельности)', 'type' => 'file'],
                ['name' => 'recomendate', 'label' => 'Рекомендация', 'type' => 'file'],
                ['name' => 'add_docs', 'label' => 'Дополнительные материалы', 'type' => 'file'],
                ['name' => 'guarantee', 'label' => 'Гарантийное письмо', 'type' => 'file'],
            ],
        ];

        foreach ($fieldsData as $sectionId => $fields) {
            $position = 1;
            foreach ($fields as $field) {
                $this->insert('{{%fields}}', array_merge($field, [
                    'section_id' => $sectionId,
                    'position' => $position,
                ]));
                $position++;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $contestId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%contests}}')
            ->where(['name' => 'Онлайн-проекты'])
            ->scalar();

        if (!$contestId) {
            echo "Конкурс 'Онлайн-проекты' не найден.\n";
            return;
        }

        $sectionIds = (new \yii\db\Query())
            ->select('id')
            ->from('{{%sections}}')
            ->where(['contest_id' => $contestId])
            ->andWhere(['in', 'name', [
                'Экран 1',
                'Экран 2',
                'Экран 3',
                'Экран 4',
                'Экран 5',
                'Экран 6'
            ]])
            ->column();

        if (!empty($sectionIds)) {
            $this->delete('{{%fields}}', ['section_id' => $sectionIds]);
        }
    }
}
