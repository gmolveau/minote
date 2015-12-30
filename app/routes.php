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
      return $app->redirect('/'.$url.'/view');
    }
    else {
      $app->abort(404, "l'url \" $url \" is not a valid one. Must be alphanumeric and less than 10 characters.");
    }
});

// la view pour chaque note
$app->get('/{url}/view', function($url) use ($app) {
    require '../src/model_note_view.php';
    if( (isset($session) and $session->get('id')==$url and $session->get('view')) or !isViewProtected($url) ){
    // si une session existe ET si le parametre 'id' de cette session est egal à l'url 
    // ET si le parametre 'view' qui est un booléen est True; OU ALORS si la view n'est PAS protected
      $content=getContent($url);
      return $app['twig']->render('view_note_view.html.twig',array('content' => $content));
    }
    else{
    //sinon : donc si pas de session ou pas pas le bon id ou pas le droit de view ou si elle est protected
      return $app['twig']->render('view_note_view_protected.html.twig');
    }
});

// la méthode POST sur la view de chaque note
// la seule methode POST qui aura lieu sur la view d'une note est le LOGIN
$app->post('/{url}/view', function($url, Request $request) use($app){
  $password = $request->get('password'); //on recupere le mot de passe de la requete POST
  if( verifyPassword($url,$pwd) ){
  // on verifie si le password entré est égal à celui de la DB
    if(isset($session) and $session->get('id')==$url){
    // si la session existe deja avec le parametre id egal à l'url
      $session->set('view', True); //on modifie juste son droit sur le 'view'
    }
    else{
    // sinon donc la session existe pas OU l'id nest pas le bon
      $session = new Session();
      $session->start();
      // on crée la session et on rentre les parametres
      $session->set('id', $url);
      $session->set('view', True);
      $session->set('edit', False);
    }
  }
  return $app->redirect('/'.$url.'/view'); //dans tous les cas on redirige vers la view elle même 
});

// l'edit pour chaque note
$app->get('/{url}/edit', function() use ($app) {
  require '../src/model_note_edit.php'; //appel du model
  if( (isset($session) and $session->get('id')==$url and $session->get('edit')) or !isEditProtected($url) ){
  // si une session existe ET si le parametre 'id' de cette session est egal à l'url 
  // ET si le parametre 'edit' qui est un booléen est True; OU ALORS si l'edit' n'est PAS protected
      $content=getContent($url);
      return $app['twig']->render('view_note_edit.html.twig',array('content' => $content));
  }
  else{
    return $app['twig']->render('view_note_edit_protected.html.twig');
  }
});

// la méthode POST sur la view de chaque note
// il y'aura plusieurs POST possibles vers cette page cest pour cela qu'on fait un switch
$app->post('/{url}/edit', function($url, Request $request) use($app) { 
  require '../src/model_note_edit.php';
  $type = $request->get('type'); // 'type' sera un champ caché dans tous les formulaires
  // on fera varier sa valeur selon le cas
  // login | protectView | protectEdit | changeUrl
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
        return $app->redirect('/'.$url.'/edit');
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
      return $app->redirect('/'.$new_url.'/view');
    default :
      $app->abort(403, "wallah le problème");
  }
});

// methode PUT pour l'edit de chaque note
$app->put('/{url}/edit', function($url, Request $request) use($app) {
  if( (isset($session) and $session->get('id')==$url and $session->get('edit')) or !isEditProtected($url) ){
  // on verifie si l'utilisateur qui envoie cette requete a le droit de faire cet update
    $content = $request->get('content');
    updateNote($url,$content);
    return True;
  }
  else{
    return False; // sinon on arrete
  }
});

// page demandée par le professeur
$app->get('/notes', function () use ($app) {
    require '../src/model_notes.php'; //appel du model
    $all_notes = get_all_notes(); // appel de la fonction pour récupérer la liste des notes
    return $app['twig']->render('view_notes.html.twig', array('all_notes' => $all_notes)); //appel du view
});