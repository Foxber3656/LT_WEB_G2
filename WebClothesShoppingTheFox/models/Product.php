<?php
require_once __DIR__ . '/../database/connection.php';

class Product
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll($filters = [])
    {
        $sql = "
            SELECT 
                products.*,
                categories.name AS category_name
            FROM products
            LEFT JOIN categories ON products.category_id = categories.id
            WHERE 1 = 1
        ";

        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND products.name LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND products.category_id = ?";
            $params[] = $filters['category_id'];
        }

        if (!empty($filters['min_price'])) {
            $sql .= " AND products.price >= ?";
            $params[] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND products.price <= ?";
            $params[] = $filters['max_price'];
        }

        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $sql .= " ORDER BY products.price ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY products.price DESC";
                    break;
                case 'name_asc':
                    $sql .= " ORDER BY products.name ASC";
                    break;
                case 'newest':
                    $sql .= " ORDER BY products.created_at DESC";
                    break;
                default:
                    $sql .= " ORDER BY products.id DESC";
            }
        } else {
            $sql .= " ORDER BY products.id DESC";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "
            SELECT 
                products.*,
                categories.name AS category_name
            FROM products
            LEFT JOIN categories ON products.category_id = categories.id
            WHERE products.id = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name, $price, $image, $categoryId, $description)
    {
        $sql = "
            INSERT INTO products (name, price, image, category_id, description)
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $name,
            $price,
            $image,
            $categoryId,
            $description
        ]);
    }

    public function update($id, $name, $price, $image, $categoryId, $description)
    {
        $sql = "
            UPDATE products
            SET name = ?, price = ?, image = ?, category_id = ?, description = ?
            WHERE id = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $name,
            $price,
            $image,
            $categoryId,
            $description,
            $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>