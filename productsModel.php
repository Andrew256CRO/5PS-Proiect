<?php
require_once 'db_connection.php';

class ProductsModel {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    public function getProducts($page, $limit) {
        try {
            $offset = ($page - 1) * $limit;
            $query = $this->con->prepare("SELECT * FROM products LIMIT :limit OFFSET :offset");
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getTotalProducts() {
        try {
            $query = $this->con->prepare("SELECT COUNT(*) as total FROM products");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getProductById($id) {
        try {
            $query = $this->con->prepare("SELECT * FROM products WHERE Id = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function searchProducts($searchTerm, $page, $limit) {
        try {
            $offset = ($page - 1) * $limit;
            $searchTerm = '%' . $searchTerm . '%';
            
            $query = $this->con->prepare("
                SELECT * FROM products 
                WHERE Name LIKE :search 
                   OR Description LIKE :search 
                LIMIT :limit OFFSET :offset
            ");
            
            $query->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getTotalSearchResults($searchTerm) {
        try {
            $searchTerm = '%' . $searchTerm . '%';
            
            $query = $this->con->prepare("
                SELECT COUNT(*) as total FROM products 
                WHERE Name LIKE :search 
                   OR Description LIKE :search
            ");
            
            $query->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function createProduct($data) {
        try {
            $query = $this->con->prepare("
                INSERT INTO products (Name, Description, Price, Image, Availability_date, In_stock) 
                VALUES (:name, :description, :price, :image, :availability_date, :in_stock)
            ");
            
            $query->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $query->bindValue(':description', $data['description'], PDO::PARAM_STR);
            $query->bindValue(':price', $data['price'], PDO::PARAM_STR);
            $query->bindValue(':image', $data['image'], PDO::PARAM_STR);
            $query->bindValue(':availability_date', $data['availability_date'], PDO::PARAM_STR);
            $query->bindValue(':in_stock', $data['in_stock'], PDO::PARAM_INT);
            
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateProduct($id, $data) {
        try {
            $query = $this->con->prepare("
                UPDATE products 
                SET Name = :name, 
                    Description = :description, 
                    Price = :price, 
                    Image = :image, 
                    Availability_date = :availability_date, 
                    In_stock = :in_stock 
                WHERE Id = :id
            ");
            
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $query->bindValue(':description', $data['description'], PDO::PARAM_STR);
            $query->bindValue(':price', $data['price'], PDO::PARAM_STR);
            $query->bindValue(':image', $data['image'], PDO::PARAM_STR);
            $query->bindValue(':availability_date', $data['availability_date'], PDO::PARAM_STR);
            $query->bindValue(':in_stock', $data['in_stock'], PDO::PARAM_INT);
            
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteProduct($id) {
        try {
            $query = $this->con->prepare("DELETE FROM products WHERE Id = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            return $query->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>