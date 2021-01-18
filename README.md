# Projet 6 : SnowTricks
Php Symfony Study project: 

<img src="https://cdn.pgaimard.fr/projet06/img/github.jpeg" width="1200" alt="project illustration"/>

## Project code quality
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/894553c1cb854929a196dcff964e8ec8)](https://www.codacy.com/gh/pierregaimard/projet06/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=pierregaimard/projet06&amp;utm_campaign=Badge_Grade)
[![Maintainability](https://api.codeclimate.com/v1/badges/ecb62290be157bae9623/maintainability)](https://codeclimate.com/github/pierregaimard/projet06/maintainability)

## Project deployment

### Step 1: clone the project
On your local machine, create a project directory and run the command:  
`git clone https://github.com/pierregaimard/projet06.git`

### Step 2: Configure application server parameters
In the `.env` file, configure the 3 parameters for the database and mailer information:
- `DATABASE_URL` The database connection information.
- `APP_EMAIL` The email used by the application to send notifications to the members.
- `MAILER_DSN` The mail DSN configuration.

Note: Be sure that `APP_ENV` variable is set to `prod`.

### Step 3: Install php dependencies
_Note: Be sure that Composer is installed on your server. If not,
[install Composer](https://getcomposer.org/download/)_

In a terminal jump into `projet06` directory and run the command:
`composer install --no-dev --optimize-autoloader`.

### Step 4: Javascript dependencies & Build public assets
_Note: Be sure that Yarn is installed on your server. If not,
[Install Yarn](https://classic.yarnpkg.com/en/docs/install)_

To install javascript dependencies, in a terminal run the command: `yarn install --force`.

Then build application assets with the command: `yarn encore production`

### Step 5: Set the database
Then you need to create the database with its structure and load the fixtures
by running the following commands:

Create the database (if needed): `php bin/console doctrine:database:create`

Add database schema: `php bin/console doctrine:migrations:migrate`

Add database fixtures: `php bin/console doctrine:fixtures:load`

### Step 6: Upload files to your server
Finaly, upload the project files to your web directory of your server excluding
`assets`, `diagram` and `node_modules` directories.

### Step 7: browse to your website
Your website is now ready.
Configure your web server if needed.
_(See the [Documentation](https://symfony.com/doc/4.4/setup/web_server_configuration.html))_

Then browse to your website URL, 

Done !!!