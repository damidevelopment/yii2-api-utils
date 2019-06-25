<?php

namespace damidevelopment\apiutils;

use yii\base\Model;
use yii\db\ActiveRecordInterface;

/**
 * @Author: Jakub Hrášek
 * @Date:   2018-06-21 13:49:54
 */
class Resource extends Model
{

    /**
     * @var Model Model holding data, that is wrapped by this Resource
     */
    protected $_model;

    public function __construct(array $config = [])
    {
        $class = static::getActiveRecordClass();
        $this->_model = new $class();
        parent::__construct($config);
    }

    /** Set Model
     * @param Model $model
     * @return self
     */
    public function setModel(?Model $model): self
    {
        $this->_model = $model;
        return $this;
    }

    // TODO: move formatters to its own helper class

    /**
     * @param  mixed $dateValue
     * @return string formatted date
     */
    protected static function formatDate($dateValue)
    {
        return $dateValue instanceof \DateTimeInterface
            ? $dateValue->format('Y-m-d H:i:s')
            : $dateValue;
    }

    /**
     * @return string Active Record class that this resource is mainly wrapping
     */
    public static function getActiveRecordClass(): string
    {
        return ActiveRecord::class;
    }

    /** Loads data to resource (and nested models)
     * @param array $data Data to load
     */
    public function loadResource(array $data)
    {
        $forbiddenProperties = ['model'];
        foreach ($data as $key => $value) {
            if ($this->canSetProperty($key) && !in_array($key, $forbiddenProperties)) {
                $this->$key = $value;
            }
        }
    }

    /** Validates resources (and nested models)
     * @return bool If validation succeed
     */
    public function validateResource(): bool
    {
        $check = $this->_model->validate();
        $this->addErrors($this->_model->errors);
        return $check;
    }

    /** Saves resource (and nested models)
     * @return bool If resource has been saved
     */
    public function saveResource(): bool
    {
        if ($this->_model instanceof ActiveRecordInterface) {
            $check = $this->_model->save();
            if ($check) {
                $this->_model->refresh();
            }
            return $check;
        }
        return false;
    }
}