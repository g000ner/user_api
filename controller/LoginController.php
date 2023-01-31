<?php
require_once './model/User.php';
require_once './model/DataBase.php';
require_once 'Controller.php';

class LoginController extends Controller
{
    private $requestMethod;

    public function __construct($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    public function processRequest()
    {
        $response = null;
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->logIn();
                break;
            default:
                $response = $this->notFoundResponse();
        }
        header($response['status_code_header']);
        echo $response['body'];
    }

    private function logIn()
    {
        $input = (array) json_decode(file_get_contents('php://input', true));
        $db = new DataBase();
        $userModel = new User($db->getConnection());

        $result = $userModel->logIn($input);

        if ($result['status']) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
        } else {
            $response['status_code_header'] = 'HTTP/1.1 403 Forbidden';
        }

        $response['body'] = json_encode($result);
        return $response;
    }
}
