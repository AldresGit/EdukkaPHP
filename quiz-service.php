<?php

function getAllQuizzes() {
    $sql = 'SELECT * FROM quiz';
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $quizzes = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($quizzes === []) {
            $quizzes = [['id'=>null]];
        }
        $db = null;
        echo json_encode($quizzes);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getQuiz($id) {
    $sql = 'SELECT * FROM quiz WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $quiz = $stmt->fetchObject();
        if ($quiz === false) {
            $quiz = ['id'=>null];
        }
        $db = null;
        echo json_encode($quiz);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getGameQuiz($game_id) {
    $sql = 'SELECT * FROM quiz WHERE game_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $game_id);
        $stmt->execute();
        $quizzes = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($quizzes === []) {
            $quizzes = [['id'=>null]];
        }
        $db = null;
        echo json_encode($quizzes);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function createQuiz() {
    $app = \Slim\Slim::getInstance();
    $question = $app->request()->post('question');
    $answer = $app->request()->post('answer');
    $options = $app->request()->post('options');
	$type = $app->request()->post('type');
    $game_id = $app->request()->post('game_id');
	$edited = 'no';
    $sql = 'INSERT INTO quiz (question, answer, options, type, game_id, edited) VALUES (?, ?, ?, ?, ?, ?)';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $question);
        $stmt->bindValue(2, $answer);
        $stmt->bindValue(3, $options);
		$stmt->bindValue(4, $type);
        $stmt->bindValue(5, $game_id);
		$stmt->bindValue(6, $edited);
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;
        echo getQuiz($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateQuiz() {
    $app = \Slim\Slim::getInstance();
    $question = $app->request()->post('question');
    $answer = $app->request()->post('answer');
    $options = $app->request()->post('options');
	$edited = 'yes';
    $id = $app->request()->post('id');
    $sql = 'UPDATE quiz SET question = ?, answer = ?, options = ?, edited = ? WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $question);
        $stmt->bindValue(2, $answer);
        $stmt->bindValue(3, $options);
		$stmt->bindValue(4, $edited);
        $stmt->bindValue(5, $id);
        $stmt->execute();
        $db = null;
        echo getQuiz($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteQuiz() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = 'DELETE FROM quiz WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>