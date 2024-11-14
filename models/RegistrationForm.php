<?php

namespace app\models;

use yii\base\Model;
use app\models\User;
use Yii;

/**
 * UserRegistrationForm модель формы для регистрации пользователя
 */
class RegistrationForm extends Model
{
    public $firstname;
    public $surname;
    public $lastname;
    public $email;
    public $phone;
    public $password;
    public $password_repeat;
    public $polit;

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['firstname', 'surname', 'lastname', 'email', 'phone', 'password', 'password_repeat', 'polit'], 'required', 'message'=>'Поле обязательно для заполнения!'],
            [['firstname', 'surname', 'lastname'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Введите валидный e-mail'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email', 'message' => 'Этот email уже зарегистрирован.'],
            ['phone', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'phone', 'message' => 'Этот телефон уже зарегистрирован.'],
            ['phone', 'match', 'pattern' => '/^\+7 \d{3} \d{3} \d{2} \d{2}$/', 'message' => 'Неверный формат телефона.'],
            [['password'], 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли должны совпадать.'],
        ];
    }

    /**
     * Метки атрибутов
     */
    public function attributeLabels()
    {
        return [
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'surname' => 'Отчество',
            'email' => 'Email',
            'phone' => 'Телефон',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
            'polit' => 'Политика конфиденциальности',
        ];
    }

    /**
     * Регистрация пользователя
     *
     * @return User|null
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->setAttributes([
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash($this->password),
            'created_at' => time(),
        ]);

        return $user->save() ? $user : null;
    }
}
