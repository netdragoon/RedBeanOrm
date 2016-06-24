<?php
use Repositories\RepositoryCategory;
use Repositories\RepositoryPeople;

include 'rb.php';
include 'Models/people.php';
include 'Models/category.php';
include 'Repositories/Repository.php';
include 'Repositories/RepositoryCategory.php';
include 'Repositories/RepositoryPeople.php';

R::setup('mysql:host=localhost;dbname=test1','root','senha');
R::setAutoResolve(TRUE);

//$repCategory = new RepositoryCategory();
$repPeople = new RepositoryPeople();

//$repCategory->insert(array('description'=>'UOL - UNIVERSO ONLINE'));
//$model = $repCategory->find(2);


//$orderBy = "WHERE name LIKE ? ORDER BY name ASC LIMIT 10";

//foreach($repPeople->all($orderBy, array('%can%')) as $item)
//{
 //   printf('<p>%s %s</p>', $item->id, $item->name);
//}

$result = $repPeople->page('ORDER BY id ASC', 2, 2, 'WHERE name LIKE ?', ['%can%']);

var_dump($result);


//var_dump($repPeople->all());



	//create

	/*$people = R::dispense('people');	
	$people->name = "ROMÁRIO DOS SANTOS";	
	$people->status = 1;
	$people->data = date_create('now');
	

	var_dump($people);return;
	/*
	$items = R::find('people');

	foreach ($items as $item) 
	{
		printf('<div>%s %s %s</div>', $item->id, $item->name, $item->status);
	}

*/
	//find/edit/update
/*
	$id = 1;
	$people = R::load('people', $id);
	if ($people)
	{
		$people->name = "Fúlvio C. Canducci Dias";
		R::store($people);
	}
*/
