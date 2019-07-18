Football
====

## Preparation

Execute commands from project folder
```
docker-compose build
docker-compose up -d
```


install vendors from f-php-fpm container

```
composer install
``` 

use this command to prepend the database for work
```
bin/console doctrine:fixtures:load
``` 

for authentication in secured endpoints use the *jwt* header with token value.
You can get the token via */token* endpoint

```
curl -X POST \
  http://localhost:8000/token \
  -H 'Content-Type: application/json' \
  -d '{
	"password": "admin",
	"email": "admin@admin.com"
}'
``` 

For this action *Get a list of football teams in given league* please use next data

```
curl -X GET \
  http://localhost:8000/league/1/team \
  -H 'Content-Type: application/json' \
  -H 'jwt: PLACE_YOUR_JWT_HERE'
```
For this action *Create a football team* please use next data

```
curl -X POST \
  http://localhost:8000/team \
  -H 'Content-Type: application/json' \
  -H 'jwt: PLACE_YOUR_JWT_HERE' \
  -d '{
	"name": "Command Name",
	"strip": "strip"
}'
```

For this action *Replace all attributes of a football team* please use next data
```
curl -X PUT \
  http://localhost:8000/team/PLACE_TEAM_ID_HERE \
  -H 'Content-Type: application/json' \
  -H 'jwt: PLACE_YOUR_JWT_HERE' \
  -d '{
	"name": "UpdatedName",
	"strip": "UpdatedStrip",
	"leagues": [1,3] \\WHERE 1 and 3 is the League ID\\
}'
```

For this action *Delete a football league* please use next data

```
curl -X DELETE \
  http://localhost:8000/league/PLACE_LEAGUE_ID_HERE \
  -H 'Content-Type: application/json' \
  -H 'jwt: PLACE_YOUR_JWT_HERE' \
```