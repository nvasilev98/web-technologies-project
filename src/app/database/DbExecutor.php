<?php

include_once 'DBConnector.php';

function createFile($fileName, $user, $content): bool
{
    $selectSql = 'SELECT COUNT(*) as total FROM files WHERE file = :filename AND created_by = :username';

    $version = 0;
    if ($stmt = DBConnector::getInstance()::getConnection() -> prepare($selectSql)) {
        $stmt->bindParam(':filename', $fileName);
        $stmt->bindParam(':username', $user);

        if ($stmt->execute()) {
            $version = $stmt->fetchColumn();
        }
    }
    $version = $version + 1;

    $sql = 'INSERT INTO files(file, created_by, content, version) VALUES (:file, :created_by, :content, :version)';

    if ($stmt = DBConnector::getInstance()::getConnection() -> prepare($sql)) {
        $stmt -> bindParam(':file', $fileName);
        $stmt -> bindParam(':created_by', $user);
        $stmt -> bindParam(':content', $content);
        $stmt -> bindParam(':version', $version);

        if ($stmt -> execute()) {
            return TRUE;
        }

        return FALSE;
    }
}
?>
