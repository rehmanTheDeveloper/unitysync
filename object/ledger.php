<?php

class Ledger
{
    private $PDO;
    private $INSERT_DATA;
    private $FETCH_DATA;
    private $UPDATE_DATA;
    private $DELETE_DATA;
    public $result;

    public function __construct($pdo)
    {
        $this->PDO = $pdo;
    }

    private function sanitize($value)
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    public function insert($data)
    {
        foreach ($data as $key => $value) {
            $this->INSERT_DATA[$key] = $this->sanitize($value);
        }
        if (!isset($this->INSERT_DATA['sale_id'])) {
            $this->INSERT_DATA['sale_id'] = "";
        }
        $query = "INSERT INTO `ledger`(`v-id`, `type`, `sale_id`, `source`, `pay_to`, `amount`, `remarks`, `project_id`, `created_date`, `created_by`) 
        VALUES (:v_id, :type, :sale_id, :source, :pay_to, :amount, :remarks, :project_id, :created_date, :created_by);";
        $stmt = $this->PDO->prepare($query);
        $stmt->bindParam(":v_id", $this->INSERT_DATA['v-id']);
        $stmt->bindParam(":type", $this->INSERT_DATA['type']);
        $stmt->bindParam(":sale_id", $this->INSERT_DATA['sale_id']);
        $stmt->bindParam(":source", $this->INSERT_DATA['source']);
        $stmt->bindParam(":pay_to", $this->INSERT_DATA['pay_to']);
        $stmt->bindParam(":amount", $this->INSERT_DATA['amount']);
        $stmt->bindParam(":remarks", $this->INSERT_DATA['remarks']);
        $stmt->bindParam(":project_id", $this->INSERT_DATA['project']);
        $stmt->bindParam(":created_date", $this->INSERT_DATA['created_date']);
        $stmt->bindParam(":created_by", $this->INSERT_DATA['created_by']);
        if ($stmt->execute()) {
            return "true";
        } else {
            return "false";
        }
    }

    public function fetch($account)
    {
        foreach ($account as $key => $value) {
            $this->FETCH_DATA[$key] = $this->sanitize($value);
        }

        if (isset($this->FETCH_DATA['v-id'])) {
            $query = "SELECT * FROM `ledger`
            WHERE `v-id` = :voucher AND `project_id` = :project;";
            $stmt = $this->PDO->prepare($query);
            $stmt->bindParam(":voucher", $this->FETCH_DATA["v-id"]);
            $stmt->bindParam(":project", $this->FETCH_DATA["project"]);
        } elseif (isset($this->FETCH_DATA['type']) && isset($this->FETCH_DATA['sale_id'])) {
            $query = "SELECT `v-id`,`type`,`source`,`pay_to`,`remarks`,
                (CASE
                WHEN `source` = :source THEN amount
                ELSE 0
                END) AS debit
                ,
                (CASE
                WHEN `pay_to` = :pay_to THEN amount
                ELSE 0
                END) AS credit
                ,
                (CASE
                WHEN `source` = :source THEN pay_to
                END) AS account
                ,
                @balance := @balance + (CASE
                    WHEN `source` = :source THEN -amount
                    WHEN `pay_to` = :pay_to THEN amount
                    ELSE 0
                END) AS balance
            FROM `ledger`
            JOIN (SELECT @balance := 0) AS init
            WHERE (`source` = :source OR `pay_to` = :pay_to) AND `type` = :type AND `sale_id` = :sale_id AND `project_id` = :project;";
            $stmt = $this->PDO->prepare($query);
            $stmt->bindParam(":type", $this->FETCH_DATA["type"]);
            $stmt->bindParam(":sale_id", $this->FETCH_DATA["sale_id"]);
            $stmt->bindParam(":source", $this->FETCH_DATA["source"]);
            $stmt->bindParam(":pay_to", $this->FETCH_DATA["pay_to"]);
            $stmt->bindParam(":project", $this->FETCH_DATA["project"]);
        } elseif (isset($this->FETCH_DATA['type'])) {
            $query = "SELECT `v-id`,`type`,`sale_id`,`source`,`pay_to`,`remarks`,`amount` FROM `ledger`
            WHERE `type` = :type AND `project_id` = :project;";
            $stmt = $this->PDO->prepare($query);
            $stmt->bindParam(":type", $this->FETCH_DATA["type"]);
            $stmt->bindParam(":project", $this->FETCH_DATA["project"]);
        } else {
            $query = "SELECT `v-id`,`type`,`source`,`pay_to`,`remarks`,
                (CASE
                WHEN `source` = :source THEN amount
                ELSE 0
                END) AS debit
                ,
                (CASE
                WHEN `pay_to` = :pay_to THEN amount
                ELSE 0
                END) AS credit
                ,
                (CASE
                WHEN `source` = :source THEN pay_to
                END) AS account
                ,
                @balance := @balance + (CASE
                    WHEN `source` = :source THEN -amount
                    WHEN `pay_to` = :pay_to THEN amount
                    ELSE 0
                END) AS balance
            FROM `ledger`
            JOIN (SELECT @balance := 0) AS init
            WHERE (`source` = :source OR `pay_to` = :pay_to) AND `project_id` = :project;";
            $stmt = $this->PDO->prepare($query);
            $stmt->bindParam(":source", $this->FETCH_DATA["source"]);
            $stmt->bindParam(":pay_to", $this->FETCH_DATA["pay_to"]);
            $stmt->bindParam(":project", $this->FETCH_DATA["project"]);
        }
        if ($stmt->execute()) {
            $this->result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ($this->result);
        } else {
            return [];
        }
    }

    public function delete($account)
    {
        foreach ($account as $key => $value) {
            $this->DELETE_DATA[$key] = $this->sanitize($value);
        }
        if (isset($this->DELETE_DATA['type'])) {
            $query = "DELETE FROM `ledger` WHERE `type` = :type AND `source` = :source AND `project_id` = :project;";
            $stmt = $this->PDO->prepare($query);
            $stmt->bindParam(":type", $this->DELETE_DATA["type"]);
            $stmt->bindParam(":source", $this->DELETE_DATA["source"]);
            $stmt->bindParam(":project", $this->DELETE_DATA["project"]);
        } else {
            $query = "DELETE FROM `ledger` WHERE (`source` = :source OR `pay_to` = :pay_to) AND `project_id` = :project;";
            $stmt = $this->PDO->prepare($query);
            $stmt->bindParam(":source", $this->DELETE_DATA["source"]);
            $stmt->bindParam(":pay_to", $this->DELETE_DATA["pay_to"]);
            $stmt->bindParam(":project", $this->DELETE_DATA["project"]);
        }

        if ($stmt->execute()) {
            return "true";
        } else {
            return "false";
        }
    }

}