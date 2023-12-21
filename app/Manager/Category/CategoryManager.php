<?php

namespace App\Manager\Category;

use App\Constants\Entity\BaseEntityManager;
use App\Constants\Enum\Status;
use App\Models\Category\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryManager
{
    use BaseEntityManager;

    protected function getModelClass(): string
    {
        return Category::class;
    }

    /**
     * Get a query builder for all categories except those with a status of 'deleted'.
     *
     * @return Builder
     */
    public function getActiveCategoriesQuery(): Builder
    {
        return Category::query()
            ->where('status', '!=', Status::DELETED)
            ->orderBy('created_at', 'desc');
    }


}
