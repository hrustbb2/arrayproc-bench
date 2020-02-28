<?php

namespace Bench;

use Lib\Models\Book;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Query\Builder;
use hrustbb2\arrayproc\ArrayProcessor;
use Lib\Entities\Book as BookEntity;

/**
 * @BeforeMethods({"init"})
 */
class Bench {

    private $connection;

    public function init()
    {
        $host = 'db';
        $db   = 'dbname';
        $user = 'mariadb_user';
        $pass = 'mariadb_user_password';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new \PDO($dsn, $user, $pass, $opt);

        $this->connection = new MySqlConnection($pdo, 'dbname');
        $connResolver = new ConnectionResolver();
        $connResolver->addConnection('db', $this->connection);
        Book::setConnectionResolver($connResolver);
    }

    /**
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function benchEloquent()
    {
        Book::with('authors')->get();
    }

    /**
     * @Revs(10)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function benchEloquentId()
    {
        $id = rand(1, 3000);
        Book::with('authors')->where('books.id', '=', $id)->get();
    }

    /**
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function benchProc()
    {
        $builder = new Builder($this->connection);
        $books = $builder->select([
                'books.id AS book_id',
                'books.name AS book_name',
                'authors.id AS author_id',
                'authors.name AS author_name',
            ])
            ->from('books')
            ->leftJoin('relations', 'relations.book_id', '=', 'books.id')
            ->leftJoin('authors', 'authors.id', '=', 'relations.author_id')
            ->get()
            ->all();

        $conf = [
            'prefix' => 'book_',
            'author' => [
                'prefix' => 'author_',
            ]
        ];
        $arrayProcessor = new ArrayProcessor();
        $booksData = $arrayProcessor->process($conf, $books)->resultArray();
        $books = [];
        foreach ($booksData as $bookData){
            $book = new BookEntity();
            $book->load($bookData);
            $books[] = $book;
        }
    }

    /**
     * @Revs(10)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function benchProcId()
    {
        $id = rand(1, 3000);
        $builder = new Builder($this->connection);
        $books = $builder->select([
            'books.id AS book_id',
            'books.name AS book_name',
            'authors.id AS author_id',
            'authors.name AS author_name',
        ])
            ->from('books')
            ->leftJoin('relations', 'relations.book_id', '=', 'books.id')
            ->leftJoin('authors', 'authors.id', '=', 'relations.author_id')
            ->where('books.id', '=', $id)
            ->get()
            ->all();

        $conf = [
            'prefix' => 'book_',
            'author' => [
                'prefix' => 'author_',
            ]
        ];
        $arrayProcessor = new ArrayProcessor();
        $booksData = $arrayProcessor->process($conf, $books)->resultArray();
        $book = new BookEntity();
        $book->load($booksData[0]);
    }

}