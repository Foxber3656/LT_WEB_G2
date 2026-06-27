<?php
require_once __DIR__ . '/../database/connection.php';

class Category
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name)
    {
        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name]);
    }

    public function update($id, $name)
    {
        $sql = "UPDATE categories SET name = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $id]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function countProducts($categoryId)
    {
        $sql = "SELECT COUNT(*) AS total FROM products WHERE category_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$categoryId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
?>