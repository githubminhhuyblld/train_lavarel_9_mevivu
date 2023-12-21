<?php

namespace App\Constants\Entity;

use App\Constants\Enum\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BaseEntityManager
{
    abstract protected function getModelClass(): string;


    /**
     * Find a model instance by its ID.
     *
     * @param mixed $id The ID of the model instance to find.
     *
     * @return mixed|null The model instance if found, or null if not found.
     */
    public function findById(mixed $id): mixed
    {
        $modelClass = $this->getModelClass();
        return $modelClass::find($id);
    }

    /**
     * Update a specific attribute of a model.
     *
     * @param mixed $id The ID of the model instance to be updated.
     * @param string $attribute The name of the attribute to be updated.
     * @param mixed $value The new value to be assigned to the attribute.
     */
    public function updateAttribute(mixed $id, string $attribute, mixed $value): void
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::find($id);
        $model->$attribute = $value;
        $model->save();
    }

    /**
     * Create a new instance of a model.
     *
     * @param array $data Data used to create the new model instance.
     */
    public function create(array $data): void
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass;

        foreach ($data as $attribute => $value) {
            $model->$attribute = $value;
        }
        $model->save();
    }

    /**
     * Update an entity's status to 'DELETED'.
     *
     * @param  $id
     */
    public function remove($id): void
    {
        $this->updateAttribute($id, 'status', 'DELETED');
    }


    /**
     * Search for model instances based on an array of criteria.
     *
     * This method builds a query based on the provided search criteria. It checks if the model's table
     * has each column specified in the criteria, and if so, applies a 'like' filter for that column.
     *
     * @param array $criteria The search criteria as an associative array.
     *
     * @return Builder The query builder with applied search conditions.
     */
    public function searchQuery(array $criteria): Builder
    {
        $modelClass = $this->getModelClass();
        $query = $modelClass::query();
        $query->where('status', '=', Status::ACTIVE);

        foreach ($criteria as $field => $value) {
            if (!empty($value) && Schema::hasColumn((new $modelClass)->getTable(), $field)) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query;
    }



}
