# Historique des commandes Symfony utilisées:

### 2026/05/06 - Cosmin

- php bin/console make:user
  - User (name)
  - yes (doctrine)
  - email (unique display property)
  - yes (hash passwords)

- php bin/console make:entity User
  - firstname (string 255 no)
  - lastname (string 255 no)
  - role (enum App\Enum\UserRole yes no)
  - phone_number (integer no)
  - parent_phone_number (integer no)
  - parent_email (string 255 no)

- php bin/console make:migration
- php bin/console doctrine:migrations:migrate (yes)
