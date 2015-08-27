<?php
    class Patron
    {
        private $name;
        private $id;

        function __construct($name, $id=null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getId()
        {
            return $this->id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO patrons (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons = array();
            foreach($returned_patrons as $patron) {
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons;");
        }

        static function find($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_id = $patron->getId();
                if ($patron_id == $search_id) {
                    $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        // function addCheckout($checkout)
        // {
        //     $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, patron_id, due_date) VALUES ({$checkout->getCopyId()}, {$this->getId()}, '{$checkout->getDueDate()}');");
        //     $checkout->setId($GLOBALS['DB']->lastInsertId());
        // }

        function getCheckouts()
        {
            $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts WHERE patron_id = {$this->getId()};");

            //var_dump($returned_checkouts);
            $checkouts = array();

            foreach($returned_checkouts as $checkout) {

                $copy_id = $checkout['copy_id'];
                $patron_id = $checkout['patron_id'];
                $id = $checkout['id'];
                $due_date = $checkout['due_date'];
                $new_checkout = new Checkout($copy_id, $patron_id, $due_date, $id);
                array_push($checkouts, $new_checkout);
            }
            return $checkouts;

        }

       function delete()
       {
           $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
           //$GLOBALS['DB']->exec("DELETE FROM checkouts WHERE patron_id = {$this->getId()};");
       }
   }
?>
