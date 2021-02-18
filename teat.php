<?php
$json = ' [
    "_total_items": 5,
    "_page": 1,
    "_page_count": 1,
    "_links":  [
        "self":  [
            "href": "https://testcustomersamocrmru.amocrm.ru/api/v4/companies/custom_fields?page=1&limit=50"
        }
    },
    "_embedded":  [
        "custom_fields": [
             [
                "id": 205631,
                "name": "Телефон",
                "type": "multitext",
                "account_id": 29283556,
                "code": "PHONE",
                "sort": 4,
                "is_api_only": false,
                "enums": [
                     [
                        "id": 104891,
                        "value": "WORK",
                        "sort": 2
                    },
                     [
                        "id": 104893,
                        "value": "WORKDD",
                        "sort": 4
                    },
                     [
                        "id": 104895,
                        "value": "MOB",
                        "sort": 6
                    },
                     [
                        "id": 104897,
                        "value": "FAX",
                        "sort": 8
                    },
                     [
                        "id": 104899,
                        "value": "HOME",
                        "sort": 10
                    },
                     [
                        "id": 104901,
                        "value": "OTHER",
                        "sort": 12
                    }
                ],
                "group_id": null,
                "required_statuses": [],
                "is_deletable": false,
                "is_predefined": true,
                "entity_type": "companies",
                "remind": null,
                "_links":  [
                    "self":  [
                        "href": "https://testcustomersamocrmru.amocrm.ru/api/v4/companies/custom_fields/205631"
                    }
                }
            },
             [
                "id": 205633,
                "name": "Email",
                "type": "multitext",
                "account_id": 29283556,
                "code": "EMAIL",
                "sort": 6,
                "is_api_only": false,
                "enums": [
                     [
                        "id": 104903,
                        "value": "WORK",
                        "sort": 2
                    },
                     [
                        "id": 104905,
                        "value": "PRIV",
                        "sort": 4
                    },
                     [
                        "id": 104907,
                        "value": "OTHER",
                        "sort": 6
                    }
                ],
                "group_id": null,
                "required_statuses": [],
                "is_deletable": false,
                "is_predefined": true,
                "entity_type": "companies",
                "remind": null,
                "_links":  [
                    "self":  [
                        "href": "https://testcustomersamocrmru.amocrm.ru/api/v4/companies/custom_fields/205633"
                    }
                }
            },
             [
                "id": 205635,
                "name": "Web",
                "type": "url",
                "account_id": 29283556,
                "code": "WEB",
                "sort": 503,
                "is_api_only": false,
                "enums": null,
                "group_id": null,
                "required_statuses": [],
                "is_deletable": false,
                "is_predefined": true,
                "entity_type": "companies",
                "remind": null,
                "_links":  [
                    "self":  [
                        "href": "https://testcustomersamocrmru.amocrm.ru/api/v4/companies/custom_fields/205635"
                    }
                }
            },
             [
                "id": 205637,
                "name": "Адрес",
                "type": "textarea",
                "account_id": 29283556,
                "code": "ADDRESS",
                "sort": 504,
                "is_api_only": false,
                "enums": null,
                "group_id": null,
                "required_statuses": [],
                "is_deletable": false,
                "is_predefined": true,
                "entity_type": "companies",
                "remind": null,
                "_links":  [
                    "self":  [
                        "href": "https://testcustomersamocrmru.amocrm.ru/api/v4/companies/custom_fields/205637"
                    }
                }
            },
             [
                "id": 399531,
                "name": "svdsv",
                "type": "text",
                "account_id": 29283556,
                "code": null,
                "sort": 506,
                "is_api_only": false,
                "enums": null,
                "group_id": null,
                "required_statuses": [],
                "is_deletable": true,
                "is_predefined": false,
                "entity_type": "companies",
                "remind": null,
                "_links":  [
                    "self":  [
                        "href": "https://testcustomersamocrmru.amocrm.ru/api/v4/companies/custom_fields/399531"
                    }
                }
            }
        ]
    }
}';
print_r(json_decode($json, true)['_embedded']["custom_fields"][5]['type']);


[
    [
        "entity_id"=> 167353,
        "note_type"=> "call_in",
        "params"=> [
        "uniq"=> "8f52d38a-5fb3-406d-93a3-a4832dc28f8b",
            "duration"=> 60,
            "source"=> "onlinePBX",
            "link"=> "https=>//example.com",
            "phone"=> "+79999999999"
        ]
    ],
    [
        "entity_id"=> 167353,
        "note_type"=> "call_out",
        "params"=> [
        "uniq"=> "8f52d38a-5fb3-406d-93a3-a4832dc28f8b",
            "duration"=> 60,
            "source"=> "onlinePBX",
            "link"=> "https=>//example.com",
            "phone"=> "+79999999999"
        ]
    ],
    [
        "entity_id"=> 167353,
        "note_type"=> "geolocation",
        "params"=> [
        "text"=> "Примечание с геолокацией",
            "address"=> "ул. Пушкина, дом Колотушкина, квартира Вольнова",
            "longitude"=> "53.714816",
            "latitude"=> "91.423146"
        ]
    ]
]


//                "task_type_id"=> 1,
                "text"=> $text,
                "complete_till"=> $date,
//                "entity_id"=> $id,
//                "entity_type"=> $entity,
//                "responsible_user_id" => 6734104


 [
    "is_completed"=> true,
    "result"=>  [
    "text"=> "Удалось связаться с клиентом"
    ]
]