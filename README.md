# canap-plus-symfony

## Pour récupérer ce repertoire
- Récupérer le lien du repertoire: https://github.com/Vara33125/canap-plus-symfony.git
- En local dans le dossier bastille, taper la commande: git clone https://github.com/Vara33125/canap-plus-symfony.git 
- Charger le dossier 'canap-plus-symfony' avec VsCode
- Taper la commande: composer install
- Dupliquer le fichier .env
- Le renommer en .env.local y configurer les pilotes pour se connecter à la base de données
- Créer la base de données: symfony console doctrine:database:create
- Migrer les données: symfony console doctrine:migrations:migrate
- Démarrer le serveur: symfony server:start
- Charger l'application dans le navigateur https://localhost:8000
