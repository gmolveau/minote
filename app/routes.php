<?php
// arrivée standard
$app->get('/', function() use ($app)
{
    require '../src/model_index.php';
    $url = generateUrl();
    return $app->redirect('/' . $url);
});

//arrivée avec url deja connue
$app->get('/{url}', function($url) use ($app)
{
    if ($url == "notes") {
        require '../src/model_notes.php'; //appel du model
        $all_notes = get_all_notes(); // appel de la fonction pour récupérer la liste des notes
        return $app['twig']->render('view_notes.html.twig', array(
            'all_notes' => $all_notes
        )); //appel du view
    } else {
        require '../src/model_index.php';
        if (checkUrl($url)) {
            return $app->redirect('/' . $url . '/edit');
        } else {
            $app->abort(404, "l'url \" $url \" is not a valid one. Must be alphanumeric and less than 10 characters.");
        }
    }
});

// la view pour chaque note
$app->get('/{url}/view', function($url) use ($app)
{
    require '../src/model_note_view.php';
    if (!isViewProtected($url)) {
        $content = getContent($url);
        return $app['twig']->render('view_note_view.html.twig', array(
            'content' => $content,
            'url' => $url
        ));
    } else {
        if (isset($app['session'])) {
            if ($app['session']->get('id') == $url) {
                if ($app['session']->get('view')) {
                    $content = getContent($url);
                    return $app['twig']->render('view_note_view.html.twig', array(
                        'content' => $content,
                        'url' => $url
                    ));
                }
            }
        }
        return $app['twig']->render('view_note_protected.html.twig', array(
            'type' => 'view',
            'url' => $url
        ));
    }
    /*
    if( (isset( $app['session']) and  $app['session']->get('id')==$url and  $app['session']->get('view')) or !isViewProtected($url) ){
    // si une session existe ET si le parametre 'id' de cette session est egal à l'url 
    // ET si le parametre 'view' qui est un booléen est True; OU ALORS si la view n'est PAS protected
    $content=getContent($url);
    return $app['twig']->render('view_note_view.html.twig',array('content' => $content));
    }
    else{
    //sinon : donc si pas de session ou pas pas le bon id ou pas le droit de view ou si elle est protected
    return $app['twig']->render('view_note_protected.html.twig',array('type'=>'view'));
    }
    */
});

// la méthode POST sur la view de chaque note
// la seule methode POST qui aura lieu sur la view d'une note est le LOGIN
$app->post('/{url}/view', function($url) use ($app)
{
    require '../src/model_note_view.php';
    $password = $app['request']->get('password'); //on recupere le mot de passe de la requete POST
    if (verifyPassword($url, $password)) {
        // on verifie si le password entré est égal à celui de la DB
        if (isset($app['session']) and $app['session']->get('id') == $url) {
            // si la session existe deja avec le parametre id egal à l'url
            $app['session']->set('view', True); //on modifie juste son droit sur le 'view'
        } else {
            // sinon donc la session existe pas OU l'id nest pas le bon
            // on crée la session et on rentre les parametres
            $app['session']->set('id', $url);
            $app['session']->set('view', True);
            $app['session']->set('edit', False);
        }
        return true;
    }
    return $app->redirect('/' . $url . '/view'); //dans tous les cas on redirige vers la view elle même 
});

// l'edit pour chaque note
$app->get('/{url}/edit', function($url) use ($app)
{
    require '../src/model_note_edit.php'; //appel du model
    if (!isEditProtected($url)) {
        $content = getContent($url);
        return $app['twig']->render('view_note_edit.html.twig', array(
            'content' => $content,
            'url' => $url
        ));
    } else {
        if (isset($app['session'])) {
            if ($app['session']->get('id') == $url) {
                if ($app['session']->get('edit')) {
                    $content = getContent($url);
                    return $app['twig']->render('view_note_edit.html.twig', array(
                        'content' => $content,
                        'url' => $url
                    ));
                }
            }
        }
        return $app['twig']->render('view_note_protected.html.twig', array(
            'type' => 'login',
            'url' => $url
        ));
    }
});

// la méthode POST sur la view de chaque note
// il y'aura plusieurs POST possibles vers cette page cest pour cela qu'on fait un switch
$app->post('/{url}/edit', function($url) use ($app)
{
    require '../src/model_note_edit.php';
    $type = $app['request']->get('type'); // 'type' sera un champ caché dans tous les formulaires
    // on fera varier sa valeur selon le cas
    // login | protectView | protectEdit | changeUrl
    switch ($type) {
        case "login":
            $password = $app['request']->get('password');
            if (verifyPassword($url, $password)) {
                if (isset($app['session']) and $app['session']->get('id') == $url) {
                    $app['session']->set('edit', True);
                } else {
                    // set and get session attributes
                    $app['session']->set('id', $url);
                    $app['session']->set('view', False);
                    $app['session']->set('edit', True);
                }
                return true;
            } else {
                $app->abort(401, "password incorrect");
            }
        case "protectView":
            $password = $app['request']->get('password');
            protectView($url, $password);
            return true;
        case "protectEdit":
            $password = $app['request']->get('password');
            protectEdit($url, $password);
            return true;
        case "changeUrl":
            $new_url = $app['request']->get('new_url');
            changeUrl($url, $new_url);
            return true;
        case "save":
            if (!isEditProtected($url)) {
                $content = $app['request']->get('content');
                updateNote($url, $content);
                return True;
            } else {
                if ((isset($app['session']) and $app['session']->get('id') == $url and $app['session']->get('edit'))) {
                    $content = $app['request']->get('content');
                    updateNote($url, $content);
                    return True;
                } else {
                    return False; // sinon on arrete
                }
            }
        default:
            $app->abort(403, "wallah le problème");
    }
});
