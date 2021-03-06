<?php

function getAllGames() {
    $sql = 'SELECT * FROM game';
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games === []) {
            $games = [['id'=>null]];
        }
        $db = null;
        echo json_encode($games);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getGame($id) {
    $sql = 'SELECT * FROM game WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $game = $stmt->fetchObject();
        if ($game === false) {
            $game = ['id'=>null]; 
        }
        $db = null;
        echo json_encode($game);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getSubjectGames($subject) {
    $sql = 'SELECT * FROM game WHERE subject = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $subject);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($games === []) {
            $games = [['id'=>null]]; 
        }
        $db = null;
        echo json_encode($games);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function createGame() {
    $app = \Slim\Slim::getInstance();
    $subject = $app->request()->post('subject');
    $title = $app->request()->post('title');
    $description = $app->request()->post('description');
    $locale = $app->request()->post('locale');
    $difficulty = $app->request()->post('difficulty');
	
	//---------------------Lineas nuevas----------------------------
	
	$time = $app->request()->post('time');
	$visibility = 'no';
	$classId = $app->request()->post('classId');
	
	//---------------------Lineas nuevas----------------------------
	
    $sql = 'INSERT INTO game (subject, title, description, locale, difficulty, vote, time, visibility, classId) VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)'; //Modificado
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $subject);
        $stmt->bindValue(2, $title);
        $stmt->bindValue(3, $description);
        $stmt->bindValue(4, $locale);
        $stmt->bindValue(5, $difficulty);
		$stmt->bindValue(6, $time);
		$stmt->bindValue(7, $visibility);
		$stmt->bindValue(8, $classId);
        $stmt->execute();
        $id = $db->lastInsertId();
        $db = null;
        echo getGame($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function updateGame() {
    $app = \Slim\Slim::getInstance();
    $title = $app->request()->post('title');
    $description = $app->request()->post('description');
    $difficulty = $app->request()->post('difficulty');
	$time = $app->request()->post('time');
	$visibility = $app->request()->post('visibility');
    $id = $app->request()->post('id');
    $sql = 'UPDATE game SET title = ?, description = ?, difficulty = ?, time = ?, visibility = ? WHERE id = ?'; //Modificado
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $title);
        $stmt->bindValue(2, $description);
        $stmt->bindValue(3, $difficulty);
		$stmt->bindValue(4, $time);
		$stmt->bindValue(5, $visibility);
        $stmt->bindValue(6, $id);
		$stmt->execute();
        $db = null;
        echo getGame($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    deleteGameActivity($id);
    deleteGameQuiz($id);
    $sql = 'DELETE FROM game WHERE id = ?';
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

function finishGame() {
    $app = \Slim\Slim::getInstance();
    $student_id = $app->request()->post('student_id');
    $game_id = $app->request()->post('game_id');
    $subject = $app->request()->post('subject');
    $result = $app->request()->post('result');
    $date = date("d-m-Y");
    $sql = 'SELECT count(*) FROM activity WHERE student_id = ? AND game_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $student_id);
        $stmt->bindValue(2, $game_id);
        $stmt->execute();
        if ($stmt->fetchColumn() === '0') {
            $sql1 = 'INSERT INTO activity (student_id, game_id, subject, result, date) VALUES (?, ?, ?, ?, ?)';
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(1, $student_id);
            $stmt1->bindValue(2, $game_id);
            $stmt1->bindValue(3, $subject);
            $stmt1->bindValue(4, $result);
            $stmt1->bindValue(5, $date);
            $stmt1->execute();
        } else {
            $sql2 = 'UPDATE activity SET result = ?, date = ? WHERE student_id = ? AND game_id = ?';
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(1, $result);
            $stmt2->bindValue(2, $date);
            $stmt2->bindValue(3, $student_id);
            $stmt2->bindValue(4, $game_id);
            $stmt2->execute();
        }
        $db = null;
        echo getActivity($student_id,$game_id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function upvoteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = 'UPDATE game SET vote = vote+1 WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $db = null;
        echo getGame($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function downvoteGame() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = 'UPDATE game SET vote = vote-1 WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $db = null;
        echo getGame($id);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

// Private Functions

function deleteGameActivity($game_id) {
    $sql = 'DELETE FROM activity WHERE game_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $game_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function deleteGameQuiz($game_id) {
    $sql = 'DELETE FROM quiz WHERE game_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $game_id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function getActivity($student_id, $game_id) {
    $sql = 'SELECT * FROM activity WHERE student_id = ? AND game_id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $student_id);
        $stmt->bindValue(2, $game_id);
        $stmt->execute();
        $activity = $stmt->fetchObject();
        if ($activity === false) {
            $activity = ['student_id'=>null,'game_id'=>null]; 
        }
        $db = null;
        echo json_encode($activity);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>