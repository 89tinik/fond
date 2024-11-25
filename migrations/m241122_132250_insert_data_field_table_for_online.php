<?php

use yii\db\Migration;

/**
 * Class m241116_132250_insert_data_field_table_for_online
 */
class m241122_132250_insert_data_field_table_for_online extends Migration
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
                ['name' => 'title', 'label' => 'Наименование соискателя', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_deal_1690807644497', 'label' => 'Название проекта', 'type' => 'text'],
                ['name' => 'uf_crm_1700121985', 'label' => 'Приоритетная тематика', 'type' => 'select', 'options' => '["\u0422\u0435\u043c\u0430\u0442\u0438\u043a\u0430 1", "\u0422\u0435\u043c\u0430\u0442\u0438\u043a\u0430 2", "\u0422\u0435\u043c\u0430\u0442\u0438\u043a\u0430 3"]'],
            ],
            $sections[2] => [
                ['name' => 'uf_crm_deal_1690892312034', 'label' => 'Полное наименование соискателя (в строгом соответствии со свидетельством о внесении записи в ЕГРЮЛ)', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690807668358', 'label' => 'Полное название проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690807980716', 'label' => 'Краткая аннотация проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690977065825', 'label' => 'Дата запуска онлайн-проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690808081768', 'label' => 'Полная стоимость проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690808108539', 'label' => 'Сумма запрашиваемой финансовой поддержки', 'type' => 'text'],
            ],
            $sections[3] => [
                ['name' => 'uf_crm_company_1690808195404', 'label' => 'Наименование соискателя', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690808224897', 'label' => 'Организационно-правовой статус', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690808247798', 'label' => 'Адрес организации', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_1691496927', 'label' => 'Учредители', 'type' =>'text', 'multi'=>1, 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690808457009', 'label' => 'Дата регистрации ', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690882644287', 'label' => 'ИНН', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690808957763', 'label' => 'ОКВЭД', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690808862652', 'label' => 'КПП', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690882684635', 'label' => 'ОГРН', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690808897038', 'label' => 'ОКПО', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'Phone', 'label' => 'Телефон', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690810249995', 'label' => 'Интернет-страница', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690810274039', 'label' => 'Название банка', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_1702905041', 'label' => 'Расчётный счёт', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_1702905079', 'label' => 'Корреспондентский счёт', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1690810373429', 'label' => 'БИК', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_1702905108', 'label' => 'ИНН/КПП банка', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'LastName', 'label' => 'Фамилия', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'Name', 'label' => 'Имя', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'SecondName', 'label' => 'Отчество', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'Post', 'label' => 'Должность', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'Phone', 'label' => 'Телефон контакт', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'Email', 'label' => 'Эл. почта', 'type' => 'text', 'b24entity' => 'contact'],
                ['name' => 'uf_crm_1691649147', 'label' => 'ФИО руководителя организации', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_1691649154', 'label' => 'ФИО бухгалтера организации', 'type' => 'text', 'b24entity' => 'company'],
            ],
            $sections[4] => [
                ['name' => 'uf_crm_company_1690810969038', 'label' => 'Уставная цель', 'type' => 'text', 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1691474995938', 'label' => 'Основные сферы деятельности', 'type' =>'text', 'multi'=>1, 'b24entity' => 'company'],
                ['name' => 'uf_crm_company_1691475122802', 'label' => 'Основные проекты соискателя по направлению конкурса', 'type' =>'text', 'multi'=>1, 'b24entity' => 'company'],
            ],
            $sections[5] => [
                ['name' => 'uf_crm_deal_1690811272652', 'label' => 'Название проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1691501347545', 'label' => 'Дата начала реализации', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690977124411', 'label' => 'Дата завершения реализации', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690811362177', 'label' => 'Цель проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690811382800', 'label' => 'Развёрнутое описание проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1690811452042', 'label' => 'Просветительское значение проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1691475385417', 'label' => 'Краткий план реализации (мультиполе)', 'type' =>'text', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691475422495', 'label' => 'Информация об основных исполнителях проекта', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1691475546647', 'label' => 'Организации, принимающие участие в поддержке', 'type' => 'text'],
                ['name' => 'uf_crm_deal_1691475506749', 'label' => 'Сумма поддержки за счет других источников финансирования ', 'type' => 'text'],
            ],
            $sections[6] => [
                ['name' => 'uf_crm_deal_1691729934958', 'label' => 'Свидетельство о государственной регистрации (ОГРН)', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691729955790', 'label' => 'Свидетельство о постановке на налоговый учет(ИНН)', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691729975175', 'label' => 'Устав ,а также все действующие изменения и дополнения к нему', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691729999544', 'label' => 'Документ,подтверждающий полномочия лица на осуществление действий от имени ЮЛ', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691730092064', 'label' => 'Описание проекта', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691730136621', 'label' => 'Смета (заполненная и заверенная)', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691730185761', 'label' => 'Опыт в реализации проектов подобного рода(предметные результаты деятельности)', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691730205652', 'label' => 'Рекомендация', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691730232885', 'label' => 'Дополнительные материалы', 'type' => 'file', 'multi'=>1],
                ['name' => 'uf_crm_deal_1691730271713', 'label' => 'Гарантийное письмо', 'type' => 'file', 'multi'=>1],
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