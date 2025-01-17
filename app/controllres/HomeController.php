<?php

namespace App\Controllres;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;
use App\Core\SessionManager;
use App\Core\Validator;
use App\Models\Users;
use Dotenv\Dotenv;
use Exception;
use Google\Service\AlertCenter\User;
use Google\Service\Oauth2;
use Google_Client;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return $this->view('home');
    }
    public function test($id)
    {
        return $id;
    }

    public function signin()
    {
        $dotenv = Dotenv::createImmutable(Application::$ROOTPATH);
        $dotenv->safeLoad();
        $clinet = new Google_Client();
        $clinet->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $clinet->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $clinet->setRedirectUri("http://localhost:8000/sigin-google");
        $clinet->addScope('email');
        $clinet->addScope('profile');
        $authUrl = $clinet->createAuthUrl();
        $data = ['google' => $authUrl];
        return $this->view('signin', $data);
    }
    public function siginForm(Request $request)
    {
        if ($request->isPost()) {
            $fields = [
                'username' => 'required |alphanumeric',
                'email' => 'required | email ',
                'password' => 'required |secure',
                'confirmPassword' => 'required |same:password'
            ];
            $validator = new Validator;
            $errors = $validator->validation($request->body(), $fields);
            if (empty($errors)) {
                $username = $request->body()['username'];
                $email = $request->body()['email'];
                $password = $request->body()['password'];
                $data = ['username' => $username, 'email' => $email, 'password' => $password];

                $user = new Users;
                $user->insertToUsers($data);
                echo "Insert To databse";
                header('refresh:2;url=sigin');
                exit;
            }
            $this->redirectTo('sigin');
        }
    }
    public function siginWithGoogle()
    {

        $dotenv = Dotenv::createImmutable(Application::$ROOTPATH);
        $dotenv->safeLoad();
        $clinet = new Google_Client();
        $clinet->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $clinet->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $clinet->setRedirectUri("http://localhost:8000/sigin-google");
        $clinet->addScope('email');
        $clinet->addScope('profile');

        if (isset($_GET['code'])) {
            try {
                $token = $clinet->fetchAccessTokenWithAuthCode($_GET['code']);

                if (isset($token['access_token'])) {
                    $clinet->setAccessToken($token['access_token']);

                    $oauth2 = new Oauth2($clinet);
                    $userInfo = $oauth2->userinfo->get();
                    $email = $userInfo->email;
                    $name = $userInfo->name;
                    $google_id = $userInfo->id;
                    $user = new Users;
                    $existingUser = $user->getUsersByEmail($email);
                    if ($existingUser) {
                        $this->session_manager->start();
                        $this->session_manager->set('logged_in', 'Admin');
                        $this->session_manager->set('username', $existingUser[0]['username']);
                        $this->session_manager->set('user_id', $existingUser[0]['id']);
                        header('Location:/dashboard');
                        exit;
                    }
                    $data = ['username' => $name, 'email' => $email, 'google_id' => $google_id];
                    $user->insertToUsers($data);
                    echo "Insert to Database";
                    header('refresh:2;url=sigin');
                    exit;
                } else {
                    echo "<p>Error: Unable to fetch access token.</p>";
                    var_dump($token);
                    exit;
                }
            } catch (Exception $e) {
                echo "<p>Exception occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
                exit;
            }
        }
    }


    // signUp

    public function sigUp()
    {
        $dotenv = Dotenv::createImmutable(Application::$ROOTPATH);
        $dotenv->safeLoad();
        $clinet = new Google_Client();
        $clinet->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $clinet->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $clinet->setRedirectUri("http://localhost:8000/sigin-google");
        $clinet->addScope('email');
        $clinet->addScope('profile');
        $authUrl = $clinet->createAuthUrl();
        $data = ['google' => $authUrl];

        return $this->view('signUp', $data);
    }

    public function sigUpForm(Request $request)
    {
        $users = new Users;
        if ($request->isPost()) {
            $fields = [
                'email' => 'required | email ',
                'password' => 'required',
            ];
            $validator = new Validator;
            $errors = $validator->validation($request->body(), $fields);
            if (empty($errors)) {
                $email = $request->body()['email'];
                $password = $request->body()['password'];
                $data = ['email' => $email, 'password' => $password];
                $user = $users->login($email, $password);
                if ($user) {
                    $this->session_manager->start();
                    $this->session_manager->set('logged_in', 'Admin');
                    $this->session_manager->set('username', $user['username']);
                    $this->session_manager->set('user_id', $user['id']);

                    if (!$this->session_manager->regenerateId()) {
                        die('Failed to regenerate session ID.');
                    }
                    return $this->redirectTo('dashboard');
                } else {
                    SessionManager::set('errors', ['email' => 'Invalid email or password. Please try again']);
                }
            }
        }
        return $this->redirectTo('sigup');
    }


    public function store()
    {
        return "hi armin";
    }
}
