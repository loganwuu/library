<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Checkout.php";

    $app = new Silex\Application();

    $app['debug']=true;

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //homepage
    $app->get("/", function() use($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/books", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books'=>Book::getAll()));
    });

    $app->get("/librarian", function() use ($app) {
        return $app['twig']->render('librarian.html.twig');
    });

    $app->get("/patrons", function() use ($app) {
        return $app['twig']->render('patrons.html.twig', array('patrons'=>Patron::getAll()));
    });

    $app->get("/patron/{id}", function($id) use ($app) {
        $patron=Patron::find($id);
        return $app['twig']->render('patron.html.twig', array('patron'=>$patron));
    });

    $app->post("/patrons", function() use ($app) {
        $patron = new Patron($_POST['name']);
        $patron->save();
        return $app['twig']->render('patrons.html.twig', array('patrons'=>Patron::getAll()));
    });

    $app->post("/books", function() use ($app) {
        // $name = $_POST['name'];
        $author = new Author($_POST['name']);
        $author->save();
        $title = $_POST['title'];
        $book = new Book($title);
        $book->save();
        $book->addAuthor($author);
        return $app['twig']->render('books.html.twig', array('books'=>Book::getAll()));
    });

    $app->post('/add_author', function() use ($app) {
        $author = new Author($_POST['name']);
        $author->save();
        $book = Book::find($_POST['book_id']);
        $book->addAuthor($author);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'author'=>$author, 'copies'=> Copy::getAll(), 'patrons'=>Patron::getAll(), 'checkouts'=>Checkout::getAll()));
    });

    $app->get("/book/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $author = $book->getAuthors();
        $copies = Copy::findCopies($id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'author'=>$author, 'copies'=> $copies, 'patrons'=>Patron::getAll(), 'checkouts'=>Checkout::getAll()));
    });

    $app->post("/add_copy", function() use ($app) {
        $book_id = $_POST['book_id'];
        $book = Book::find($book_id);
        $author = $book->getAuthors();
        $copy = new Copy($book_id);
        $copy->save();
        $copies = Copy::findCopies($book_id);
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'author'=>$author, 'copies'=> $copies, 'patrons'=>Patron::getAll(), 'checkouts'=>Checkout::getAll()));
    });

    $app->patch("/checkout_copy/{id}", function($id) use ($app) {
        $checkout = new Checkout($_POST['copy_id'], $_POST['patron_id'], $_POST['due_date']);
        $checkout->save();
        $book_id = $_POST['book_id'];
        $book = Book::find($book_id);
        $author = $book->getAuthors();
        return $app['twig']->render('book.html.twig', array('book'=>$book, 'author'=>$author, 'copies'=> Copy::getAll(), 'patrons'=>Patron::getAll(), 'checkouts'=>Checkout::getAll()));
    });

    // $app->patch("/checkout_copy/{id}", function($id) use ($app) {
    //     $book = Book::find($id);
    //     $author = $book->getAuthors();
    //     $copy_id = $_POST['copy_id'];
    //     $copy = Copy::find($copy_id);
    //     //$checkout = new Checkout($copy_id, $)
    //     $copy->update($_POST['due_date']);
    //     $copies = Copy::findCopies($id);
    //     return $app['twig']->render('book.html.twig', array('book'=>$book, 'author'=>$author, 'copies'=> $copies, 'patrons'=>Patron::getAll()));
    // });

    $app->patch("/patron/{id}", function($id) use ($app) {
        $patron = Patron::find($id);
        $patron->update($_POST['name']);
        return $app['twig']->render('patron.html.twig', array('patron'=>$patron));
    });

    $app->delete("/patron/{id}", function($id) use ($app) {
        $patron = Patron::find($id);
        $patron->delete();
        return $app['twig']->render('patrons.html.twig', array('patrons'=>Patron::getAll()));
    });

    return $app;
?>
