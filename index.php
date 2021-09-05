<?php
require 'db/utils';

header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $db = connect();
    if (isset($_GET['id'])) {
        // Mostrar un post
        $query = "SELECT * FROM" . DB_DATABASE . "WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        header("HTTP/1.1 200 OK");
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        disconnect();
        exit();
    } else {
        // Mostrar todos los posts
        $query = "SELECT * FROM" . DB_DATABASE;
        $stmt = $db->prepare($query);
        $stmt->execute();
        header("HTTP/1.1 200 OK");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        disconnect();
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = connect();
    $input = $_POST;
    $query = "INSERT INTO " . DB_DATABASE . "(title, status, content) VALUES (:title, :status, :content)";
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
    $query = "DELETE FROM" . DB_DATABASE . "WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    header("HTTP/1.1 200 OK");
    disconnect();
    exit();
}
