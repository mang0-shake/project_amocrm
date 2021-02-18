<?php

namespace MyApp\Controllers;

use MyApp\Models\Model;

class IndexController
{
    public $token;
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function actionIndex()
    {
        echo "
            <form action='index/creator' method='post'>
                <h1>Задайте число от 0 до 10000</h1>
                <input type='number' name='count' required>
                <input type='submit' value='Создать'>
            </form>
        ";
    }

    public function actionCreator()
    {
        $count = $_POST["count"];
        if ($count > 10000) {
            echo "Число больше 10000";
            exit();
        }

        $model = new Model($this->token, $count);
        $companyIds = $model->createCompanies(); // Добавить компании
        $contactsIds = $model->createContacts(); // Добавить контакты
        $customersIds = $model->createCustomer(); //Добавить компании
        $dealsIds = $model->createDeal(); //Добавить сделки

        $addedFieldsIds = $model->addMultiselect(); // Добавить конатктам кастомное поле
        $model->addValuesToMultiselect($addedFieldsIds, $contactsIds); // Добавление случайного значения мультиполя
        $model->createRelations($companyIds, $contactsIds, $dealsIds, $customersIds); // Создать связи
        header("Location:/index/inputs");
    }

    public function actionInputs()
    {
        echo "
                <form action='/index/addTextField' method='post'>
                    <label for='searchingId'>
                        <h1>Введите id </h1>
                    </label>
                    <input type='number' id='searchingId' name='inputId' required>
                    <p>Выберите тип сущности</p>
                    <div>
                        <input type='radio' id='company1'
                               name='textField' value='companies' required>
                        <label for='company1'>Компания</label>
                
                        <input type='radio' id='contact1'
                               name='textField' value='contacts'>
                        <label for='contact1'>Контакт</label>
                
                        <input type='radio' id='customer1'
                               name='textField' value='customers'>
                        <label for='customer1'>Покупатель</label>
                        <input type='radio' id='lead1'
                               name='textField' value='leads'>
                        <label for='lead1'>Сделка</label>
                    </div>
                    <br>
                    <h1>Поле</h1>
                    Название поля<input type='text' name='nameFieldValue'>Значение поля<input type='text' name='textFieldValue'><button >Добавить текстовое поле</button>
                    </form>
                    
                    <hr>
                    
                    <form action='/index/addNote' method='post'>
                        <label for='searchingId'>
                        <h1>Введите id </h1>
                        </label>
                        <input type='number' id='searchingId' name='inputNoteId' required>
                        <p>Выберите тип сущности</p>
                        <div>
                            <input type='radio' id='company2'
                                   name='addNote' value='companies' required>
                            <label for='company2'>Компания</label>
                    
                            <input type='radio' id='contact2'
                                   name='addNote' value='contacts'>
                            <label for='contact2'>Контакт</label>
                    
                            <input type='radio' id='customer2'
                                   name='addNote' value='customers'>
                            <label for='customer2'>Покупатель</label>
                            <input type='radio' id='lead'
                                   name='addNote' value='leads'>
                            <label for='lead'>Сделка</label>
                        </div>
                        <h1>Примечание</h1>
                        <select name='noteSelect' id=''>
                            <option value='note'>Добавить примечани</option>
                            <option value='call'>Добавить входящий звонок</option>
                            <input type='number' name='addNoteValue'>                            
                        </select>
                        <button type='submit'>OK</button>                 
                    </form>
                        
                        <hr>
                    <form action='/index/addTask' method='post'>
                    <label for='searchingId'>
                        <h1>Введите id </h1>
                    </label>
                    <input type='number' id='searchingId' name='inputTaskId' required>
                    <p>Выберите тип сущности</p>
                    <div>
                        <input type='radio' id='company3'
                               name='addTask' value='companies' required>
                        <label for='company3'>Компания</label>
                
                        <input type='radio' id='contact3'
                               name='addTask' value='contacts'>
                        <label for='contact3'>Контакт</label>
                
                        <input type='radio' id='customer3'
                               name='addTask' value='customers'>
                        <label for='customer3'>Покупатель</label>
                        <input type='radio' id='lead'
                               name='addTask' value='leads'>
                        <label for='lead'>Сделка</label>
                    </div>
                    <h1>Задача</h1>
                    <p>
                        Дата <input type='date' name='addTaskDate'>
                        Текст <input type='text' name='addTaskText'>
                        ID ответственного <input type='number' name='addTaskUserId'>
                        <button>Добавить задачу</button>
                        <br><br>
                        Завершить задачу по ID <input type='number' name='finishUserId'>
                        <button type='submit'>Завершить задачу</button>
                    </p>
                    </form>
            
        ";
    }

//    public function actionAdd()
//    {
//        $response = json_decode((file_get_contents("accessToken.txt")), true);
//        $access_token = $response['access_token']; //Access токен
//        $model = new Model($access_token, null);
//        $model->addTextField(100, "companies", "", "");
//    }
    public function actionAddTextField()
    {
        $model = new Model($this->token, null);
        $model->addTextField((int) $_POST["inputId"], $_POST["textField"], $_POST["nameFieldValue"],$_POST["textFieldValue"]);
    }
    public function actionAddNote()
    {
        $model = new Model($this->token, null);
        if($_POST["noteSelect"] === "note"){
                $model->addNote((int) $_POST["inputNoteId"], $_POST["addNote"], $_POST["addNoteValue"]);
        }
        if($_POST["noteSelect"] === "call"){
            $model->addCall((int) $_POST["inputNoteId"], $_POST["addNote"], $_POST["addNoteValue"]);
        }
    }
    public function actionAddTask()
    {
        $model = new Model($this->token, null);
        if($_POST["finishUserId"]){
            $model->finishTask((int)$_POST["finishUserId"]);
        } else {
            [$year, $month, $day] = explode("-", $_POST["addTaskDate"]);
            $unixDate = mktime(null, null, null, $month, $day, $year);
            $model->addTask((int)$_POST["inputTaskId"], $_POST["addTask"], $unixDate, $_POST["addTaskText"], (int)$_POST["addTaskUserId"]);
        }
        //$id, $entity, $date, $text, $respUserId
//        echo $_POST["inputTaskId"];
//        echo $_POST["addTask"];
//        echo $_POST["addTaskText"];
//        echo $_POST["addTaskUserId"];
//        echo $_POST["finishUserId"];
    }

}