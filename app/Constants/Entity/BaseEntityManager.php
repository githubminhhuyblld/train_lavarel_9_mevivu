<?php
namespace App\Constants\Entity;

trait BaseEntityManager
{
    abstract protected function getModelClass(): string;

    public function updateAttribute($id, $attribute, $value)
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::find($id);
        $model->$attribute = $value;
        $model->save();
    }
}
