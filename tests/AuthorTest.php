<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Author.php";
    require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);

            //Act
            $result = $test_author->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function testSetName()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);

            //Act
            $test_author->setName("Steve Smith");
            $result = $test_author->getName();

            //Assert
            $this->assertEquals("Steve Smith", $result);
        }

        function testGetId()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            //Act
            $result = $test_author->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals($test_author, $result[0]);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();
            $new_name = "Steve Smith";

            //Act
            $test_author->update($new_name);

            //Assert
            $this->assertEquals("Steve Smith", $test_author->getName());
        }

        function testDeleteAuthor()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();
            $name2 = "Steve Smith";
            $id2 = 2;
            $test_author2 = new Author($name2, $id2);
            $test_author2->save();

            //Act
            $test_author->delete();

            //Assert
            $this->assertEquals([$test_author2], Author::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;

            $name2 = "Steve Smith";
            $id2 = 2;

            $test_author = new Author($name, $id);
            $test_author->save();
            $test_author2 = new Author($name, $id2);
            $test_author2->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;

            $name2 = "Steve Smith";
            $id2 = 2;

            $test_author = new Author($name, $id);
            $test_author->save();
            $test_author2 = new Author($name, $id2);
            $test_author2->save();

            //Act
            Author::deleteAll();
            $result = Author::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;

            $name2 = "Steve Smith";
            $id2 = 2;

            $test_author = new Author($name, $id);
            $test_author->save();
            $test_author2 = new Author($name, $id2);
            $test_author2->save();

            //Act
            $id3 = $test_author->getId();
            $result = Author::find($id3);

            //Assert
            $this->assertEquals($test_author, $result);
        }

        function testAddBook()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            //Act
            $test_author->addBook($test_book);

            //Assert
            $this->assertEquals($test_author->getBooks(),[$test_book]);
        }

        function testGetBooks()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Little Dog";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            $test_author->addBook($test_book);
            $test_author->addBook($test_book2);
            $result = $test_author->getBooks();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function testDelete()
        {
            //Arrange
            $name = "Paul Jones";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            $title = "Little Cat";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            //Act
            $test_author->addBook($test_book);
            $test_author->delete();

            //Assert
            $this->assertEquals([], $test_book->getAuthors());
        }
    }
?>
