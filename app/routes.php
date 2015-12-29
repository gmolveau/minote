<?php

$app->register(new Silex\Provider\SessionServiceProvider());

// arrivée standard
$app->get('/', function () use ($app) { 
    require '../src/model_index.php';
    $url = generateUrl();
    return $app->redirect('/'.$url);
});

//arrivée avec url deja connue
$app->get('/{url}', function($url) use ($app) {
    require '../src/model_index.php';
    if( checkUrl($url) ){
      if( viewProtected($url) ){
        return $app->redirect('/'.$url.'/view');
      }
      else{
        return $app->redirect('/'.$url.'/view');
      }
    }
    else {
      $app->abort(404, "l'url \" $url \" is not a valid one. Must be alphanumeric and less than 10 characters.");
    }
});

$app->get('/{url}/view', function($url) use ($app) {
    require '../src/model_index.php';
    $reponse=traiterUrl($url);
    return $app['twig']->render('view_preventions'.$type.'.html.twig');
});

$app->post('/{url}/view', function($url, Request $request) use($app)
{
    $pwd = $request->get('password'); //on recupere le mot de passe de la requete POST
    // tester si valide
    //TODO: authenticate
 
    $app['session']->set('user', array('username'=> $usr));
    return $app['twig']->render('postLogin.html', array('username' => $usr));
});

$app->get('/{url}/edit', function() use ($app) {
    require '../src/model_index.php'; //appel du model
    $reponse=traiterUrl($url);
    return $app['twig']->render('view_preventions'.$type.'.html.twig');
});

$app->post('/{url}/edit', function($url, Request $request) use($app) {
  require '../src/model_note_edit.php';
    $type = $request->get('type');
    $password = $request->get('password');
    if ($type == "view") {
      protectView($url,$password);      
    }
    else{
      protectEdit($url,$password)
    }
    //TODO: authenticate
 
    $app['session']->set('user', array('username'=> $usr));
    return $app['twig']->render('postLogin.html', array('username' => $usr));
 
});
$app->put('/{url}/edit', function($url, Request $request) use($app) {
  $content = $request->get('content');
}


/* Toutes les notes */
$app->get('/notes', function () use ($app) {
    require '../src/model_notes.php'; //appel du model
    $all_notes = get_all_notes(); // appel de la fonction pour récupérer la liste des notes
    return $app['twig']->render('view_notes.html.twig', array('all_notes' => $all_notes)); //appel du view
});


// $app->register(new Silex\Provider\UrlGeneratorServiceProvider());

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

$app->get('/', function() use($app)
{
    if(null === $user = $app['session']->get('user'))
        return $app->redirect('/login');
 
    return $app['twig']->render('main.html', array('name' => $user['username']));
});

$app->get('/login', function() use($app)
{
    return $app['twig']->render('getLogin.html');
});
 
$app->post('/login', function(Request $request) use($app)
{
    $usr = $request->get('username');
    $pas = $request->get('password');
 
    //TODO: authenticate
 
    $app['session']->set('user', array('username'=> $usr));
    return $app['twig']->render('postLogin.html', array('username' => $usr));
 
});
 
$app->get('/logout', function() use($app)
{
    $app['session']->set('user', null);
    return $app['twig']->render('logout.html', array());
 
});

$app->get('/blog/{postId}/{commentId}', function ($postId, $commentId) {
    // ...
});


*/
