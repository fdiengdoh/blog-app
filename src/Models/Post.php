<?php
namespace Fdiengdoh\BlogApp\Models;

use Fdiengdoh\BlogApp\Database;
use PDO;

class Post {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createPost($title, $content, $authorId, $featuredImage = null) {
        $query = "INSERT INTO posts (title, content, author_id, featured_image) VALUES (:title, :content, :author_id, :featured_image)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId,
            'featured_image' => $featuredImage
        ]);
    }

    public function publishPost($postId, $authorId) {
        $query = "UPDATE posts SET published_at = NOW() WHERE id = :post_id AND author_id = :author_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['post_id' => $postId, 'author_id' => $authorId]);
    }

    public function updatePost($postId, $authorId, $title, $content) {
        $query = "UPDATE posts SET title = :title, content = :content WHERE id = :post_id AND author_id = :author_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'title' => $title,
            'content' => $content,
            'post_id' => $postId,
            'author_id' => $authorId
        ]);
    }

    public function deletePost($postId, $authorId) {
        $query = "DELETE FROM posts WHERE id = :post_id AND author_id = :author_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['post_id' => $postId, 'author_id' => $authorId]);
    }

    public function getPostByCategory($categoryId) {
        $query = "SELECT p.* FROM posts p 
                  JOIN post_categories pc ON p.id = pc.post_id 
                  WHERE pc.category_id = :category_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSinglePost($postId) {
        $query = "SELECT * FROM posts WHERE id = :post_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllPosts() {
        $query = "SELECT * FROM posts ORDER BY published_at DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
}
