<?php
    class Copy
    {
        private $book_id;
        private $id;

        function __construct($book_id, $id = null)
        {
            $this->book_id = $book_id;
            $this->id = $id;
        }

        function getBookId()
        {
            return $this->book_id;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO copies (book_id) VALUES ({$this->getBookId()} );");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();
            foreach($returned_copies as $copy) {
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $new_copy = new Copy($book_id, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies;");
        }

        static function find($search_id)
        {
            $found_copy = NULL;
            $copies = Copy::getAll();
            foreach($copies as $copy) {
                $copy_id = $copy->getId();
                if ($copy_id == $search_id) {
                    $found_copy = $copy;
                }
            }
            return $found_copy;
        }

        static function findCopies($search_book_id)
        {
            $found_copies = array();
            $copies = Copy::getAll();
            foreach($copies as $copy) {
                $book_id = $copy->getBookId();
                if ($book_id == $search_book_id) {
                    array_push($found_copies, $copy);
                }
            }
            return $found_copies;
        }

        // function addCheckoutCopy($patron, $due_date)
        // {
        //     $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, patron_id, due_date) VALUES ({$this->getId()}, {$patron->getId()}), '{$this->getDueDate()}');");
        // }
        //
        // function getPatrons()
        // {
        //     $query = $GLOBALS['DB']->query("SELECT patron_id FROM checkouts WHERE copy_id = {$this->getId()};");
        //     $patron_ids = $query->fetchAll(PDO::FETCH_ASSOC);
        //     $patrons = array();
        //     foreach($patron_ids as $id) {
        //         $patron_id = $id['patron_id'];
        //         $result = $GLOBALS['DB']->query("SELECT * FROM patrons WHERE id = {$patron_id};");
        //         $returned_patron = $result->fetchAll(PDO::FETCH_ASSOC);
        //         $name = $returned_patron[0]['name'];
        //         $id = $returned_patron[0]['id'];
        //         $new_patron = new Patron($name, $id);
        //         array_push($patrons, $new_patron);
        //     }
        //     return $patrons;
        // }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = {$this->getId()};");
            // $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE copy_id = {$this->getId()};");
        }
        function getCheckouts() {

            $query = $GLOBALS['DB']->query("SELECT patrons.* FROM copies
                JOIN checkouts ON (copies.id = checkouts.copy_id)
                JOIN patrons ON (checkouts.patron_id = patrons.id)
                WHERE copies.id = {$this->getId()};");
            $patrons = $query->fetchAll(PDO::FETCH_ASSOC);
            $patrons_array = array();

            foreach($patrons as $patron) {
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons_array, $new_patron);
            }
            return $patrons_array;

        }
    }

?>
