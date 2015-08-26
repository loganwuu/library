<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Copy.php';
    require_once 'src/Patron.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Copy::deleteAll();
            Patron::deleteAll();
        }

        function testGetId()
        {
            //Arrange
            $book_id = 1;
            $due_date = '0000-00-00';
            $id = 1;
            $test_copy = new Copy($book_id, $due_date, $id);
            $test_copy->save();

            //Act
            $result = $test_copy->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $book_id = 1;
            $due_date = '0000-00-00';
            $id = 1;
            $test_copy = new Copy($book_id, $due_date, $id);

            //Act
            $test_copy->save();

            //Assert
            $result = Copy::getAll();
            $this->assertEquals($test_copy, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $book_id = 1;
            $due_date = '0000-00-00';
            $id = 1;
            $test_copy = new Copy($book_id, $due_date, $id);
            $test_copy->save();

            $book_id2 = 2;
            $due_date2 = '0000-00-00';
            $id2 = 2;
            $test_copy2 = new Copy($book_id2, $due_date2, $id2);
            $test_copy2->save();

            //Act
            $result = Copy::getAll();

            //Assert
            $this->assertEquals([$test_copy, $test_copy2], $result);

        }

        function testDeleteAll()
        {
            //Arrange
            $book_id = 1;
            $due_date = '0000-00-00';
            $id = 1;
            $test_copy = new Copy($book_id, $due_date, $id);
            $test_copy->save();

            $book_id2 = 2;
            $due_date2 = '0000-00-00';
            $id2 = 2;
            $test_copy2 = new Copy($book_id2, $due_date2, $id2);
            $test_copy2->save();

            //Act
            Copy::deleteALl();

            //Assert
            $result = Copy::getAll();
            $this->assertEquals([], $result);
        }

//Not working as now
        function testAddCheckoutCopy()
        {
            //Arrange
            $book_id = 1;
            $id = 1;
            $due_date = '0000-00-00';
            $test_copy = new Copy($book_id, $due_date, $id);
            $test_copy->save();

            $name = "Ben";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $due_date2 = '2222-01-22';

            //Act
            $test_copy->addCheckoutCopy($test_patron, $due_date2);

            //Assert
            $this->assertEquals($test_copy->getPatrons(), [$test_patron]);
        }

//Not working as now
        function testGetPatrons()
        {
            //Arrange
            $book_id = 1;
            $id = 1;
            $due_date = '0000-00-00';
            $test_copy = new Copy($book_id, $due_date, $id);
            $test_copy->save();

            $name = "Ben";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();
            $due_date = '2222-01-22';

            $name2 = "Jen";
            $id2 = 2;
            $test_patron2 = new Patron($name2, $id2);
            $test_patron2->save();
            $due_date2 = '1111-01-22';

            //Act
            $test_copy->addCheckoutCopy($test_patron, $due_date);
            $test_copy->addCheckoutCopy($test_patron2, $due_date2);
            $result = $test_copy->getPatrons();

            //Assert
            $this->assertEquals([[$test_patron, $due_date], [$test_patron2, $due_date2]], $result);
        }
    }
?>
