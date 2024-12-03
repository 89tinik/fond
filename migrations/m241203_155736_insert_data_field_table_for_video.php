<?php

use yii\db\Migration;

/**
 * Class m241203_155731_insert_data_section_table_for_event
 */
class m241203_155736_insert_data_field_table_for_video extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $contestId = (new \yii\db\Query())
            ->select('id')
            ->from('{{%contests}}')
            ->where(['name' => 'Видеоконтент'])
            ->scalar();

        if (!$contestId) {
            echo "Конкурс 'Видеоконтент' не найден.\n";
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
                ['name' => 'TITLE', 'label' => 'Наименование соискателя', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_DEAL_1690807644497', 'label' => 'Название проекта', 'type' => 'text'],
                ['name' => 'UF_CRM_1700121985', 'label' => 'Приоритетная тематика (селект)', 'type' => 'text'],
            ],
            $sections[2] => [
                ['name' => 'UF_CRM_DEAL_1690892312034', 'label' => 'Полное наименование соискателя (в строгом соответствии со свидетельством о внесении записи в ЕГРЮЛ)', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1690807668358', 'label' => 'Полное название проекта', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1690807980716', 'label' => 'Краткая аннотация проекта', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1691501347545', 'label' => 'Дата начала реализации проекта', 'type' => 'date'],
                ['name' => 'UF_CRM_DEAL_1691501381856', 'label' => 'Дата завершения реализации проекта', 'type' => 'date'],
                ['name' => 'UF_CRM_DEAL_1690892843324', 'label' => 'Телеканалы, проявившие заинтересованность в трансляции ожидаемого видеоконтента', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1690808081768', 'label' => 'Полная стоимость проекта', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1690808108539', 'label' => 'Сумма запрашиваемой финансовой поддержки', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1691475506749', 'label' => 'Сумма поддержки за счет других источников финансирования', 'type' => 'text'],
            ],
            $sections[3] => [
                ['name' => 'UF_CRM_COMPANY_1690808195404', 'label' => 'Наименование соискателя', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690808224897', 'label' => 'Организационно-правовой статус', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690808247798', 'label' => 'Адрес организации с индексом', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_1691496927', 'label' => 'Учредители (мультиполе)', 'type' => 'text', 'multi' => 1, 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690808457009', 'label' => 'Дата регистрации', 'type' => 'date', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690882644287', 'label' => 'ИНН', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690808957763', 'label' => 'ОКВЭД', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690808862652', 'label' => 'КПП', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690882684635', 'label' => 'ОГРН', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690808897038', 'label' => 'ОКПО', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'PHONE', 'label' => 'Телефон', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690810249995', 'label' => 'Интернет-страница', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690810274039', 'label' => 'Название банка', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_1702905041', 'label' => 'Расчётный счёт', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_1702905079', 'label' => 'Корреспондентский счёт', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1690810373429', 'label' => 'БИК', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_1702905108', 'label' => 'ИНН/КПП банка', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'LAST_NAME', 'label' => 'Фамилия', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'NAME', 'label' => 'Имя', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'SECOND_NAME', 'label' => 'Отчество', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'POST', 'label' => 'Должность', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'PHONE', 'label' => 'Телефон', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'EMAIL', 'label' => 'Эл. почта', 'type' => 'EMAIL', 'b24entity' => 'contact'],
                ['name' => 'UF_CRM_1691649147', 'label' => 'ФИО руководителя организации', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_1691649154', 'label' => 'ФИО бухгалтера организации', 'type' => 'text', 'b24entity' => 'company'],
            ],
            $sections[4] => [
                ['name' => 'UF_CRM_COMPANY_1690810969038', 'label' => 'Уставная цель', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1691474995938', 'label' => 'Основные сферы деят-ти (мультиполе)', 'type' => 'text', 'multi' => 1, 'b24entity' => 'company'],
                ['name' => 'UF_CRM_COMPANY_1691475122802', 'label' => 'Основные проекты соискателя (мультиполе)', 'type' => 'text', 'multi' => 1, 'b24entity' => 'company'],
            ],
            $sections[5] => [
                ['name' => 'UF_CRM_DEAL_1690898001907', 'label' => 'Название видеоконтента', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1690894640900', 'label' => 'Предполагаемые локации съёмок', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1690811382800', 'label' => 'Развёрнутое описание проекта', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1691475385417', 'label' => 'Краткий план реализации (мультиполе)', 'type' => 'text', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691475422495', 'label' => 'Информация об основных исполнителях проекта', 'type' => 'text'],
                ['name' => 'UF_CRM_DEAL_1691496275335', 'label' => 'Научные консультанты проекта (мультиполе)', 'type' => 'text', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691475546647', 'label' => 'Организации, принимающие участие в поддержке', 'type' => 'text'],
            ],
            $sections[6] => [
                ['name' => 'UF_CRM_DEAL_1691729934958', 'label' => 'Свидетельство о государственной регистрации (ОГРН)', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691729955790', 'label' => 'Свидетельство о постановке на налоговый учет(ИНН)', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691729975175', 'label' => 'Устав, а также все действующие изменения и дополнения к нему', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691729999544', 'label' => 'Документ, подтверждающий полномочия лица на осуществление действий от имени ЮЛ', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730092064', 'label' => 'Описание проекта', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730136621', 'label' => 'Смета (заполненная и заверенная)', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730185761', 'label' => 'Опыт в реализации проектов подобного рода (предметные результаты деятельности)', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730205652', 'label' => 'Рекомендация', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730232885', 'label' => 'Дополнительные материалы', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730271713', 'label' => 'Гарантийное письмо', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730423172', 'label' => 'Синопсис и сценарий фильма', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730495160', 'label' => 'Краткая режиссерская экспликация, подписанная режиссером', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730514247', 'label' => 'Биография и фильмография режиссера', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730541239', 'label' => 'Заключение эксперта', 'type' => 'file', 'multi' => 1],
                ['name' => 'UF_CRM_DEAL_1691730619303', 'label' => 'Гарантийное письмо телеканала', 'type' => 'file', 'multi' => 1],
            ]
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
            ->where(['name' => 'Видеоконтент'])
            ->scalar();

        if (!$contestId) {
            echo "Конкурс 'Видеоконтент' не найден.\n";
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
