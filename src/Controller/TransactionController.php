<?php

namespace Src\Controller;

class TransactionController
{
    protected $gateway;

    public function __construct($gateway)
    {
        $this->gateway = $gateway;
    }

    public function processRequest($method, $id = null)
    {
        if ($id) {
            $this->processSingleRequest($method, $id);
        }
        else {
            $this->processCollectionRequest($method);
        }
    }

    protected function processSingleRequest($method, $id) {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getOne($id));
                break;
            case "POST":
                break;
        }
    }

    protected function processCollectionRequest($method) {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
            case "POST":
                $data = $_REQUEST;

                if (!array_key_exists("billing_lastname", $data)) {
                    echo json_encode([
                        "status" => "ERROR",
                        "errormessage" => "No lastname given."
                    ]);
                    break;
                }
                if ($data["billing_lastname"] === "Failed") {
                    $data["status"] = "ERROR";
                    $data["errormessage"] = "Lastname is 'Failed'.";
                }
                else
                {
                    $data["status"] = "APPROVED";
                    $data['errormessage'] = null;
                }

                $id = $this->gateway->create($data);

                echo json_encode([
                    "status" => $data['status'],
                    "errormessage" => $data['errormessage'],
                    "id" => $id
                ]);
                break;
        }
    }
}