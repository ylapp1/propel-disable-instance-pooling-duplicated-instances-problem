<?php
/**
 * @file
 * @version 0.1
 * @copyright 2022 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

require_once __DIR__ . "/vendor/autoload.php";

function dumpAuthorBooks(Author $_author): string
{
	$output = "";
	foreach ($_author->getBooks() as $book)
	{
		$output .= "- {$book->getTitle()}\n";
	}

	return $output;
}

Propel::init(__DIR__ . "/propel/conf/disable-instance-pooling-problem-conf.php");

$connection = null;
while (!$connection)
{
	echo 'Trying to connect to database ... ';
	try
	{
		$connection = Propel::getConnection();
		echo 'OK' . PHP_EOL;
	}
	catch (PropelException $exception)
	{
		echo PHP_EOL . 'Error: ' . $exception->getMessage() . PHP_EOL;
		sleep(1);
	}
}

echo `cd propel && ../vendor/bin/propel-gen insert-sql`;

Propel::disableInstancePooling();

// Save some data
echo "Setting up data ... ";
$author = AuthorQuery::create()->filterByName("first author")->findOneOrCreate();
$author->save();

$bookA = BookQuery::create()->filterByTitle("first book")->findOneOrCreate();
$bookB = BookQuery::create()->filterByTitle("second book")->findOneOrCreate();
$bookC = BookQuery::create()->filterByTitle("third book")->findOneOrCreate();

$bookA->setAuthor($author)->save();
$bookB->setAuthor($author)->save();
$bookC->setAuthor($author)->save();

echo "DONE\n";

echo "\n";

// First case: Many to one instance duplication
echo "# Case A: Many to one instance duplication\n";
$author = AuthorQuery::create()->findPk(1);
$author->getBooks(); // Load Book instances into Author
$firstBook = $author->getBooks()[0];
$newAuthorInstance = $firstBook->getAuthor(); // Load new Author instance
$newAuthorInstance->getBooks(); // Load new Book instances into other Author

echo sprintf("Original and new author object are %s\n", $author === $newAuthorInstance ? "equal" : "unequal");

echo "\n## Before save Book\n";
echo sprintf("Original author object has %d books:\n%s\n", count($author->getBooks()), dumpAuthorBooks($author));
echo sprintf("New author object has %d books:\n%s\n", count($newAuthorInstance->getBooks()), dumpAuthorBooks($newAuthorInstance));

$firstBook->save();

echo "\n## After save Book\n";
echo sprintf("Original author object has %d books:\n%s\n", count($author->getBooks()), dumpAuthorBooks($author));
echo sprintf("New author object has %d books:\n%s\n", count($newAuthorInstance->getBooks()), dumpAuthorBooks($newAuthorInstance));

echo "\n\n";

// Second case: One to many instance duplication
echo "# Case B: One to many instance duplication\n";
$book = BookQuery::create()->findPk(1);
$author = $book->getAuthor();
$author->getBooks(); // Load new Book instances into Author

echo "\n## Before save Book\n";
echo sprintf("Author object has %d books:\n%s\n", count($author->getBooks()), dumpAuthorBooks($author));

$book->save();

echo "\n## After save Book\n";
echo sprintf("Author object has %d books:\n%s\n", count($author->getBooks()), dumpAuthorBooks($author));
