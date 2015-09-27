<?php
/**
 * write by vyouzhi 
 */

include_once 'bootstrap.php';

$router = new AltoRouter();
$router->setBasePath(ROOT);

foreach ($routeMap as $value) {
	$router->map($value['method'], $value['uri'],  $value['params'], $value['module']);
}

$router->AutoLoad();

$match = $router->match();

$router->httpCode301();

//$router->httpCode404($match);

routing($match['action']);


if(class_exists($match['action'])){
	$show = new $match['action'];	
	$show->Show();	
}else {
    require_once(Lib.'/error/e404.php');  
    $show = new e404();
    $show->Show();
	//如果没有就直接 404
	//$router->httpCode404(false);
}
?>

