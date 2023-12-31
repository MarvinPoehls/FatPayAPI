<?php

namespace Src;

use PDO;

class TransactionGateway
{
    protected $connection;

    public function __construct($database)
    {
        $this->connection = $database->getConnection($database->getDatabaseName());
    }

    public function getAll()
    {
        $sql = "SELECT * FROM transactions";
        $stmt = $this->connection->query($sql);

        $data = [];
        while ($row = $stmt->fetch_assoc()) {
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

        return $stmt->fetch_assoc();
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO transactions (
                    status,
                    errormessage,
                    shopsystem,
                    shopversion,
                    moduleversion,
                    language,
                    billing_firstname,
                    billing_lastname,
                    billing_street,
                    billing_zip,
                    billing_city,
                    billing_country,
                    shipping_firstname,
                    shipping_lastname,
                    shipping_street,
                    shipping_zip,
                    shipping_city,
                    shipping_country,
                    email,
                    customer_nr,
                    order_nr,
                    amount,
                    currency
                )
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bind_param(
            'sssssssssssssssssssssds',
            $data['status'],
            $data['errormessage'],
            $data['shopsystem'],
            $data['shopversion'],
            $data['moduleversion'],
            $data['language'],
            $data['billing_firstname'],
            $data['billing_lastname'],
            $data['billing_street'],
            $data['billing_zip'],
            $data['billing_city'],
            $data['billing_country'],
            $data['shipping_firstname'],
            $data['shipping_lastname'],
            $data['shipping_street'],
            $data['shipping_zip'],
            $data['shipping_city'],
            $data['shipping_country'],
            $data['email'],
            $data['customer_nr'],
            $data['order_nr'],
            $data['order_sum'],
            $data['currency']
        );

        $stmt->execute();

        return $this->connection->insert_id;
    }
}