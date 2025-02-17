<?php
namespace Fdiengdoh\BlogApp\Models;

use Fdiengdoh\BlogApp\Database;
use PDO;

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createCategory($name, $description = null) {
        $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'name' => $name,
            'description' => $description
        ]);
    }

    public function getAllCategories() {
        $query = "SELECT * FROM categories ORDER BY created_at DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignPostToCategory($postId, $categoryId) {
        $query = "INSERT INTO post_categories (post_id, category_id) VALUES (:post_id, :category_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'post_id' => $postId,
            'category_id' => $categoryId
        ]);
    }

    public function removePostFromCategory($postId, $categoryId) {
        $query = "DELETE FROM post_categories WHERE post_id = :post_id AND category_id = :category_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'post_id' => $postId,
            'category_id' => $categoryId
        ]);
    }

    public function getPostsByCategory($categoryId) {
        $query = "SELECT p.* FROM posts p 
                  JOIN post_categories pc ON p.id = pc.post_id 
                  WHERE pc.category_id = :category_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
