# DermaBus+

DermaBus+ est un prototype Laravel destiné à soutenir le projet togolais de dépistage mobile des maladies tropicales négligées cutanées. Il fournit une interface web pour le suivi des patients, des dépistages et des ressources de sensibilisation, ainsi qu’une API REST prête pour une future application mobile Flutter.

## Fonctionnalités principales

- **Tableau de bord analytique** résumant les indicateurs clés (patients, auto-inscriptions, dépistages, cas pris en charge, traitements actifs, réinsertions) et les derniers événements terrain.
- **Gestion des patients** avec profil détaillé, historique des dépistages, coordonnées GPS et suivi de la réinsertion socio-économique.
- **Gestion des dépistages** basée sur les critères du modèle OMS “Skin-NTDs” : symptômes guidés, référencement, gravité, planification des suivis et suivi du plan de traitement.
- **Gestion des suivis cliniques et sociaux** : planification des rendez-vous, assignation des agents, traçabilité des visites et alertes sur les retards.
- **Journal de notes pluridisciplinaires** : consignation sécurisée des observations médicales, psychosociales et logistiques avec contrôles de visibilité par rôle.
- **Suivi des prescriptions** : ordonnances par dépistage, posologie, durée et instructions, avec export API prêt pour l’intégration mobile.
- **Gestion des accès** : création du compte administrateur initial, rôles (admin, clinicien, enregistrement, accompagnement social) et interface de gestion des membres de l’équipe.
- **Bibliothèque de ressources pédagogiques** pour la sensibilisation communautaire (articles, résumés, liens médias) avec statut de publication.
- **Interface publique** présentant le projet, les services et un **formulaire d’auto-inscription sécurisé** pour les patients et leurs proches.
- **Portail patient** sécurisé permettant de consulter rendez-vous de suivi, traitements prescrits et historique des visites à l’aide d’un code DermaBus+ et du numéro de téléphone.
- **API REST v1** (`/api/v1/...`) exposant patients, dépistages, suivis, notes de dossier et ressources pour une intégration mobile (endpoints `patients`, `screenings`, `follow-ups`, `case-notes`, `resources`).

## Prise en main

1. **Installer les dépendances PHP**

   ```bash
   composer install
   ```

2. **Configurer l’environnement**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Ajustez la section base de données (`DB_...`) selon votre moteur (MySQL, PostgreSQL, SQLite…).

3. **Appliquer les migrations**

   ```bash
   php artisan migrate
   ```

4. **Démarrer le serveur de développement**

   ```bash
   php artisan serve
   ```

   L’interface publique est disponible sur `http://localhost:8000/` et l’espace équipe (tableau de bord + CRUD) sur `http://localhost:8000/dashboard`.

5. **Créer le premier compte administrateur**

   Tant qu’aucun utilisateur n’existe, l’URL `http://localhost:8000/register-admin` propose la création du compte administrateur initial. Une fois ce compte créé, l’accès est restreint aux administrateurs déjà connectés.

6. **Connexion des membres de l’équipe**

   L’équipe DermaBus+ se connecte via `http://localhost:8000/login`. Les administrateurs peuvent ensuite créer de nouveaux comptes (clinicien·ne, agent d’enregistrement, accompagnement social) depuis le menu **Équipe**.

7. **Connexion patient**

   Les patients auto-inscrits ou enregistrés par l’équipe peuvent consulter leur dossier via `http://localhost:8000/mon-espace` en utilisant leur référence DermaBus+ et leur numéro de téléphone. L’accès peut être suspendu depuis le back-office (champ *Portail patient actif*).

### Tests

Les tests automatiques utilisent PHPUnit :

```bash
php artisan test
```

Assurez-vous qu’une clé d’application (`APP_KEY`) est configurée avant d’exécuter la suite.

## API mobile (préparation)

Toutes les routes API sont préfixées par `/api/v1` et renvoient des réponses JSON :

| Ressource      | Endpoint                       | Description                                                 |
|----------------|--------------------------------|-------------------------------------------------------------|
| Patients       | `GET /api/v1/patients`         | Liste paginée filtrable (`search`, `status`).               |
| Dépistages     | `GET /api/v1/screenings`       | Liste paginée filtrable (`patient_id`, `severity`).         |
| Suivis         | `GET /api/v1/follow-ups`       | Rendez-vous (filtre `patient_id`, `status`, pagination).    |
| Notes de suivi | `GET /api/v1/case-notes`       | Journal clinique/social (filtre `patient_id`, `category`).  |
| Ressources     | `GET /api/v1/resources`        | Supports de sensibilisation (filtre `category`).            |
| Création       | `POST /api/v1/...`             | Création avec validation et retours structurés.             |
| Mise à jour    | `PUT/PATCH /api/v1/.../{id}`   | Mise à jour d’un enregistrement.                            |
| Suppression    | `DELETE /api/v1/.../{id}`      | Suppression d’un enregistrement.                            |

L’authentification par token (Laravel Sanctum ou Passport) peut être ajoutée ultérieurement. Pour l’instant, les endpoints sont ouverts et préparent le terrain pour l’intégration Flutter.

## Structure des données

### Patients

- Référence DermaBus+ lisible + identifiant externe UUID
- Informations personnelles et géographiques
- Canal d’enregistrement, auto-inscription, consentement horodaté, langue préférée
- Antécédents médicaux, déclarations du patient & notes psychosociales
- Plan de prise en charge, référent DermaBus+, suivi de l’accès portail patient
- Indicateurs de réinsertion socio-économique

### Dépistages

- Date, localisation, coordonnées GPS
- Symptômes (tableau), suspicion clinique, gravité, score de risque
- Statut de référencement, suivi, statut du traitement (en cours / terminé / non requis)
- Plan de traitement, prescriptions associées, notes cliniques et communautaires

### Ressources pédagogiques

- Titre, catégorie, langue, résumé, contenu
- Lien média optionnel
- Statut de publication & date de diffusion

## Contribution

Les pull requests sont les bienvenues pour enrichir le module analytique, ajouter des exports ou intégrer l’authentification des agents de santé.

---
Projet porté par **Agbessimé Prisca** dans le cadre du Hackathon Santé & Innovation Sociale 2025.
