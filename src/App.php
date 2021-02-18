<?php
declare(strict_types=1);

namespace MyApp;

class App
{
    public function start(string $token): void
    {
        [$uri] = explode('?', $_SERVER['REQUEST_URI']);
        [$controllerName, $actionName, $param] = explode('/', trim($uri, '/')); // TODO убрать $param

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
            $subdomain = 'testwathird';
            $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

            $data = [
                'client_id' => 'ea2a467f-22ee-41b8-9849-db112e5fca5d',
                'client_secret' => 'RIVV2D7OLOojEsxvFwbF1eTynYNATmCY1rEKqwJ8frDBSFaiuXvuzj9M5DRvlSPl',
                'grant_type' => 'authorization_code',
                'code' => 'def5020050676bcacba599ccf36a2e586a4c20debebd95802aaf80b3cb3dd059576c446fd2860e85d40828d0a80f35b7f2f23a70508f1acea3920085df28d1b52bc3730593cabde6d2dbf16dca6755f7487154319db7642e515f0ff5444d9caf5600d0967ef933c9d8d53b5271d1e6d511a360a8447c76660372d89bca636a7a6af8bbeaf16447fc4a2a7016a4fa6a3889415c9dbb3feba64afe1284770e15aa2061a63986d167877062ed1d2b791db508f25c5e56bd29684c2392a3a91853ffe321a4c9d2da543afb8564d74351d12a42cbe27384df06081105e72f0bcf09b7bd0e9cf1dcc4142aff2f9a520471aff6b6ad1b3cc1dfcabc58bc1181d2f950256108952f82f18aca964e3eaa483fa826cf15bbd17bcdbc32e71a2dc73732bc163287313f56d37197dc32c65ceb60daaeba93c45cdc303c445f3a294e8c142cf70fe8e9035e029d017432025377c476ab2ad2bd253f570c001e15c49832e3a2cb2d50801e767eb9b9c2585a972debfb97fee26671a18d219f7d0ff6e481e14dce7e02e05629573fea2c012cdbeb2f6587ec3311e4503dae4a12d44a799a62c8e1e68c7e4262577c918702dc0fec7aff089dd6e6e05e68807e141828f5',
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

            $fp = fopen("accessToken.txt", "w"); // file_put_contents
            fwrite($fp, $out);
            fclose($fp);
        }
        return json_decode((file_get_contents("accessToken.txt")), true)["access_token"];
//        $access_token = $response['access_token']; //Access токен

    }
}