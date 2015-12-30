<?php

$app->register(new Silex\Provider\SessionServiceProvider());

// arrivée standard
$app->get('/', function () use ($app) { 
    require '../src/model_index.php';
    $url = generateUrl();
    return $app->redirect('/milinks/web/index.php/'.$url);
});

//arrivée avec url deja connue
$app->get('/{url}', function($url) use ($app) {
    require '../src/model_index.php';
    if( checkUrl($url) ){
      return $app->redirect('/milinks/web/index.php/'.$url.'/view');
    }
    else {
      $app->abort(404, "l'url \" $url \" is not a valid one. Must be alphanumeric and less than 10 characters.");
    }
});


$app->get('/{url}/view', function($url) use ($app) {
    require '../src/model_note_view.php';
    if( (isset($session) and $session->get('id')==$url and $session->get('view')) or !isViewProtected($url) ){
      $content=getContent($url);
      return $app['twig']->render('view_note_view.html.twig',array('content' => $content));
    }
    else{
      return $app['twig']->render('view_note_view_protected.html.twig');
    }
});

$app->post('/{url}/view', function($url, Request $request) use($app){
  $password = $request->get('password'); //on recupere le mot de passe de la requete POST
  if( verifyPassword($url,$pwd) ){
    if(isset($session) and $session->get('id')==$url){
      $session->set('view', True);
    }
    else{
      $session = new Session();
      $session->start();
      // set and get session attributes
      $session->set('id', $url);
      $session->set('view', True);
      $session->set('edit', False);
    }
  }
  return $app->redirect('/milinks/web/index.php/'.$url.'/view');
});


$app->get('/{url}/edit', function() use ($app) {
  require '../src/model_note_edit.php'; //appel du model
  if( (isset($session) and $session->get('id')==$url and $session->get('edit')) or !isEditProtected($url) ){
      $content=getContent($url);
      return $app['twig']->render('view_note_edit.html.twig',array('content' => $content));
  }
  else{
    return $app['twig']->render('view_note_edit_protected.html.twig');
  }
});

$app->post('/{url}/edit', function($url, Request $request) use($app) { 
  require '../src/model_note_edit.php';
  $type = $request->get('type'); //login | protectView | protectEdit | changeUrl
  switch($type) {
    case "login" :
      $password = $request->get('password');
      if (verifyPassword($url,$pwd)) {
        if(isset($session) and $session->get('id')==$url){
          $session->set('edit', True);
        }
        else{
          $session = new Session();
          $session->start();
          // set and get session attributes
          $session->set('id', $url);
          $session->set('view', False);
          $session->set('edit', True);
        }
        return $app->redirect('/milinks/web/index.php/'.$url.'/edit');
      }
      else{
        $app->abort(401, "password incorrect");
      }
    case "protectView" :
      $password = $request->get('password');
      protectView($url,$password);
      break;
    case "protectEdit" :
      $password = $request->get('password');
      protectEdit($url,$password);
      break;
    case "changeUrl" :
      $new_url = $request->get('new_url');
      changeUrl($url,$new_url);
      return $app->redirect('/milinks/web/index.php/'.$new_url.'/view');
    default :
      $app->abort(403, "wallah le problème");
  }
});


$app->put('/{url}/edit', function($url, Request $request) use($app) {
  if( (isset($session) and $session->get('id')==$url and $session->get('edit')) or !isEditProtected($url) ){
    $content = $request->get('content');
    updateNote($url,$content);
    return True;
  }
  else{
    return False;
  }
});

/* Toutes les notes */
$app->get('/notes', function () use ($app) {
    require '../src/model_notes.php'; //appel du model
    $all_notes = get_all_notes(); // appel de la fonction pour récupérer la liste des notes
    return $app['twig']->render('view_notes.html.twig', array('all_notes' => $all_notes)); //appel du view
});


/*

DANS LA VARIABLE $_SESSION il y aura :
'id' [str] qui sera l'id de la note
'view' [bool]
'edit' [bool]

*/
