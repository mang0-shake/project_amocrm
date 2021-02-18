<?php


namespace MyApp\Models;


class Model
{
    public $companyIds;
    public $contactIds;
    public $leadIds;
    public $customerIds;

    public $token;
    public $count;
    public $subdomain = "testwathird";

    public function __construct($token, $count = null)
    {
        $this->count = $count;
        $this->token = $token;
    }

    public function createCompanies()
    {
        $id = [];
        $array = [
            []
        ];
        for ($i = 0; $i < $this->count; $i++) {
            $out = self::makeRequestPOST("api/v4/companies", $array);
            $companyId = json_decode($out, true)["_embedded"]["companies"][0]['id'];
            array_push($id, $companyId);
            sleep(5); // TODO дублирование
        }

        $this->companyIds = $id;
        return $this->companyIds;
    }

    public function createContacts()
    {
        $id = [];
        $array = [
            [
                []
            ],
        ];
        for ($i = 0; $i < $this->count; $i++) {
            $out = self::makeRequestPOST("api/v4/contacts", $array);
            $contactId = json_decode($out, true)["_embedded"]["contacts"][0]['id'];
            array_push($id, $contactId);
            sleep(1);
        }
        $this->contactIds = $id;
        return $this->contactIds;
    }

    public function createCustomer()
    {
        $id = [];
        $array = [
            [
                name => 'Покупатель',
            ],
        ];
        for ($i = 0; $i < $this->count; $i++) {
            $out = self::makeRequestPOST("api/v4/customers", $array);
            $customerId = json_decode($out, true)["_embedded"]["customers"][0]['id'];
            array_push($id, $customerId);
            sleep(1);
        }
        $this->customerIds = $id;
        return $this->customerIds;
    }

    public function createDeal()
    {
        $id = [];
        $array = [
            [
                []
            ],
        ];
        for ($i = 0; $i < $this->count; $i++) {
            $out = self::makeRequestPOST("api/v4/leads", $array);
            $leadId = json_decode($out, true)["_embedded"]["leads"][0]['id'];
            array_push($id, $leadId);
            sleep(1);
        }
        $this->leadIds = $id;
        return $this->leadIds;
    }


    public function createRelations($companyIds, $contactsIds, $dealsIds, $customersIds)
    {
//        $this->creasdfteRelations();
        for ($i = 0; $i < count($dealsIds); $i++) {
            self::makeRequestPOST("api/v4/leads/$dealsIds[$i]/link", [
                [
                    to_entity_id => $companyIds[$i],
                    to_entity_type => "companies",
                ]
            ]);
            sleep(1);
        }
        // TODO привязать контакты к сделкам
        for ($j = 0; $j < count($customersIds); $j++) {
            self::makeRequestPOST("api/v4/customers/$customersIds[$j]/link", [
                [
                    to_entity_id => $contactsIds[$j],
                    to_entity_type => "contacts",
                ]
            ]);
            sleep(1);
        }
    }
//
//    protected function creasdfteRelations($companyIds, $contactsIds, $dealsIds, $customersIds)
//    {
//        // TODO сделать цикл универсальным
//        for ($i = 0; $i < count($dealsIds); $i++) {
//            self::makeRequestPOST("api/v4/leads/$dealsIds[$i]/link", [
//                [
//                    to_entity_id => $companyIds[$i],
//                    to_entity_type => "companies",
//                ]
//            ]);
//            sleep(1);
//        }
//    }

    public function addMultiselect()
    {
        $array = [
            [
                'name' => 'test multiselect',
                'type' => 'multiselect',
                'sort' => 510,
                'enums' => [
                    [
                        'value' => 'Значение 1',
                        'sort' => 1
                    ],
                    [
                        'value' => 'Значение 2',
                        'sort' => 2
                    ],
                    [
                        'value' => 'Значение 3',
                        'sort' => 3
                    ],
                    [
                        'value' => 'Значение 4',
                        'sort' => 4
                    ],
                    [
                        'value' => 'Значение 5',
                        'sort' => 5
                    ],
                    [
                        'value' => 'Значение 6',
                        'sort' => 6
                    ],
                    [
                        'value' => 'Значение 7',
                        'sort' => 7
                    ],
                    [
                        'value' => 'Значение 8',
                        'sort' => 8
                    ],
                    [
                        'value' => 'Значение 9',
                        'sort' => 9
                    ],
                    [
                        'value' => 'Значение 10',
                        'sort' => 10
                    ],
                ]
            ]
        ];
        return $this->makeRequestPOST("api/v4/contacts/custom_fields", $array);
    }

    public function addValuesToMultiselect($addedFieldsIds, $contactsIds)
    {
        //Формируем список id значений мультисписка из json строки
        $ids = [];
        $addMultiselectOutput = json_decode($addedFieldsIds, true);
        if (!isset($addMultiselectOutput['_embedded']['custom_fields'][0]['enums'])) { // TODO проверки
//            throw new \Exception('asdfdas');
        }
        foreach ($addMultiselectOutput['_embedded']['custom_fields'][0]['enums'] as $item) {
            array_push($ids, $item['id']);
        }
        //Получаем id самого списка
        $multiSelectId = $addMultiselectOutput['_embedded']['custom_fields'][0]['id'];
        foreach ($contactsIds as $item) {
            $array = [
                "custom_fields_values" => [
                    [
                        "field_id" => $multiSelectId,
                        "values" => [
                            [
                                "enum_id" => $ids[rand(0, count($ids) - 1)]
                            ]
                        ]
                    ],
                ]
            ];
            self::makeRequestPUTCH("api/v4/contacts/$item", $array);
        }
    }

    public function addTextField($id, $entity, $fieldName, $text)
    {
        $out = self::makeRequestGET("api/v4/$entity/custom_fields"); //Запрос на существование поля text
        $outputArray = json_decode($out, true)['_embedded']["custom_fields"];
        $fieldTypes = [];

        $textFieldId = [];
        foreach ($outputArray as $item) {
            if ($item['type'] === "text") {
                array_push($fieldTypes, $item['type']);
                array_push($textFieldId, $item['id']);
            };
        }
        if (in_array("text", $fieldTypes)) {
            $editValues = [
                "custom_fields_values" => [
                    [
                        "field_id" => $textFieldId[0],
                        "values" => [
                            [
                                "value" => $text
                            ]
                        ]
                    ],
                ]
            ];
            self::makeRequestPUTCH("api/v4/$entity/$id", $editValues);
        } else {
            $array = [
                [
                    'name' => $fieldName,
                    'type' => 'text',
                    'sort' => '510',
                ]
            ];
            $newId = json_decode(self::makeRequestPOST("api/v4/$entity/custom_fields", $array), true)['_embedded']["custom_fields"][0]["id"]; //создаем поле
            $newValue = [
                "custom_fields_values" => [
                    [
                        "field_id" => $newId,
                        "values" => [
                            [
                                "value" => $text
                            ]
                        ]
                    ]
                ]
            ];;
            self::makeRequestPUTCH("api/v4/$entity/$id", $newValue); //Добавляем значение в поле
        }
    }

    public function addNote($id, $entity, $noteText)
    {
        $array = [
            [
                "entity_id" => $id,
                "note_type" => "common",
                "params" => [
                    "text" => $noteText
                ]
            ]
        ];
        self::makeRequestPOST("api/v4/$entity/$id/notes", $array);
    }
    public function addCall($id, $entity, $callText)
    {
        $array = [
            [
                "entity_id" => $id,
                "note_type" => "call_in",
                "params" => [
                    "uniq"=> "8f52d38a-5fb3-406d-93a3-a4832dc28f8b",
                    "duration"=> 60,
                    "source"=> "onlinePBX",
                    "link"=> "https://example.com",
                    "phone"=> $callText
                ]
            ]
        ];
       self::makeRequestPOST("api/v4/$entity/$id/notes", $array);
    }
    public function addTask($id, $entity, $date, $text, $respUserId)
    {
        $array = [
            [
                "text"=> $text,
                "complete_till"=> $date,
                "responsible_user_id" => $respUserId,
                "entity_id" => $id,
                "entity_type"=> $entity,
            ]
        ];
        echo ($this->makeRequestPOST("api/v4/tasks", $array));
    }
    public function finishTask($id)
    {
        $array =  [
            "is_completed"=> true,
            "result"=>  [
                "text"=> "Выполнена"
            ]
        ];
        $this->makeRequestPUTCH("api/v4/tasks/$id", $array);
    }

    private function makeRequestPOST($api, $array)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/' . $api;
        $headers = [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ];
        $curl = curl_init($link);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($array));
        $out = curl_exec($curl);
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
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
//                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
            }
        } catch (\Exception $e) {
            die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }
        return $out;
    }

    protected const PATCH = 'PATCH';

    // TODO реквесты в одтельный класс
    private function makeRequest($api, $array, $method)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/' . $api;
        $headers = array(
            "Accept: application/json",
            'Authorization: Bearer ' . $this->token,
            "Content-Type: application/json",
        );

        // https://www.php.net/manual/ru/function.curl-setopt.php
        $curl = curl_init($link);
        curl_setopt($curl, CURLOPT_URL, $link);
        if ($method === self::PATCH) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        }

        curl_setopt($curl, CURLOPT_PATCH, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if ($method === 'POST' || $method === self::PATCH) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($array));
        }
        $out = curl_exec($curl);
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
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
            }
        } catch (\Exception $e) {
            die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }
        return $out;
    }

    private function makeRequestGET($api)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/' . $api;
        $headers = [
            'Authorization: Bearer ' . $this->token
        ];
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
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
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
            }
        } catch (\Exception $e) {
            die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }
        return $out;
    }

}