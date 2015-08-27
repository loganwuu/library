<?php
    class Checkout
    {
        private $copy_id;
        private $patron_id;
        private $id;
        private $due_date;

        function __construct($copy_id, $patron_id, $id = null, $due_date) {
            $this->copy_id = $copy_id;
            $this->patron_id = $patron_id;
            $this->id = $id;
            $this->due_date = $due_date;
        }

        function setCopyId($copy_id) {
            $this->copy_id = $copy_id;
        }

        function setId($new_id) {
            $this->id = $new_id;
        }


        function getCopyId() {
            return $this->copy_id;
        }

        function setPatronId($patron_id) {
            $this->patron_id = $patron_id;
        }

        function getPatronId() {
            return $this->patron_id;
        }

        function getId() {
            return $this->id;
        }

        function setDueDate($due_date) {
            $this->due_date = $due_date;
        }

        function getDueDate() {
            return $this->due_date;
        }

        function save() {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, patron_id, due_date) VALUES ({$this->getCopyId()}, {$this->getPatronId()}, '{$this->getDueDate()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll() {
            $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts;");
            $checkouts = array();
            foreach($returned_checkouts as $checkout) {
                $copy_id = $checkout['copy_id'];
                $patron_id = $checkout['patron_id'];
                $id = $checkout['id'];
                $due_date = $checkout['due_date'];
                $new_checkout = new Checkout($copy_id, $patron_id, $id, $due_date);
                array_push($checkouts, $new_checkout);
            }
            return $checkouts;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM checkouts;");
        }

        function update($new_due_date) {
            $GLOBALS['DB']->exec("UPDATE checkouts SET due_date = '{$new_due_date}' WHERE id = {$this->getId()};");
            $this->setDueDate($new_due_date);
        }

        static function find($search_id)
        {
            $found_checkout = null;
            $checkouts = Checkout::getAll();
            foreach($checkouts as $checkout) {
                $checkout_id = $checkout->getId();
                if ($checkout_id == $search_id) {
                    $found_checkout = $checkout;
                }
            }
            return $found_checkout;
        }
    }
?>
