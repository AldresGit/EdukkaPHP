<?php

function getRoom($id) {
    $sql = 'SELECT * FROM multiplayer_game WHERE id = ?';
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $room = $stmt->fetchObject();
        if ($room === false) {
            $room = ['id'=>null]; 
        }
        $db = null;
        echo json_encode($room);
    } catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function searchRoom($id) {
	$sql = 'SELECT * FROM multiplayer_game WHERE status = ? AND class_id = ?';
	try{
		$status = 'waiting';
		$db = getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(1, $status);
		$stmt->bindValue(2, $id);
		$stmt->execute();
		$rooms = $stmt->fetchAll(PDO::FETCH_OBJ);
		if($rooms === []) {
			$rooms = [['id'=>null]];
		}
		$db = null;
		echo json_encode($rooms);
	} catch(PDOException $e) {
		echo json_encode($e->getMessage());
	}
}

function createRoom() {
	$app = \Slim\Slim::getInstance();
	$user1 = $app->request()->post('user1');
	$id_user1 = $app->request()->post('id_user1');
	$user2 = null;
	$id_user2 = null;
	$quizzes = $app->request()->post('quizzes');
	$status = $app->request()->post('status');
	$class_id = $app->request()->post('class_id');
	$extra = 0;
	$data = $app->request()->post('data');
	
	$sql = 'INSERT INTO multiplayer_game (user1, id_user1, user2, id_user2, quizzes, status, class_id, extra, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
	try {
		$db = getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(1, $user1);
		$stmt->bindValue(2, $id_user1);
		$stmt->bindValue(3, $user2);
		$stmt->bindValue(4, $id_user2);
		$stmt->bindValue(5, $quizzes);
		$stmt->bindValue(6, $status);
		$stmt->bindValue(7, $class_id);
		$stmt->bindValue(8, $extra);
		$stmt->bindValue(9, $data);
		$stmt->execute();
		$id = $db->lastInsertId();
		$db = null;
		echo getRoom($id);
	} catch(PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

function joinRoom() {
	$app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $user2 = $app->request()->post('user2');
	$id_user2 = $app->request()->post('id_user2');
	$status = 'prepared';
	$sql = 'UPDATE multiplayer_game SET user2 = ?, id_user2 = ?, status = ? WHERE id = ?';
	try{
		$db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $user2);
        $stmt->bindValue(2, $id_user2);
        $stmt->bindValue(3, $status);
		$stmt->bindValue(4, $id);
		$stmt->execute();
		$db = null;
		echo getRoom($id);
	} catch(PDOException $e) {
		echo json_encode($e->getMessage());
	}
}

function leaveRoom() {
	$app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
	$user2 = null;
	$id_user2 = null;
	$status = 'waiting';
	$sql = 'UPDATE multiplayer_game SET user2 = ?, id_user2 = ?, status = ? WHERE id = ?';
	try{
		$db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $user2);
        $stmt->bindValue(2, $id_user2);
        $stmt->bindValue(3, $status);
		$stmt->bindValue(4, $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo json_encode($e->getMessage());
	}
}

function deleteRoom() {
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
    $sql = 'DELETE FROM multiplayer_game WHERE id = ?';
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

function adornRoom() {
	$app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
	$extra = 1;
	$sql = 'UPDATE multiplayer_game SET extra = ? WHERE id = ?';
	try{
		$db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $extra);
		$stmt->bindValue(2, $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo json_encode($e->getMessage());
	}
}

function getRandomQuizzes($id) {
	$sql = 'SELECT Q.* 
		FROM game G JOIN quiz Q ON G.id = Q.game_id
		WHERE G.classId = ?
		ORDER BY RAND()
		LIMIT 5';
	try{
		$db = getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(1, $id);
		$stmt->execute();
		$quizzes = $stmt->fetchAll(PDO::FETCH_OBJ);
		if($quizzes === []) {
			$quizzes = [['id'=>null]];
		}
		$db = null;
		echo json_encode($quizzes);
	} catch(PDOException $e) {
		echo json_encode($e->getMessage());
	}
}

function updateQuizzes() {
	$app = \Slim\Slim::getInstance();
    $id = $app->request()->post('id');
	$quizzes = $app->request()->post('quizzes');
	$sql = 'UPDATE multiplayer_game SET quizzes = ? WHERE id = ?';
	try{
		$db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $quizzes);
		$stmt->bindValue(2, $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo json_encode($e->getMessage());
	}
}

function getQuizzesById() {
	$app = \Slim\Slim::getInstance();
    $id1 = $app->request()->post('id1');
	$id2 = $app->request()->post('id2');
	$id3 = $app->request()->post('id3');
	$id4 = $app->request()->post('id4');
	$id5 = $app->request()->post('id5');
	$sql = 'SELECT * FROM quiz WHERE id = ? OR id = ? OR id = ? OR id = ? OR id = ?';
	try{
		$db = getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(1, $id1);
		$stmt->bindValue(2, $id2);
		$stmt->bindValue(3, $id3);
		$stmt->bindValue(4, $id4);
		$stmt->bindValue(5, $id5);
		$stmt->execute();
		$quizzes = $stmt->fetchAll(PDO::FETCH_OBJ);
		if($quizzes === []) {
			$quizzes = [['id'=>null]];
		}
		$db = null;
		echo json_encode($quizzes);
	} catch(PDOException $e) {
		echo json_encode($e->getMessage());
	}
}

?>