<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	$accessToken = Session::get('SPOTIFY_ACCESS_TOKEN');
    if (is_array($accessToken)) {
        $accessToken = $accessToken[0];
        Session::put('SPOTIFY_ACCESS_TOKEN', $accessToken);
    }
    $validToken = false;
    $session = new \SpotifyWebAPI\Session('f33dad46558f45e184b2a8fc0d4ad519', 'eacb03c7675b49a68d43cd30d4c4cabb', 'http://spotifyfindgnosis/auth');
    if (!$accessToken) {
        return Redirect::to($session->getAuthorizeUrl(array('scope' => array('playlist-read-private', 'playlist-modify-public', 'playlist-modify-private', 'streaming', 'user-library-read', 'user-library-modify', 'user-read-private', 'user-read-email'))));
    }

    $api = new \SpotifyWebAPI\SpotifyWebAPI();

    $refresh = $session->getRefreshToken();

    $test = $api->setAccessToken($accessToken);

    return Response::json(array('test' => $test, 'api' => $api, 'refresh' => $refresh));
});

Route::get('/auth', function () {
    $session = new \SpotifyWebAPI\Session('f33dad46558f45e184b2a8fc0d4ad519', 'eacb03c7675b49a68d43cd30d4c4cabb', 'http://spotifyfindgnosis/auth');

    $session->requestToken($_GET['code']);
    Session::put('SPOTIFY_ACCESS_TOKEN', $session->getAccessToken());

    return Redirect::to('/');
});

Route::controller('/proxy/spotify', 'SpotifyProxyController');