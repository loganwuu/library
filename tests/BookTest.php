<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once 'src/Book.php';
    require_once 'src/Author.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
        }

        function testGetTitle()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals($title, $result);
        }

        function testSetTitle()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $test_book->setTitle("Big Cat");
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals("Big Cat", $result);
        }

        function testUpdate()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $new_title = "Big Cat";

            //Act
            $test_book->update($new_title);

            //Assert
            $this->assertEquals("Big Cat", $test_book->getTitle());
        }

        function testSave()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $test_book->save();

            //Assert
            $result = Book::getAll();
            $this->assertEquals($test_book, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Little Dog";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);

        }

        function testDeleteAll()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Little Dog";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            Book::deleteALl();

            //Assert
            $result = Book::getAll();
            $this->assertEquals([], $result);
        }

        function testGetId()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();
            
            //Act
            $result = $test_book->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));

        }

        function testDeleteBook()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Little Dog";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            $test_book->delete();

            //Assert
            $this->assertEquals([$test_book2], Book::getAll());
        }

        function testFind()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Little Dog";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            $id = $test_book->getId();
            $result = Book::find($id);

            //Assert
            $this->assertEquals($test_book, $result);
        }

        function testSaveSetsId()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $test_book->save();

            //Assert
            $this->assertEquals(true, is_numeric($test_book->getId()));
        }

        function testAddAuthor()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Little Dog";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            $name = "Ben";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            //Act
            $test_book->addAuthor($test_author);

            //Assert
            $this->assertEquals($test_book->getAuthors(), [$test_author]);
        }

        function testGetAuthors()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $name = "Ben";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            $name2 = "Jen";
            $id2 = 2;
            $test_author2 = new Author($name2, $id2);
            $test_author2->save();

            //Act
            $test_book->addAuthor($test_author);
            $test_book->addAuthor($test_author2);

            //Assert
            $this->assertEquals($test_book->getAuthors(), [$test_author, $test_author2]);
        }

        function testDelete()
        {
            //Arrange
            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $name = "Ben";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            //Act
            $test_book->addAuthor($test_author);
            $test_book->delete();

            //Assert
            $this->assertEquals([], $test_author->getBooks());
        }
    }
?>
