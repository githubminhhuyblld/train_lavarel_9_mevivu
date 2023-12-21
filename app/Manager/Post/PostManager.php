<?php

namespace App\Manager\Post;

use App\Constants\Entity\BaseEntityManager;
use App\Constants\Enum\Status;
use App\Models\Post\Post;
use Illuminate\Database\Eloquent\Builder;

class PostManager
{
    use BaseEntityManager;
    protected function getModelClass(): string
    {
        return Post::class;
    }

    public function getAll(): Builder
    {
        return Post::query()
            ->where('status', '!=', Status::DELETED)
            ->orderBy('created_at', 'desc');
    }
    public function getByTitle(mixed $title): Builder
    {
        return Post::query()
            ->where('title', 'like', '%' . $title . '%')
            ->where('status', '!=', Status::DELETED)
            ->orderBy('created_at', 'desc');
    }

    public function getByExcerpt(mixed $excerpt): Builder
    {
        return Post::query()
            ->where('excerpt', 'like', '%' . $excerpt . '%')
            ->where('status', '!=', Status::DELETED)
            ->orderBy('created_at', 'desc');
    }

    public function createPost(array $data)
    {
        return Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'image' => $data['image'],
            'is_featured' => $data['is_featured'],
            'posted_at' => $data['publish'] === "CREATE_PUBLISH" ? now() : null,
        ]);
    }
    public function update($id, $data): void
    {
        $this->updateAttribute($id, 'title', $data->title);
        $this->updateAttribute($id, 'content', $data->content);
        $this->updateAttribute($id, 'excerpt', $data->excerpt);
        $this->updateAttribute($id, 'slug', $data->slug);
        $this->updateAttribute($id, 'image', $data->image);
        $this->updateAttribute($id, 'is_featured', $data->is_featured);
    }

}
