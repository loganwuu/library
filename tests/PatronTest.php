<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Patron.php";
    require_once "src/Copy.php";
    require_once "src/Checkout.php";


    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Patron::deleteAll();
            Copy::deleteAll();
            Checkout::deleteAll();
        }

        function testSave() {

            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            //Act
            $result = Patron::getAll();

            //Assert
            $this->assertEquals($test_patron, $result[0]);
        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $name2 = "Sally Sue";
            $id2 = 2;
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();

            //Act
            Patron::deleteAll();
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $new_name = "Sally Sue";

            //Act
            $test_patron->update($new_name);

            //Assert
            $this->assertEquals("Sally Sue", $test_patron->getName());
        }

        function testFind()
        {
            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $name2 = "Sally Sue";
            $id2 = 2;
            $test_patron2 = new Patron($name, $id);
            $test_patron2->save();

            //Act
            $result = Patron::find($test_patron2->getId());

            //Assert
            $this->assertEquals($test_patron2, $result);
        }

        function testAddCheckout()
        {
            //Arrange
            $book_id = 1;
            $id = 1;
            $test_copy = new Copy($book_id, $id);
            $test_copy->save();

            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $copy_id = $test_copy->getId();
            $patron_id = $test_patron->getId();
            $id = null;
            $due_date = "2015-09-20";
            $test_checkout = new Checkout($copy_id, $patron_id, $id, $due_date);

            //Act
            $test_patron->addCheckout($test_checkout);

            //Assert
            $this->assertEquals(1,count($test_patron->getCheckouts()));

        }

        function testGetCheckouts()
        {
            //Arrange
            $name = "Jim Bob";
            $id = 1;
            $test_patron = new Patron($name, $id);
            $test_patron->save();

            $book_id = 1;
            $id = 1;
            $test_copy = new Copy($book_id, $id);
            $test_copy->save();

            $book_id2 = 2;
            $id2 = 2;
            $test_copy2 = new Copy($book_id2, $id2);
            $test_copy2->save();

            $copy_id = $test_copy->getId();
            $patron_id = $test_patron->getId();
            $id = null;
            $due_date = "2015-09-30";
            $test_checkout = new Checkout($copy_id, $patron_id, $id, $due_date);

            $copy_id2 = $test_copy2->getId();
            $patron_id2 = $test_patron->getId();
            $id2 = null;
            $due_date2 = "2015-09-30";
            $test_checkout2 = new Checkout($copy_id2, $patron_id2, $id2, $due_date2);

            // $copy_id2 = $test_copy2->getId();
            // $patron_id2 = $test_patron->getId();
            // $id2 = null;
            // $due_date2 = "2015-09-31";
            // $test_checkout2 = new Checkout($copy_id2, $patron_id2, $id2, $due_date2);

            //Act
            $test_patron->addCheckout($test_checkout2);
            $test_patron->addCheckout($test_checkout);

            $result = $test_patron->getCheckouts();
            var_dump($result);

            //Assert
            $this->assertEquals(2,count($test_patron->getCheckouts()));
        }

        // function testDelete()
        // {
        //     //Arrange
        //     $book_id = 1;
        //     $id = 1;
        //     $test_copy = new Copy($book_id, $id);
        //     $test_copy->save();
        //
        //     $name = "Jim Bob";
        //     $id = 1;
        //     $test_patron = new Patron($name, $id);
        //     $test_patron->save();
        //
        //     $copy_id = 1;
        //     $patron_id = 1;
        //     $id = 1;
        //     $due_date = "2015-09-20";
        //     $test_checkout = new Checkout($copy_id, $patron_id, $id, $due_date);
        //     $test_checkout->save();
        //
        //     //Act
        //     $test_patron->addCheckout($test_checkout);
        //     $test_patron->delete();
        //
        //     //Assert
        //     $this->assertEquals([], $test_patron::find($id));
        // }



    }

?>
