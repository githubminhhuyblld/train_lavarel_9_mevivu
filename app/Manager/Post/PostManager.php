<?php

namespace App\Manager\Post;

use App\Constants\Entity\BaseEntityManager;
use App\Constants\Enum\Status;
use App\Models\Post\Post;

class PostManager
{
    use BaseEntityManager;
    protected function getModelClass(): string
    {
        return Post::class;
    }
    public function getPosts()
    {
        $posts = Post::where('status', Status::ACTIVE)
            ->orderBy('created_at', 'desc')
            ->get();

        return $posts;
    }

    public function createPost(array $data)
    {
        return Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'image' => $data['image'],
            'posted_at' => $data['publish'] === "CREATE_PUBLISH" ? now() : null,
        ]);
    }
    public function searchPost($searchTerm)
    {
        $posts = Post::where(function ($query) use ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('id', $searchTerm);
        })
            ->whereNotIn('status', [Status::DELETED])
            ->get();

        return $posts;
    }
    public function update($id, $data)
    {
        $this->updateAttribute($id, 'title', $data->title);
        $this->updateAttribute($id, 'content', $data->content);
        $this->updateAttribute($id, 'excerpt', $data->excerpt);
        $this->updateAttribute($id, 'slug', $data->slug);
        $this->updateAttribute($id, 'image', $data->image);
    }

    public function removePost($id){
        $this->updateAttribute($id, 'status', Status::DELETED);
    }
}
