<?php

namespace App\Database;

use App\Core\Application;
use Dotenv\Dotenv;
use PDO;
use PDOException;

class DB
{
    private string $dsn = 'mysql:host=%s;dbname=%s;';
    protected PDO $conn;
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(Application::$ROOTPATH);
        $dotenv->safeLoad();
        $this->dsn = sprintf($this->dsn, $_ENV['DB_HOST'], $_ENV['DB_DATABASE']);
        if (!$this->databaseExists()) {
            $this->initializeConnection();
        }
        $this->connetToDatabase();
        $this->createUserTable();
        $this->createCategoryTable();
        $this->createPriorityLevelsTable();
        $this->createTasksTable();
    }

    /** Checks whether the specified database exists.
     *Returns true if the database exists.
     *If the database is not found (error code 1049), it returns false.
     *For any other error, the application will terminate with an error message.
     */
    private function databaseExists()
    {
        try {
            $this->dsn = sprintf($this->dsn, $_ENV['DB_HOST'], $_ENV['DB_DATABASE']);
            $this->conn = new PDO($this->dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            return true;
        } catch (PDOException $pe) {
            if ($pe->getCode() === 1049) {
                return false;
            }
            die('Error checking database: ' . $pe->getMessage());
        }
    }
    /**
     * Attempts to connect to the specified database.
     * If the database does not exist (error code 1049), it calls initializeConnection to create the database.
     * After the database is created, it retries the connection.
     * For any other errors, the application will terminate with an error message.
     */
    private function connetToDatabase()
    {
        try {
            $this->conn = new PDO($this->dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $pe) {
            if ($pe->getCode() === 1049) {
                $this->initializeConnection();
                $this->connetToDatabase();
            }
            die('Error PDO Connection ' . $pe->getMessage());
        }
    }
    /** 
     * Creates the database if it does not exist.
     * First, it connects to MySQL without specifying a database name.
     * Then, it uses an SQL query to create the database.
     * If an error occurs during this process, the application will terminate with an error message.
     */
    private function initializeConnection()
    {
        $dsnWithoutDatabase  = sprintf('mysql:host=%s;', $_ENV['DB_HOST']);
        try {
            $this->conn = new PDO($dsnWithoutDatabase, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "CREATE DATABASE " . $_ENV['DB_DATABASE'];
            $this->conn->exec($query);
        } catch (PDOException $pe) {
            die('Error Creating Database: ' . $pe->getMessage());
        }
    }
    /**
     * Creates the 'users' table if it does not exist.
     * The table stores user information including username, email, and password.
     * Timestamps for 'created_at' and 'updated_at' are automatically set.
     */
    private function createUserTable()
    {
        if (!$this->tableExists('users')) {
            $query = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) DEFAULT NULL,
            google_id VARCHAR(255) UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        }
        return false;
    }
    /**
     * Creates the 'categories' table if it does not exist.
     * The table stores category information for tasks.
     * It includes a 'name' field and timestamps for 'created_at'.
     */
    private function createCategoryTable()
    {
        if (!$this->tableExists('categories')) {
            $query = "CREATE TABLE categories (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(255) NOT NULL,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        }
        return false;
    }
    /**
     * Creates the 'tasks' table if it does not exist.
     * The table stores task information such as title, description, due date, status, and priority level.
     * It also includes a foreign key constraint that references the 'priority_levels' table.
     */
    private function createTasksTable()
    {
        if (!$this->tableExists('tasks')) {
            $query = "CREATE TABLE tasks (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                due_date DATETIME DEFAULT NULL,
                status ENUM('pending', 'in progress', 'completed') DEFAULT 'pending',
                user_id INT(11) NOT NULL,
                priority_level_id INT(11) DEFAULT NULL,
                category_id INT(11) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (priority_level_id) REFERENCES priority_levels(id),
                FOREIGN KEY (category_id) REFERENCES categories(id));";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        }
        return false;
    }
    /**
     * Creates the 'priority_levels' table if it does not exist.
     * The table stores the priority levels of tasks (e.g., low, medium, high).
     * It includes 'name', 'description', and timestamps for 'created_at' and 'updated_at'.
     */
    private function createPriorityLevelsTable()
    {
        if (!$this->tableExists('priority_levels')) {
            $query = "CREATE TABLE priority_levels(
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        description TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return true;
        }
        return false;
    }

    /**
     * Checks if a table exists in the database by checking its name.
     * It uses the 'SHOW TABLES' SQL query to check the existence of the table.
     * If the table exists, it returns true, otherwise false.
     */
    private function tableExists($tablename)
    {
        $query = 'SHOW TABLES LIKE :tableName';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tableName', $tablename);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
