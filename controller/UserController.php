<?php
require_once './model/User.php';
require_once './model/DataBase.php';
require_once 'Controller.php';

class UserController extends Controller
{
    private $requestMethod = null;
    private $userId = null;

    public function __construct($requestMethod, $userId = 0)
    {
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
    }

    public function processRequest()
    {
        $response = null;
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->getUser($this->userId);
                } else {
                    $response = $this->getAllUsers();
                }
                break;
            case 'POST':
                $response = $this->createUser();
                break;
            case 'DELETE':
                if ($this->userId) {
                    $response = $this->deleteUser($this->userId);
                } else {
                    $response = $this->notFoundResponse();
                }
                break;
            case 'PUT':
                if ($this->userId) {
                    $response = $this->updateUser($this->userId);
                } else {
                    $response = $this->notFoundResponse();
                }
                break;
            default:
                $response = $this->notFoundResponse();
        }
        header($response['status_code_header']);
        echo $response['body'];
    }

    private function getAllUsers()
    {
        $db = new DataBase();
        $userModel = new User($db->getConnection());
        $result = $userModel->findAll();

        $response['body'] = json_encode($result);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        return $response;
    }


    private function getUser($id)
    {
        $db = new DataBase();
        $userModel = new User($db->getConnection());
        $result = $userModel->find($id);

        $response['body'] = json_encode($result);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        return $response;
    }

    private function createUser()
    {
        $input = (array) json_decode(file_get_contents('php://input', true));

        $db = new DataBase();
        $userModel = new User($db->getConnection());

        if (!$userModel->validate($input)) {
            return $this->unprocessableResponse();
        }

        if ($userModel->findByLogin($input['login'])) {
            return $this->alreadyExistsResponse();
        }

        $result = [];
        $result['created_user_id'] = $userModel->create($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function deleteUser($id)
    {
        $db = new DataBase();
        $userModel = new User($db->getConnection());

        if (!$userModel->find($id)) {
            return $this->notFoundResponse();
        }

        $result = $userModel->delete($id);
        $response['body'] = json_encode(array(
            'deleted_users_count' => $result
        ));
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        return $response;
    }

    private function updateUser($id)
    {
        $db = new DataBase();
        $userModel = new User($db->getConnection());

        if (!$userModel->find($id)) {
            return $this->notFoundResponse();
        }

        $input = (array) json_decode(file_get_contents('php://input', true));

        if (
            $userModel->findByLogin($input['login']) &&
            (int) ($userModel->findByLogin($input['login'])[0]['id']) !== $id
        ) {
            return $this->alreadyExistsResponse();
        }

        if (!(isset($input['login']) && isset($input['password']) && isset($input['old_password']))) {
            return $this->unprocessableResponse();
        }

        $result = $userModel->update($id, $input);
        $response['body'] = json_encode($result);
        if($result['status']) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
        } else {
            $response['status_code_header'] = 'HTTP/1.1 403 Forbidden';
        }
        return $response;
    }
}
