<?php

namespace app\models;

use Yii;

class B24Data
{
    const COMPANY_INN_FIELD = 'UF_CRM_COMPANY_1690882644287';
    const CONTACT_PHONE_FIELD = 'PHONE';
    const CONTACT_EMAIL_FIELD = 'EMAIL';
    private $dataUrl;

    public function __construct()
    {
        $this->dataUrl = [
            'new-deal' => 'https://historyrussia.bitrix24.ru/rest/' . $_ENV['B24_USER_ID'] . '/' . $_ENV['B24_HASH'] . '/crm.deal.add',
            'new-company' => 'https://historyrussia.bitrix24.ru/rest/' . $_ENV['B24_USER_ID'] . '/' . $_ENV['B24_HASH'] . '/crm.company.add',
            'new-contact' => 'https://historyrussia.bitrix24.ru/rest/' . $_ENV['B24_USER_ID'] . '/' . $_ENV['B24_HASH'] . '/crm.contact.add',
            'add-contact-to-company' => 'https://historyrussia.bitrix24.ru/rest/' . $_ENV['B24_USER_ID'] . '/' . $_ENV['B24_HASH'] . '/crm.company.contact.add',
            'get-company' => 'https://historyrussia.bitrix24.ru/rest/' . $_ENV['B24_USER_ID'] . '/' . $_ENV['B24_HASH'] . '/crm.company.list',
            'get-contact' => 'https://historyrussia.bitrix24.ru/rest/' . $_ENV['B24_USER_ID'] . '/' . $_ENV['B24_HASH'] . '/crm.contact.list',
            'get-company-info' => 'https://historyrussia.bitrix24.ru/rest/' . $_ENV['B24_USER_ID'] . '/' . $_ENV['B24_HASH'] . '/crm.company.get',
        ];
    }

    /**
     * @param array $data
     * @return int
     * @throws \yii\db\Exception
     */
    public function addContact($data)
    {

        $response = $this->sendRequest('new-contact', $data);
        $user = User::findOne(Yii::$app->user->id);
        $user->post = $data['fields']['POST'];
        $user->b24Id = $response['result'];
        $user->save();
        return $user->b24Id;
    }

    /**
     * @param array $data
     * @return int
     * @throws \yii\db\Exception
     */
    public function addCompany($data)
    {
        $responseCompany = $this->sendRequest('new-company', $data);
        $company = new Companies();
        $company->user_id = Yii::$app->user->id;
        $company->b24Id = $responseCompany['result'];
        $company->name = $data['fields']['TITLE'] . '(ИНН' . $data['fields'][self::COMPANY_INN_FIELD] . ')';
        $company->save();
        $this->sendRequest('add-contact-to-company', [
            'id' => $company->b24Id,
            'fields' => ['CONTACT_ID' => Yii::$app->user->identity->b24Id]
        ]);
        return $company->b24Id;
    }

    /**
     * @param array $data
     * @param $appFields
     * @param Applications $application
     * @return int|mixed
     * @throws \yii\db\Exception
     */
    public function checkCompany($data, $appFields, $application)
    {
        $dataGet = [
            'filter' => [self::COMPANY_INN_FIELD => $data['fields'][self::COMPANY_INN_FIELD]],
            'select' => ['ID', 'TITLE', 'UF_*']
        ];
        $response = $this->sendRequest('get-company', $dataGet);
        if (!empty($response['result'])) {
            $this->updateDraft($appFields, $response['result'][0]);
            $company = new Companies();
            $company->user_id = Yii::$app->user->id;
            $company->b24Id = $response['result'][0]['ID'];
            $company->name = $response['result'][0]['TITLE'] . '(ИНН' . $data['fields'][self::COMPANY_INN_FIELD] . ')';
            $company->save();
            $application->company_id = $company->id;
            $application->save();
            return $response['result'][0]['ID'];
        } else {
            return $this->addCompany($data);
        }
    }

    public function checkContact($data)
    {
        $user = User::findOne(Yii::$app->user->id);
        $dataGet = [
            'filter' => [
                self::CONTACT_EMAIL_FIELD => $user->email,
                self::CONTACT_PHONE_FIELD => $user->phone,
            ],
            'select' => ['ID', 'LAST_NAME', 'NAME', 'SECOND_NAME', 'PHONE', 'EMAIL', 'POST']
        ];
        $response = $this->sendRequest('get-contact', $dataGet);
        if (!empty($response['result'])) {
            $user->firstname = $response['result'][0]['NAME'];
            $user->lastname = $response['result'][0]['LAST_NAME'];
            $user->surname = $response['result'][0]['SECOND_NAME'];
            $user->post = $response['result'][0]['POST'];
            $user->b24Id = $response['result'][0]['ID'];
            $user->save();
            return $response['result'][0]['ID'];
        } else {
            return $this->addContact($data);
        }
    }


    public function getCompanyData($id)
    {
        $companyFields = Fields::getCompanyFieldsList();
        $companyInfo = $this->sendRequest('get-company-info', ['id' => $id]);
        if (array_key_exists('result', $companyInfo)) {
            $outputArr = [];
            foreach ($companyFields as $key => $id) {
                if (array_key_exists($key, $companyInfo['result'])) {
                    $outputArr[$id] = $companyInfo['result'][$key];
                } else {
                    $outputArr[$id] = '';
                }
            }
        } else {
            return json_encode($companyInfo);
        }
        return json_encode($outputArr);
    }

    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    public function sendRequest($urlKey, $data, $asArray = true)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->dataUrl[$urlKey]);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($curl);
        curl_close($curl);
        if ($asArray) {
            $response = json_decode($response, true);
        }
        return $response;
    }

    private function updateDraft($appFields, $data)
    {
        foreach ($appFields as $appField) {
            if (array_key_exists($appField->field->name, $data) && $data[$appField->field->name] != $appField->value) {
                $appField->value = $data[$appField->field->name];
                $appField->save();
            }
        }
    }
}