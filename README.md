Digit'Annuaire
==

Bienvenue sur **Digit'annuaire**, Une application web fait en **Symfony** qui permet de partager des posts et des photos entre les étudiants de votre établissement.



## Installation


- [Installer Symfony](https://symfony.com/doc/current/setup.html) avec Composer ([Voir les dépendances requises](https://symfony.com/doc/current/setup.html#technical-requirements)).


- Dans un terminal, aller sur le repertoire du projet et lancer les commande suivantes :

```bash
composer install
php bin/console make:migration
php bin/console d:m:m
php bin/console d:f:l
```

## Lancement 


Lancer les commandes suivante : 

```bash
symfony server:start
```

Aller sur [http://localhost:8000](http://localhost:8000) sur n'importe quel navigateur web.
