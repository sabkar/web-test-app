<?php 

include_once 'headers/base.headers.php';
include_once 'utils/response.php';
include_once 'config/database.php';
include_once 'objects/Quiz.php';

$db = Database::getConnection();

function route($method, $urlData, $formData) {
  //echo $method;
  //print_r($urlData);
  
  if ($method === 'GET') {
    // GET /quizes/
    if (!count($urlData)) {
      return findAll();
    }

  }
  return response(array('error' => 'Bad Request'), 400);
}

/*
  Находит все тесты. Отображает неполную информацию: только название, количесво вопросов, лимит и ИД
  Работает по роуту:
  GET /quizes/
*/
function findAll() {
  global $db;
  // вытаскиваем все данные из БД
  $stmt = Quiz::findAll($db);

  // если что-то есть - достаем
  if ($stmt->rowCount() > 0) {
    // пользуемся магической функцией для автоматического формирования объекта
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Quiz');
    // сюда будем записывать готовый объект
    $quiz_array = array();
    $quiz_array['quizes'] = array();
    // пока что-то есть - достаем и пушим в массив
    while($row = $stmt->fetch(PDO::FETCH_CLASS)) {
      array_push($quiz_array['quizes'], $row);
    }
    return response($quiz_array, 200);
  }
  // если ничего не нашлось - 404
  return response(array('error' => 'Not Found'), 404);
}

?>