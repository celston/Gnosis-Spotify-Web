<?php

class SpotifyProxyController extends BaseController {
    private $accessToken;

    public function __construct() {
        $this->beforeFilter('@checkSession');
    }

    public function getMe() {
        $api = $this->getApi();

        return Response::json($api->me());
    }

    public function getMysavedtracks() {
        $api = $this->getApi();

        return Response::json($api->getMySavedTracks());
    }

    public function getMyplaylists() {
        $api = $this->getApi();
        $me = $api->me();

        return Response::json($api->getUserPlaylists($me->id));
    }

    public function getMystarredplaylist() {
        $api = $this->getApi();
        $me = $api->me();

        return Response::json($api->getUserStarredPlaylist($me->id));
    }

    public function getSearch($type, $query) {
        $api = $this->getApi();
        return Response::json($api->search($query, $type));
    }

    private function getApi() {
        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken($this->accessToken);

        return $api;
    }

    public function checkSession() {
        $this->accessToken = Session::get('SPOTIFY_ACCESS_TOKEN');
        if (is_array($this->accessToken)) {
            $this->accessToken = $this->accessToken[0];
            Session::put('SPOTIFY_ACCESS_TOKEN', $this->accessToken);
        }
        if (!$this->accessToken) {
            $session = new \SpotifyWebAPI\Session('f33dad46558f45e184b2a8fc0d4ad519', 'eacb03c7675b49a68d43cd30d4c4cabb', 'http://spotifyfindgnosis/auth');
            return Redirect::to($session->getAuthorizeUrl(array('scope' => array('playlist-read-private', 'playlist-modify-public', 'playlist-modify-private', 'streaming', 'user-library-read', 'user-library-modify', 'user-read-private', 'user-read-email'))));
        }
    }
} 