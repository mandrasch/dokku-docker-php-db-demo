# dokku-docker-php-db-demo
Simple PHP + MySQL Example of Docker Image Deployment with Dokku


## How was this created?

```bash
ddev config --project-type=php --database="mysql:8.0"
ddev start

ddev composer init 
ddev composer require vlucas/phpdotenv

ddev npm init -y
ddev npm i vite

# Added vite.config.js
```