## Setup

Run this followed commands after changing .env "DATABASE_URL":

```bash
# Install dependencies (only the first time)
composer install

# Create database
php bin/console doctrine:database:create --if-not-exists

# Load migrations
php bin/console doctrine:migrations:migrate

# Load fixtures after changing .env "PEXEL_API_KEY"
php bin/console doctrine:fixtures:load

#Create admin user
php bin/console create-admin <name> <email> <password>

# Run the local server
symfony serve

```
