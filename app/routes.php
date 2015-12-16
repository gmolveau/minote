<?php

// Home page
// exemple de route

// $app->register(new Silex\Provider\SessionServiceProvider());

// Home page
$app->get('/', function () use ($app) {
    // require '../src/model.php'; //appel du model
    // $articles = getArticle(); / appel de la fonction pour récupérer ce dont on a besoin
    return $app['twig']->render('index.html.twig'); //appel du view
});

// $app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->get('/prevention', function() use ($app) {
	require '../src/model_prevention.php'; //appel du model
	$all_cat = get_all_cat();
	return $app['twig']->render('view_prevention.html.twig', array('all_cat' => $all_cat));
});

$app->get('/prevention/{type}', function() use ($app) {
    require '../src/model_preventions'.$type.'.php'; //appel du model
    $crise = getTypeCrise($type);
    return $app['twig']->render('view_preventions'.$type.'.html.twig');
});

/*
 $app->post('/', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request) {
  
      $name = $request->get('name');
      $quantity = $request->get('quantity');
      $description = $request->get('description');
      $image = $request->get('image');
      
      // Code to add the toy into the toy db
    // and return a toy id
     //$toy_id = create_toy($name, $quantity, $description, $image);
     //$toy = get_toy($toy_id);
     
     // For now lets just assume we have saved it
     $toy = array(
         '00003' => array(
             'name' => $name,
             'quantity' => $quantity,
             'description' => $description,
             'image' => $image,
         )
     );
     
     // Useful to return the newly added details
// HTTP_CREATED = 200
 return new Symfony\Component\HttpFoundation\Response(json_encode($toy), HTTP_CREATED);
 });

*/
?>