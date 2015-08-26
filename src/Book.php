<?php
    class Book
    {
        private $title;
        private $id;

        function __construct($title, $id = null)
        {
            $this->title = $title;
            $this->id = $id;
        }

        function setTitle($new_title)
        {
            $this->title = (string) $new_title;
        }

        function getTitle()
        {
            return $this->title;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->getTitle()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getALl()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books;");
        }

        static function find($search_id)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                    $found_book = $book;
                }
            }
            return $found_book;
        }

        function update($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
            $this->setTitle($new_title);
        }

        function addAuthor($author)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$author->getId()}, {$this->getId()});");
        }

        function getAuthors()
        {
            $query = $GLOBALS['DB']->query("SELECT author_id FROM authors_books WHERE book_id = {$this->getId()};");
            $author_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            $authors = array();
            foreach($author_ids as $id) {
                $author_id = $id['author_id'];
                $result = $GLOBALS['DB']->query("SELECT * FROM authors WHERE id = {$author_id};");
                $returned_author = $result->fetchAll(PDO::FETCH_ASSOC);

                $name = $returned_author[0]['name'];
                $id = $returned_author[0]['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function search($search_title)
        {
            $found_books = array();
            $books = Book::getAll();
            foreach($books as $book) {
                $book_title = $book->getTitle();
                if($book_title == $search_title) {
                    array_push($found_books, $book);
                }
            }
            return $found_books;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE book_id = {$this->getId()};");
        }
    }
?>
