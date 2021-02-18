<?php


namespace MyApp;

class App
{
    public function start($token)
    {
        [$uri] = explode('?', $_SERVER['REQUEST_URI']);
        [$controllerName, $actionName, $param] = explode('/', trim($uri, '/'));

        if (empty($controllerName)) {
            $controllerName = 'index';
        }
        if (empty($actionName)) {
            $actionName = 'index';
        }
        $controllerClass = 'MyApp\Controllers\\' . ucfirst($controllerName) . 'Controller';
        $actionMethod = 'action' . ucfirst($actionName);

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass($token);
            if (method_exists($controller, $actionMethod)) {
                $controller->$actionMethod($param);
                return;
            }
        }
    }
    public function initToken()
    {
        if (!file_get_contents("accessToken.txt")) {
            $subdomain = 'testcustomersamocrmru';
            $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

            $data = [
                'client_id' => 'a434234b-fc07-4c28-8807-dd1547eb8c99',
                'client_secret' => 'uH8LDhYzH2sZOCFvxQxYCh2ZPoqeEhjSh3YpnDDnjXZIR5cEK5S3u74aT5jHDrIQ',
                'grant_type' => 'authorization_code',
                'code' => 'def5020060ed9ee44b03879ea17a0f69a7178088200c8249e87546ee0797593d5936266304dbfe0342c3d785cbcd2be4bd50074688bf9c1999180f73627af4da2b9b13d8e18f7699123eaed3a02f9bcf5a3bbe52d3e909148accdcc616c4ba283a4139fab9b90a97d51c56059a4ec687792805a21020ed68a74a51b1452d654e1105a5c3687ebd3b1d9bade4dd60ed729e69e017a2a778844a35c2de6db82874b606d225d6ae21668b6f513044bfa86181c5b659027ed07a1b753891567b5cf9203988bb92d4ac2fb15c2002043a56d2cd6b6a24530b1fb793b42c3691fdd719152bc4ec3c50ee5af8128378a4b8c815ef7ee07910c317fdbda10a48411bd8ca8d443f9131fa4f12bbd9fe74512cf01369991c017028daf95f1a1c50b0d8e0b2344f100d76efc9c7686c22aed2e7a05fdb80a67829ae8b82b85b07d1f7d585ef3af8ca12261b51de0a9aca9564286abbd75cc5a3c21e8e936ec21b516beb545c9ec2bee3b478f2facc70e742b0d2b623ef471dcc8c443841abc9167aa9716f771c3bcf135582107e27a45eac083183d7e2a1ed0e134a65ba079a750fdb9661918db9d255e26b754de713da1eeb6329e9346fc6346e45690fa530323d',
                'redirect_uri' => 'https://google.com',
            ];
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
            curl_setopt($curl, CURLOPT_URL, $link);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            $code = (int)$code;
            $errors = [
                400 => 'Bad request',
                401 => 'Unauthorized',
                403 => 'Forbidden',
                404 => 'Not found',
                500 => 'Internal server error',
                502 => 'Bad gateway',
                503 => 'Service unavailable',
            ];

            try {
                if ($code < 200 || $code > 204) {
                    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
                }
            } catch (\Exception $e) {
                die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
            }

            $fp = fopen("accessToken.txt", "w");
            fwrite($fp, $out);
            fclose($fp);
        }
        return json_decode((file_get_contents("accessToken.txt")), true)["access_token"];
//        $access_token = $response['access_token']; //Access токен

    }
}