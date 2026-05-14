# Historique des commandes Symfony utilisées:

### 2026/05/06 - Cosmin (deprecated, on a refait la DB)

- php bin/console make:user
  - User (name)
  - yes (doctrine)
  - email (unique display property)
  - yes (hash passwords)

- php bin/console make:entity User
  - firstname (string 255 no)
  - lastname (string 255 no)
  - role (enum App\Enum\UserRole yes no)
  - phone_number (string 20 no)
  - parent_phone_number (string 20 no)
  - parent_email (string 255 no)

- php bin/console make:migration
- php bin/console doctrine:migrations:migrate (yes)

### 2026/05/07 - Cosmin (deprecated, on a refait la DB)

- php bin/console make:entity classes
  - name (string 255 no)
  - professor_id (relation user ManyToOne no yes)

- php bin/console make:entity class_users
  - user_id (relation user ManyToOne no no)
  - class_id (relation classes ManyToOne no no)

- php bin/console make:entity grades
  - class_id (relation classes ManyToOne no no)
  - student_id (relation classes ManyToOne no no)
  - grade (string 20 no)
  - comments (text yes)
  - updated_at (datetime_immutable no)

- php bin/console make:entity documents
  - user_id (relation user ManyToOne no no)
  - type (enum App\Enum\DocumentType no yes)
  - title (string 255 no)
  - path (string 255 no)

- php bin/console make:entity absences
  - user_id (relation user ManyToOne no no);
  - proof (relation documents ManyToOne yes no)
  - start_date (datetime no)
  - end_date (datetime yes)

- php bin/console make:entity user_actions
  - user_id (relation user ManyToOne no no);
  - ip (string 20 yes)
  - action (text no)
  - created_at (datetime_immutable no)

- php bin/console make:entity notifications
  - title (string 255 no)
  - message (text no)
  - type (enum App\Enum\NotificationType yes no)

- php bin/console make:entity notification_recipients
  - notification_id (relation notifications ManyToOne no no)
  - user_id (relation user ManyToOne no no)
  - is_read (boolean no)
  - read_at (datetime_immutable yes)

- php bin/console make:entity homework
  - class_id (relation classes no no)
  - title (string 255 no)
  - description (text no)
  - due_date (datetime no)

- php bin/console make:entity schedules
  - class_id (relation classes ManyToOne no no)
  - day_of_week (enum App\Enum\Weekdays no no)
  - start_time (time no)
  - end_time (time no)
  - room (string 255 no)

- php bin/console make:entity textbooks
  - class_id (relation classes ManyToOne no no)
  - title (string 255 no)
  - content (text no)

- php bin/console make:entity class_documents
  - class_id (relation classes ManyToOne no no)
  - document_id (relation documents ManyToOne no no)
  - title (string 255 no)
  - visibility (boolean no)

- php bin/console make:migration
- php bin/console doctrine:migrations:migrate

### 2026/05/11 - Cosmin (deprecated, on a refait la DB)

J'ai supprimé 'le colonne "roles" du User et adapté la colonne "role" pout qu'elle correspond aux critéres de sécurité Symfony.

### 2026/05/11 - Khaly

- php bin/console make:auth
  - Login form authenticator
  - SecurityController (name)
  - app_home (redirect after login)
  - yes (logout support)

- php bin/console make:registration-form
  - yes (UniqueEntity)
  - app_home (redirect after registration)
  - no (PHPUnit tests)

- php bin/console make:controller HomeController

### 2026/05/12 - Cosmin

Je nettoie le projet pour refaire toutes la DB plus adapté au enseignement supérieur.
On a refait une schéma DB.

### 2026/05/13 - Cosmin

Ajouté ROLE_STUDENT, ROLE_TEACHER et ROLE_ADMIN dans security.yaml.
Pour les utiliser:

- Controller:

#[IsGranted('ROLE_TEACHER')]
public function editExam(): Response
{
// Code
}

- Routes:
  access_control: - { path: ^/admin, roles: ROLE_ADMIN } - { path: ^/teacher, roles: ROLE_TEACHER } - { path: ^/student, roles: ROLE_STUDENT } // dans le security.yaml

- Twig:

{% if is_granted('ROLE_ADMIN') %}
<a href="/admin/settings">System Settings</a>
{% endif %}

Pour assigner un role dans le controller:

'''$user = new User();
$user->setEmail('prof@university.edu');
$user->setRoles(['ROLE_TEACHER']); // This is an array'''

J'ai aussi ajouté les enums (voir src/Enum).

### 2026/05/13 - Amad

- Ajout des champs manquants dans `RegistrationFormType.php` :
  - `phone_number` (TextType, optionnel, avec contrainte Regex `/^\+?[0-9]{7,15}$/` pour n'autoriser que les chiffres)
  - `github` (TextType, optionnel)
  - `google_drive` (TextType, optionnel)

- Ajout des champs correspondants dans `templates/registration/register.html.twig`

- Correction des `mappedBy` incorrects dans les entités (les noms référençaient les anciennes propriétés snake_case type `user_id`, `professor_id` au lieu des noms camelCase actuels) :
  - `User.php` : 7 corrections (`professor_id` → `professor`, `user_id` → `user`, `student_id` → `student`)
  - `Promotions.php` : 2 corrections (`promotion_id` → `promotion`, `prmotion_id` → `promotion`)
  - `Documents.php` : `document_id` → `document`
  - `Notifications.php` : `notification_id` → `notification`
  - `Projects.php` : `project_id` → `project`

- Correction de l'erreur `column t0.id does not exist` :
  `user` est un mot réservé PostgreSQL. Ajout de `#[ORM\Table(name: '"user"')]` dans `User.php` pour forcer le quoting du nom de table dans les requêtes SQL.
### 2026/05/13 - Josselin

 Journalisation des accès et actions sensibles (RGPD)

Créé manuellement (pas de commande make: disponible) :

- src/Service/ActionLogger.php
  - Service qui enregistre une action en base (user + action + horodatage)
  - Utilisé par AuthenticationListener et les contrôleurs

- src/EventListener/AuthenticationListener.php
  - Écoute LoginSuccessEvent et LogoutEvent de Symfony
  - Log automatiquement LOGIN et LOGOUT via ActionLogger

- src/Command/PurgeLogsCommand.php
  - php bin/console app:purge-logs
  - Supprime les logs UserActions de plus de 12 mois 

- src/Repository/UserActionsRepository.php
  - Ajout de la méthode deleteOlderThan(\DateTimeImmutable $before): int

### 2026/05/13 - Amad (suite)

- CRUD généré via `make:crud` pour les entités suivantes :
  - `Promotions` → PromotionsController + PromotionsType + templates/promotions/
  - `PromotionUsers` → PromotionUsersController + PromotionUsersType + templates/promotion_users/
  - `Absences` → AbsencesController + AbsencesType + templates/absences/
  - `Grades` → GradesController + GradesType + templates/grades/
  - `Projects` → ProjectsController + ProjectsType + templates/projects/

- Correction des `choice_label` générés avec `'id'` par défaut dans tous les FormType :
  - Relations User → `fn(User $u) => $u->getFirstname() . ' ' . $u->getLastname()`
  - Relations Promotions → `'name'`
  - Relations Projects → `'title'`
  - Relations Documents → `'title'`

- Ajout des types corrects pour les champs date (`DateTimeType`, `widget: single_text`), booléen (`CheckboxType`) et enum (`EnumType`) dans les FormType concernés

- Mise à jour des templates `index.html.twig` pour afficher les relations (nom du prof, de l'étudiant, de la promo) au lieu de l'id brut

### 2026/05/14 - Josselin

#### Contrôle d'accès par rôle

Ajout de `#[IsGranted]` sur les 6 controllers CRUD qui n'avaient aucune restriction de rôle.
Un utilisateur authentifié pouvait créer/modifier/supprimer des notes, promotions, projets, etc.

Règles appliquées :

- `GradesController`, `ProjectsController`, `PromotionsController`, `AbsencesController` :
  tout utilisateur connecté (`ROLE_USER`) peut consulter, mais seul un prof (`ROLE_TEACHER`) peut créer, modifier ou supprimer.

- `DocumentsController` :
  tout utilisateur connecté peut tout faire (consulter, uploader, modifier).

- `PromotionUsersController` (gestion des inscriptions en promotion) :
  seul un prof (`ROLE_TEACHER`) peut consulter, seul un admin (`ROLE_ADMIN`) peut modifier les inscriptions.

Fichiers modifiés : `GradesController.php`, `ProjectsController.php`, `PromotionsController.php`, `AbsencesController.php`, `DocumentsController.php`, `PromotionUsersController.php`

####  Validation MIME des fichiers uploadés

Ajout d'une vérification `getMimeType()` côté controller dans les actions `new` et `edit` de `DocumentsController`.
`getMimeType()` lit les magic bytes du fichier — impossible à contourner côté client, contrairement à l'extension ou au Content-Type déclaré.

Fichiers modifiés : `DocumentsController.php`


