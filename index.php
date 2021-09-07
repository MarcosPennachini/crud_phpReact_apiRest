<?php
require 'db/utils.php';

header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $db = connect();
    if (isset($_GET['id'])) {
        // Mostrar un post
        $query = "SELECT * FROM posts WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        header("HTTP/1.1 200 OK");
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        disconnect();
        exit();
    } else {
        // Mostrar todos los posts
        $query = "SELECT * FROM posts";
        $stmt = $db->prepare($query);
        $stmt->execute();
        header("HTTP/1.1 200 OK");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        disconnect();
        exit();
    }
}

if ($_POST['METHOD'] == 'POST') {
    unset($_POST['METHOD']);
    $db = connect();
    $input = $_POST;
    $query = "INSERT INTO posts (title, status, content) VALUES (:title, :status, :content)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':title', $_POST['title']);
    $stmt->bindParam(':status', $_POST['status']);
    $stmt->bindParam(':content', $_POST['content']);
    $stmt->execute();
    $postId = $db->lastInsertId();

    if ($postId) {
        $input['id'] = $postId;
        header("HTTP/1.1 200 OK");
        echo json_encode($input);
        exit();
    }

    disconnect();
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $db = connect();
    $id = $_GET['id'];
    $query = "DELETE FROM posts WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("HTTP/1.1 200 OK");
    disconnect();
    exit();
}

if ($_POST['METHOD'] == 'PUT') {
    unset($_POST['METHOD']);
    $db = connect();

    $postId = $_GET['id'];
    if (!isset($_POST)) {
        echo 'not isset';
    }
    echo json_encode($_POST);
    $title = $_POST['title'];
    $status = $_POST['status'];
    $content = $_POST['content'];

    $query = "UPDATE posts SET title=:title, status=:status, content=:content WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':id', $postId);
    $stmt->execute();
    header("HTTP/1.1 200 OK");
    disconnect();
    exit();
}

header("HTTP/1.1 400 Bad Request");
