## Setup instructions

Clone the repo. Create `.env.local` file with the contents:

```dotenv
FREECURRENCY_API_KEY=
FREECURRENCY_TIMEOUT=15
```
don't forget to fill in your API key

```shell
composer install && bin/console doctrine:migrations:migrate --no-interaction
```

To run a local web server, please execute

```shell
php -S localhost:8000
```

And you will have access to the admin panel of the project via http://127.0.0.1:8000/admin (HTTP insecure protocol)

of if you use Symfony CLI, you can run

```shell
symfony serve
```

And you will get access to the admin panel using https://127.0.0.1:8000/admin (HTTPS secure protocol)

Start adding currencies. Please keep in mind that freecurrencyapi does not support all currencies. These currencies are guaranteed supported: USD, PLN, CAD, and AUD.

Then, you can start synchronization process by running the following:

```shell
bin/console app:synchronize:rates
```

to use conversion, you can run

```shell
bin/console app:currency:convert 150 USD AUD
```

And you'll get converted amount of 150 USD to AUD in the console.
