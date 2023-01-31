<?php
require_once './model/Database.php';

class User
{
    private $db = null;

    function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = 'SELECT * FROM Users';
        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "SELECT * FROM Users WHERE id = ?";
        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function create(array $newUserData)
    {
        $newUserData['password'] = password_hash($newUserData['password'], PASSWORD_DEFAULT);
        $statement = "INSERT INTO Users (`login`, `password`) VALUES (:login, :password)";
        try {
            $statement = $this->db->prepare($statement);

            $this->db->beginTransaction();
            $statement->execute(array(
                ':login' => $newUserData['login'],
                ':password' => $newUserData['password'],
            ));
            $createdUserId = $this->db->lastInsertId();
            $this->db->commit();

            return $createdUserId;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function validate(array $data)
    {
        if ((!isset($data['login'])) || (!isset($data['password']))) {
            return false;
        } else {
            return true;
        }
    }

    public function findByLogin($login)
    {
        $statement = "SELECT * FROM Users WHERE login = ?";
        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($login));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "DELETE FROM Users WHERE id = ?";
        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function logIn(array $data)
    {
        try {
            $userByLogin = $this->findByLogin($data['login']) ? $this->findByLogin($data['login'])[0] : null;
            if (!$userByLogin) {
                return array(
                    'status' => false,
                    'error' => 'Incorrect login'
                );
            }

            if (password_verify($data['password'], $userByLogin['password'])) {
                return array(
                    'status' => true,
                    'user_id' => $userByLogin['id']
                );
            } else {
                return array(
                    'status' => false,
                    'error' => 'Incorrect password'
                );
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, array $userData)
    {
        $userById = $this->find($id);
        if(! password_verify($userData['old_password'], $userById['password'])) {
            return array(
                'status' => false,
                'error' => 'Incorrect old password'
            );
        }

        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $statement = "UPDATE Users SET login = :login, password = :password WHERE id = :id";
        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                ':login' => $userData['login'],
                ':password' => $userData['password'],
                ':id' => $id
            ));
            $updatedUser = $this->find($id);
            return Array(
                'status' => true,
                'updated_user'=> $updatedUser
            );
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
