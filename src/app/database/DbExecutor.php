<?php

include_once 'DBConnector.php';

function createFile($fileName, $user, $content) {
    $sql = 'INSERT INTO files(file, created_by, content) VALUES (:file, :created_by, :content)';

    if ($stmt = DBConnector::getInstance()::getConnection() -> prepare($sql)) {
        $stmt -> bindParam(':file', $fileName);
        $stmt -> bindParam(':created_by', $user);
        $stmt -> bindParam(':content', $content);

        if ($stmt -> execute()) {
            return TRUE;
        }

        return FALSE;
    }
}
?>
