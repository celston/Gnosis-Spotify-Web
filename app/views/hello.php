<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular.min.js"></script>
</head>
<body>
	<div class="container">
        <div ng-app="foo">
            <div ng-controller="FirstController" ng-cloak>
                <table class="table table-striped table-bordered small">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Artist</th>
                            <th>Album</th>
                            <th>Track</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="track in starredPlaylist">
                            <td>{{$index + 1}}</td>
                            <td>{{track.artists[0].name}}</td>
                            <td>{{track.album.name}}</td>
                            <td>{{track.name}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
	</div>

    <script>
        var app = angular.module('foo', []);
        app.controller('FirstController', ['$scope', '$log', '$http', function ($scope, $log, $http) {
            $log.debug('check');
            $scope.message = 'Hello, World!';

            $http({
                method: 'GET',
                url: '/proxy/spotify/mystarredplaylist'
            }).success(function (response) {
                $scope.starredPlaylist = response.items.map(function (item) {
                    return item.track;
                })
            });
        }]);
    </script>
</body>
</html>
