<?php

namespace Src;

use PDO;

class TransactionGateway
{
    protected $connection;

    public function __construct($database)
    {
        $this->connection = $database->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM transactions";
        $stmt = $this->connection->query($sql);

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getOne($id)
    {
        $sql = "SELECT EXISTS(SELECT 1 FROM transactions WHERE id = $id)";
        $stmt = $this->connection->query($sql);
        $exists = $stmt->fetch()[0];

        if (!$exists) {
            return [
                "status" => "ERROR",
                "errormessage" => "Transaction with ID = $id doesnt exist."
            ];
        }

        $sql = "SELECT * FROM transactions WHERE id = ".$id;
        $stmt = $this->connection->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO transactions (firstname, lastname, status, errormessage)
                VALUES (:firstname, :lastname, :status, :errormessage)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":firstname", $data["firstname"], PDO::PARAM_STR);
        $stmt->bindValue(":lastname", $data["lastname"], PDO::PARAM_STR);
        $stmt->bindValue(":status", $data["status"], PDO::PARAM_STR);
        $stmt->bindValue(":errormessage", $data["errormessage"], PDO::PARAM_STR);

        $stmt->execute();

        return $this->connection->lastInsertId();
    }
}