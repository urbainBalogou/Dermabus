# DermaBus+

DermaBus+ est un prototype Laravel destiné à soutenir le projet togolais de dépistage mobile des maladies tropicales négligées cutanées. Il fournit une interface web pour le suivi des patients, des dépistages et des ressources de sensibilisation, ainsi qu’une API REST prête pour une future application mobile Flutter.

## Fonctionnalités principales

- **Tableau de bord analytique** résumant les indicateurs clés (patients, auto-inscriptions, dépistages, cas pris en charge, réinsertions) et les derniers événements terrain.
- **Gestion des patients** avec profil détaillé, historique des dépistages, coordonnées GPS et suivi de la réinsertion socio-économique.
- **Gestion des dépistages** basée sur les critères du modèle OMS “Skin-NTDs” : symptômes guidés, référencement, gravité, planification des suivis.
- **Bibliothèque de ressources pédagogiques** pour la sensibilisation communautaire (articles, résumés, liens médias) avec statut de publication.
- **Interface publique** présentant le projet, les services et un **formulaire d’auto-inscription sécurisé** pour les patients et leurs proches.
- **API REST v1** (`/api/v1/...`) exposant patients, dépistages et ressources pour une intégration mobile (endpoints `patients`, `screenings`, `resources`).

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

### Tests

Les tests automatiques utilisent PHPUnit :

```bash
php artisan test
```

Assurez-vous qu’une clé d’application (`APP_KEY`) est configurée avant d’exécuter la suite.

## API mobile (préparation)

Toutes les routes API sont préfixées par `/api/v1` et renvoient des réponses JSON :

| Ressource    | Endpoint                     | Description                                         |
|--------------|------------------------------|-----------------------------------------------------|
| Patients     | `GET /api/v1/patients`       | Liste paginée filtrable (`search`, `status`).       |
| Dépistages   | `GET /api/v1/screenings`     | Liste paginée filtrable (`patient_id`, `severity`). |
| Ressources   | `GET /api/v1/resources`      | Supports de sensibilisation (filtre `category`).    |
| Création     | `POST /api/v1/...`           | Création avec validation et retours structurés.     |
| Mise à jour  | `PUT/PATCH /api/v1/.../{id}` | Mise à jour d’un enregistrement.                    |
| Suppression  | `DELETE /api/v1/.../{id}`    | Suppression d’un enregistrement.                    |

L’authentification par token (Laravel Sanctum ou Passport) peut être ajoutée ultérieurement. Pour l’instant, les endpoints sont ouverts et préparent le terrain pour l’intégration Flutter.

## Structure des données

### Patients

- Identifiant externe UUID
- Informations personnelles et géographiques
- Canal d’enregistrement, auto-inscription, consentement horodaté, langue préférée
- Antécédents médicaux, déclarations du patient & notes psychosociales
- Indicateurs de réinsertion

### Dépistages

- Date, localisation, coordonnées GPS
- Symptômes (tableau), suspicion clinique, gravité, score de risque
- Statut de référencement & suivi
- Notes cliniques et communautaires

### Ressources pédagogiques

- Titre, catégorie, langue, résumé, contenu
- Lien média optionnel
- Statut de publication & date de diffusion

## Contribution

Les pull requests sont les bienvenues pour enrichir le module analytique, ajouter des exports ou intégrer l’authentification des agents de santé.

---
Projet porté par **Agbessimé Prisca** dans le cadre du Hackathon Santé & Innovation Sociale 2025.
