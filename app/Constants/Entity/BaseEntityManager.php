<?php

namespace App\Constants\Entity;

trait BaseEntityManager
{
    abstract protected function getModelClass(): string;

    public function updateAttribute($id, $attribute, $value): void
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::find($id);
        $model->$attribute = $value;
        $model->save();
    }
    public function create(array $data): void
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass;

        foreach ($data as $attribute => $value) {
            $model->$attribute = $value;
        }
        $model->save();
    }

}
