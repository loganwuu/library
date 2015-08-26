<?php
    class Copy
    {
        private $book_id;
        private $count;
        private $due_date;
        private $id;

        function __construct($book_id, $count, $due_date = '0000-00-00', $id = null)
        {
            $this->book_id = $book_id;
            $this->count = $count;
            $this->due_date = $due_date;
            $this->id = $id;
        }

        function setCount($new_count)
        {
            $this->count = (int) $new_count;
        }

        function getCount()
        {
            return $this->count;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = (string) $new_due_date;
        }

        function getDueDate()
        {
            return $this->due_date;
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
            $GLOBALS['DB']->exec("INSERT INTO copies (book_id, count, due_date) VALUES ({$this->getBookId()}, {$this->getCount()}, '{$this->getDueDate()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();
            foreach($returned_copies as $copy) {
                $book_id = $copy['book_id'];
                $count = $copy['count'];
                $due_date = $copy['due_date'];
                $id = $copy['id'];
                $new_copy = new Copy($book_id, $count, $due_date, $id);
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

        function update($new_due_date)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET due_date = '{$new_due_date}' WHERE id = {$this->getId()};");
            $this->setDueDate($new_due_date);
        }
    }

?>
