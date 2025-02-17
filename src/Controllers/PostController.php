<?php
namespace Fdiengdoh\BlogApp\Controllers;

use Fdiengdoh\BlogApp\Models\Post;

class PostController {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    public function create($title, $content, $authorId, $imagePath = null) {
        return $this->postModel->createPost($title, $content, $authorId, $imagePath);
    }

    public function publish($postId, $authorId) {
        return $this->postModel->publishPost($postId, $authorId);
    }

    public function update($postId, $authorId, $title, $content) {
        return $this->postModel->updatePost($postId, $authorId, $title, $content);
    }

    public function delete($postId, $authorId) {
        return $this->postModel->deletePost($postId, $authorId);
    }

    public function getByCategory($categoryId) {
        return $this->postModel->getPostByCategory($categoryId);
    }

    public function getSingle($postId) {
        return $this->postModel->getSinglePost($postId);
    }

    public function getAll() {
        return $this->postModel->getAllPosts();
    }
}
