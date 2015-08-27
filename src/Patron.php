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

        function addCheckout($checkout)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, patron_id, due_date) VALUES ({$checkout->getCopyId()}, {$this->getId()}, '{$checkout->getDueDate()}');");
        }

        function getCheckouts()
        {
            $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts WHERE patron_id = {$this->getId()}");
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
            // $query = $GLOBALS['DB']->query("SELECT copies.* FROM patrons
            //     JOIN checkouts ON (patrons.id = checkouts.patron_id)
            //     JOIN copies ON (checkouts.copy_id = copies.id)
            //     WHERE patrons.id = {$this->getId()};");
            // $copies = $query->fetchAll(PDO::FETCH_ASSOC);
            // $copies_array = array();
            //
            // foreach($copies as $copy) {;
            //     $book_id = $copy['book_id'];
            //     $id = $copy['id'];
            //     $new_copy = new Copy($book_id, $id);
            //     array_push($copies_array, $new_copy);
            // }
            // return $copies_array;
        }

       function delete()
       {
           $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
           //$GLOBALS['DB']->exec("DELETE FROM checkouts WHERE patron_id = {$this->getId()};");
       }
   }
?>
