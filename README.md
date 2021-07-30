# Utilisation du projet
## 1 - Prérequis
Installez les dernières versions des composants suivants :
* [Docker](https://docs.docker.com/install/)
* [Docker-compose](https://docs.docker.com/compose/install/)
* [Make / Makefile](https://www.gnu.org/software/make/manual/make.html)

Ces outils sont assez communs sous Linux et MacOs, si vous êtes sous Windows, je vous recommande d'installer docker dans [WSL](https://docs.microsoft.com/fr-fr/windows/wsl/install-win10).

## 2 - Technologies
Le projet est une api développée sur une stack php 8 / postgresql 13 sous le framework Symfony 5.3.6.

Ma stack docker est minimaliste, j'utilise php en mode cli, le service php n'est donc pas permanent et n'est relancé qu'en temps voulu.

Pour lancer le serveur, j'utilise le server web interne à php qui n'est bien sur pas adapté à la production mais qui "fit" bien avec l'objectif de l'exercice.

Je n'utilise plus le server local de symfony car depuis la version 5, la commande a été déplacée vers la cli symfony que je ne souhaite pas utiliser.

## 3 - Make
Le projet utilise make comme **task runner**, chaque commande (docker ou autres) habituellement lancée en ligne de commande est wrappée dans les targets du makefile.

Si vous ne désirez pas utiliser make, rien ne vous y oblige il suffit de copier chaque commande de la target correspondante. 

Attention tout de même de bien remplacer les variables adéquates telles que les variables **${UID}** et **${GID}**.
En effet, certaines commandes qui génèrent des fichiers sont utilisées avec un utilisateur ayant les même uid/gid du host afin d'éviter des conflits de droit owner.

## 4 - Lancement de l'application
### Le Build
On commence par build l'image docker de php.
Pour information, j'installe globalement dans l'image docker certains outils de tests et d'analyse en suivant les [recommandations de Sebastian Bergmann](https://twitter.com/s_bergmann/status/999635212723212288).

```bash
$ make
```

#### L'Installation des dépendances

```bash
$ make install
```

#### Lancement de la stack

```bash
$ make up
```

### Initialisation de la base de données
Cette commande installe la base de données, lance les migrations et insert quelques fixtures de test.

Avant tout, bien vérifier que le service est lancé:

```bash
$ docker ps

CONTAINER ID   IMAGE                  COMMAND                  CREATED              STATUS              PORTS                      NAMES
9988aa8adb15   postgres:13.3-alpine   "docker-entrypoint.s…"   About a minute ago   Up About a minute   127.0.0.1:5432->5432/tcp   lbc_database_1

 ```

Et que l'initialisation du premier démarrage du service soit terminé :

```bash
$ make logs

database_1  | 2021-07-30 22:41:27.756 UTC [7] LOG:  listening on Unix socket "/var/run/postgresql/.s.PGSQL.5432"
database_1  | 2021-07-30 22:41:27.771 UTC [22] LOG:  database system was shut down at 2021-07-30 22:41:24 UTC
database_1  | 2021-07-30 22:41:27.777 UTC [7] LOG:  database system is ready to accept connections

```

```bash
$ make init
```

### LE RUN

#### Lancement du serveur

Pour lancer le serveur en mode dev :
```bash
$ make server-dev
```
En mode prod :
```bash
$ make server-prod
```

### Tests
J'ai séparé les tests en 3 packages :
* Core : test de la layer applicative qui n'utilise pas la base de données : La partie infrastructure est mockée par prophecy.
* Unit : il n'y a qu'un test, celui qui prouve que l'algorithme de recherche du modèle de voiture fonctionne.
* Functional : ce sont des tests end to end, il testent les principales fonctionnalités et les réponses en cas d'erreur.

Pour les tests fonctionnels utilisant la base de données, j'utilise le paquet dama/doctrine-test-bundle qui permet d'exécuter chaque test dans une transaction et de rollback automatiquement à la fin. On revient toujours à l'état initial de la base de données entre chaque test, avec quelques limitations bien sûr.

Je pense que pour ce test technique ce n'était pas nécessaire, dans le boostrap de phpunit je repars de toute façon de 0.



#### Lancement des tests *Core*

```bash
$ make test-core
```
#### Lancement des tests *Unit*

```bash
$ make test-algorithm
```
#### Lancement des tests *Functional*

```bash
$ make test-func
```

Les tests peuvent être tous lancés d'un coup avec la commande make test.

```bash
$ make test
```

## 5 - Requêtes curl de test

### [GET] /api/ads

```bash
$ curl --request GET --url http://localhost:8000/api/ads

[{"id":"6b430756-f16f-11eb-9c8b-0242c0a89004","title":"Php Developer","content":"Job add content","type":"job"},{"id":"6b430986-f16f-11eb-9c8b-0242c0a89004","title":"My House","content":"Description of My house","type":"realEstate"},{"id":"6b430d28-f16f-11eb-9c8b-0242c0a89004","title":"My car","content":"My beautiful car !","type":"automobile"}]%  
```

### [POST] /api/ads
#### Création d'une annonce emploi

```bash
$ curl --request POST \
--url http://localhost:8000/api/ads \
--header 'content-type: application/json' \
--data '{
"title": "Php developer",
"content": "We need you !!!!",
"type": "job"
}'

{"id":"29baced4-f171-11eb-adce-0242c0a89002","title":"Php developer","content":"We need you !!!!","type":"job"}%   
```

#### Création d'une annonce immobilier
```bash
$ curl --request POST \
--url http://localhost:8000/api/ads \
--header 'content-type: application/json' \
--data '{
"title": "My house",
"content": "My beautiful house",
"type": "realEstate"
}'

{"id":"b16d9ae6-f171-11eb-adce-0242c0a89002","title":"My house","content":"My beautiful house","type":"realEstate"}% 
```
#### Création d'une annonce automobile
```bash
$ curl --request POST \
  --url http://localhost:8000/api/ads \
  --header 'content-type: application/json' \
  --data '{
	"title": "My Car",
	"content": "My beautiful car",
	"type": "automobile",
	"model": "C4"
}'

{"id":"11b0f8ac-f173-11eb-adce-0242c0a89002","title":"My Car","content":"My beautiful car","type":"automobile","model":"C4","manufacturer":"Citroen"}%```
```

### [GET] /api/ads/{id}

/!\ Ne pas oublier de remplacer le motif {id} par un uuid réel, récupérer depuis une précédente requête. 

```bash
$ curl --request GET \
  --url http://localhost:8000/api/ads/{id}
  
{"id":"11b0f8ac-f173-11eb-adce-0242c0a89002","title":"My Car","content":"My beautiful car","type":"automobile","model":"C4","manufacturer":"Citroen"}%
```
### [PATCH] /api/ads/{id}

/!\ Ne pas oublier de remplacer le motif {id} par un uuid réel, récupérer depuis une précédente requête.

#### Annonce immobilière 
```bash
$ curl --request PATCH \
--url http://localhost:8000/api/ads/{id} \
--header 'content-type: application/json' \
--data '{
"title": "My new beautifull house",
"content": "My beautiful house"
}'

{"id":"b16d9ae6-f171-11eb-adce-0242c0a89002","title":"My new beautifull house","content":"My beautiful house","type":"realEstate"}%  
```

#### Annonce automobile

```bash
$ curl --request PATCH \
--url http://localhost:8000/api/ads/{id} \
--header 'content-type: application/json' \
--data '{
"title": "My new car",
"content": "My beautiful new car",
"model": "Serie 6"
}'

{"id":"62ba0584-f175-11eb-adce-0242c0a89002","title":"My new car","content":"My beautiful new car","type":"automobile","model":"Serie 6","manufacturer":"BMW"}%
```

### [DELETE] /api/ads/{id}

/!\ Ne pas oublier de remplacer le motif {id} par un uuid réel, récupérer depuis une précédente requête.


```bash
$ curl --request DELETE \
--url http://localhost:8000/api/ads/{id}
```

## 6 - Ce qui me semble intéressant, à ma convenance

Le projet a été développé suivant certains principes d'architecture logicielle. La **couche métier (core)** a été isolée de telle façon qu'elle soit entièrement découpler des **détails d'implémentations** (orm, framework, ...).

A la manière de l'architecture héxagonale je branche **des adapters** pour faire le lien entre la couche applicative (ports) et les outils :
* pour les ports primaires : controller via le JsonApiRequestCoreAdapter qui se charge de transformer une requête http en requête dto applicative et de transformer le dto de réponse en réponse en http. 
* pour les ports secondaires : j'utilise des repository  **/src/Infrastructure/*** qu'il ne faut pas confondre avec les repositories doctrine... ce sont des adapters, ils implémentent les interface **/src/Core/Infrastructure/*** du core (IDP)

Le core utilise les couches classiques de l'oignon architecture : User Interface | Infrastructure | Tests > Application Services > Domain Services > Domain Model
Le domain étant très simple je n'ai pas eu besoin de définir de "domain services", le peu de métier à été mis dans les modèles (entities).

J'ai fait une petite entorse à l'architecture en testant directement la méthode de création depuis une entité mais c'était pour répondre à l'énoncé de l'exercice.

Pour ne pas créer d'adhérence entre mon application et l'orm, en polluant mes entités avec des annotations doctrine, je ne les utilise pas. J'utilise le mapping xml : **/config/doctrine/***, l'utilisation du yaml étant dépréciée.     

Au niveau de la modélisation des entités (du domain), les besoins étant très simples, je n'ai pas fait de lien entre aggregats, les données du référentiel **CarModel** sont recopiées directement sur l'entité **Ad** à créer ou à modifier.
J'ai choisi ne pas créer d'agrégat **Manufacturer** qui alourdirait le domain...

J'ai également décider ne pas laisser le SGBD générer les identités de mes objets mais de le faire depuis l'application. Les uuid sont fait pour ça.
J'ai cependant simplifié leur initialisation en le faisant depuis le constructeur (sans l'injecter), il n'y a pas de différence au niveau de l'utilisation.
J'utilise un **value type** (value object) spécifique pour les ids.

Ces choix ne sont pas utile dans le cadre de l'exercice...

En ce qui concerne l'organisation de mon code **CORE**, j'ai décidé de définir les namespace **par composant** (Ads est composant avec sa propre arbo) et non **par layer** comme symfony nous pousse à faire.
Celà m'oblige à débrancher l'autowiring du dossier Core et de déclarer manuellement mes services.

```yml
services:
# core application uses package "by component" instead package "by layer", it's incompatible with autowiring
_defaults:
autowire: true
autoconfigure: true

    App\:
        resource: '../src/'
        exclude:
            - '../src/Controller/'
            - '../src/Core/'
            - '../src/Kernel.php'

    App\Controller\:
        resource: '../src/Controller'
        tags: [ 'controller.service_arguments' ]

    # Core Services <-ici
    App\Core\Ads\Application\FindAllAdsService:
        arguments:
            - '@App\Core\Ads\Infrastructure\AdRepositoryInterface'
    ...
```