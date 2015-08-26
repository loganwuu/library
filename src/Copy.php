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
            $books = array();
            foreach($returned_copies as $copy) {

                
                $description = $copy['description'];
                $id = $copy['id'];
                $category_id = $copy['category_id'];
                $due_date = $copy['due_date'];
                $new_task = new Task($description, $id, $category_id, $due_date);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }
    }

?>
