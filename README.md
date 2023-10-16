# Comida na Tela API
Menu API for food establishments

## Objective/Motivation of the project
The menu is one of the most important parts of a food establishment. It is the first thing customers see when they enter the restaurant and is an essential tool for their decision-making. However, physical menus can often be difficult to read or out of date. Additionally, they need to be printed regularly, which can be expensive and harmful to the environment.

To solve these problems, many restaurants are opting to have a digital menu. With a digital menu, it's easy to update prices and menu options in real time. Customers can also easily browse the menu on their mobile devices, making it easier to choose what they would like to order. This not only improves the customer experience, but also helps restaurants save money and reduce their environmental impact. The **Comida na Tela** app offers establishments an efficient and modern way to present their menus to their customers.

## How to run the project

### Dependencies to run the project
* docker
* docker-compose-plugin
* git

### Clone the project
#### SSH Option
```bash
git clone git@github.com:ohRobertoCarlos/comida-tela-api-laravel.git
```

### Enter the project folder
```bash
cd comida-tela-api-laravel/
```

### Enter the api folder
```bash
cd api/
```

### Create settings file
Create a .env file from the .env.example file

#### If you are on Linux just run the following command:

```bash
cp .env.example .env
```

### Install dependencies
#### Install with composer image (if the image does not exist locally it will be downloaded):
```bash
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  -u $(id -u):$(id -g) \
  composer install
```

### This project is using the Laravel Sail package in the local development environment, to up the containers run:
Make sure port 80 or any port used by containers is not being used.

```bash
./vendor/bin/sail up -d
```

### Generate application key
```bash
./vendor/bin/sail artisan key:generate
```

### Run migrations
```bash
./vendor/bin/sail artisan migrate --seed
```

### Initial Admin user created
An initial admin user was created after seeding, you can log in with this user using the credentials below:
```json
{
  "email": "admin@email.com",
  "password" : "cmt$passWord"
}
```

It is not recommended to use this user in a production environment, so create a new admin user or assign a new real email and password to the initial user.

### To run the tests:
```bash
./vendor/bin/sail test
```

### Go to http://localhost/docs/api for api documentation
The api documentation is generated in OpenAPI (Swagger) format using a package called [Scramble](https://scramble.dedoc.co/)
