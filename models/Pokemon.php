<?php

namespace app\models;
use yii\base\Model;

class Pokemon extends Model
{

    public $id;
    public $name;
    public $types;
    public $picture;
    public $weight;
    public $height;


    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['id', 'name',], 'required'],
            // email has to be a valid email address
            ['name', 'string'],
            // verifyCode needs to be entered correctl
        ];
    }

}