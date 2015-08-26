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

        function addCopy($copy)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, patron_id) VALUES ({$copy->getId()}, {$this->getId()});");
        }

        function getCopies()
       {
           $query = $GLOBALS['DB']->query("SELECT copy_id FROM checkouts WHERE patron_id = {$this->getId()};");
           $copy_ids = $query->fetchAll(PDO::FETCH_ASSOC);
           $copies = array();
           foreach($copy_ids as $id) {
               $copy_id = $id['copy_id'];
               $result = $GLOBALS['DB']->query("SELECT * FROM copies WHERE id = {$copy_id};");
               $returned_copy = $result->fetchAll(PDO::FETCH_ASSOC);
               $book_id = $returned_copy[0]['book_id'];
               $id = $returned_copy[0]['id'];
               $new_copy = new Copy($book_id, $id);
               array_push($copies, $new_copy);
           }
           return $copies;
       }
       
       function delete()
       {
           $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
           $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE patron_id = {$this->getId()};");
       }
   }
?>
