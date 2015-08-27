<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    // require_once "src/Author.php";
    require_once "src/Checkout.php";


    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CheckoutTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Patron::deleteAll();
            Copy::deleteAll();
            Checkout::deleteAll();
        }

        function testSave() {

            //Arrange
            $copy_id = 1;
            $patron_id = 1;
            $id = 1;
            $due_date = '2015-10-10';
            $test_checkout = new Checkout($copy_id, $patron_id, $due_date, $id);
            $test_checkout->save();

            //Act
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals($test_checkout, $result[0]);
        }

        // function testDeleteAll()
        // {
        //     //Arrange
        //     $name = "JK Rowling";
        //     $id = 1;
        //     $test_author = new Author($name, $id);
        //     $test_author->save();
        //
        //     $name2 = "George RR Martin";
        //     $id2 = 2;
        //     $test_author2 = new Author($name, $id);
        //     $test_author2->save();
        //
        //     //Act
        //     Author::deleteAll();
        //     $result = Author::getAll();
        //
        //     //Assert
        //     $this->assertEquals([], $result);
        // }
        //
        // function testUpdate()
        // {
        //     //Arrange
        //     $name = "JK Rowling";
        //     $id = 1;
        //     $test_author = new Author($name, $id);
        //     $test_author->save();
        //
        //     $new_name = "George RR Martin";
        //
        //     //Act
        //     $test_author->update($new_name);
        //
        //     //Assert
        //     $this->assertEquals("George RR Martin", $test_author->getName());
        // }
        //
        // function testFind()
        // {
        //     //Arrange
        //     $name = "JK Rowling";
        //     $id = 1;
        //     $test_author = new Author($name, $id);
        //     $test_author->save();
        //
        //     $name2 = "George RR Martin";
        //     $id2 = 2;
        //     $test_author2 = new Author($name, $id);
        //     $test_author2->save();
        //
        //     //Act
        //     $result = Author::find($test_author2->getId());
        //
        //     //Assert
        //     $this->assertEquals($test_author2, $result);
        // }
        //
        // function testAddBook()
        // {
        //     //Arrange
        //     $title = "Harry Potter";
        //     $id = 1;
        //     $test_book = new Book($title, $id);
        //     $test_book->save();
        //
        //     $name = "JK Rowling";
        //     $id = 1;
        //     $test_author = new Author($name, $id);
        //     $test_author->save();
        //
        //     //Act
        //     $test_author->addBook($test_book);
        //
        //     //Assert
        //     $this->assertEquals($test_author->getBooks(),[$test_book]);
        //
        // }
        //
        // function testGetBooks()
        // {
        //     //Arrange
        //     $name = "JK Rowling";
        //     $id = 1;
        //     $test_author = new Author($name, $id);
        //     $test_author->save();
        //
        //     $title = "Harry Potter";
        //     $id = 1;
        //     $test_book = new Book($title, $id);
        //     $test_book->save();
        //
        //     $title2 = "Moby Dick";
        //     $id2 = 2;
        //     $test_book2 = new Book($title, $id);
        //     $test_book2->save();
        //
        //     //Act
        //     $test_author->addBook($test_book);
        //     $test_author->addBook($test_book2);
        //
        //     $result = $test_author->getBooks();
        //
        //     //Assert
        //     $this->assertEquals([$test_book, $test_book2], $result);
        // }
        //
        // function testDelete()
        // {
        //     //Arrange
        //     $title = "Harry Potter";
        //     $id = 1;
        //     $test_book = new Book($title, $id);
        //     $test_book->save();
        //
        //     $name = "JK Rowling";
        //     $id = 1;
        //     $test_author = new Author($name, $id);
        //     $test_author->save();
        //
        //     //Act
        //     $test_author->addBook($test_book);
        //     $test_author->delete();
        //
        //     //Assert
        //     $this->assertEquals([], $test_book->getAuthors());
        // }



    }

?>
