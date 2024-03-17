# dokku-docker-php-db-demo

Simple PHP + MySQL + Vite example of Docker Image Deployment with Dokku, it's my first try. Tried to keep it simple and lightweight.

Fork of https://github.com/bkuhl/fpm-nginx.

The Docker Image is built via GitHub Action and pushed to GitHubs package registry. The Dokku instance pulls the image from there (after being informed by a GitHub Action trigger).


| Container     | Size          |
| ------------- | ------------- |
| webserver     |     |
| database      |   |

⚠️ Beware: This is a demo project, please use with caution and double-check security. ⚠️ 

This is not intended for local development, for local development i use DDEV.

## Build image locally

```bash
# run
docker-compose build

# build and run
docker-compose up --build

# with more output
BUILDKIT_PROGRESS=plain docker-compose up --build
```

You can ssh/jump into containers with Orbstack (Volumes > ... > Webserver > Open terminal) or Docker Desktop.

The generated image will be in a special folder like `/Users/max/OrbStack/docker/images/`, depends on your Docker runtime. 

## TODOs

- [ ] Test dokku deployment
- [ ] Test persistent upload directory
- [ ] Do we need the work dir application/? How can we streamline the copying of all files?
- [ ] Is it correct that I created a volumen for mysql data?
- [ ] ⚠️ How to secure MySQL password? Set in dokku via .env?
- [ ] Run nginx as non-root? (https://www.reddit.com/r/nginx/comments/16ih337/running_nginx_as_nonroot_unprivileged_inside/)
- [ ] What is the best way to combine DDEV for local development and docker compose for deployment?
- [ ] Switch to serversideup/php:8.2-fpm-nginx ? 
- [ ] Try with Craft CMS and add worker

## How was this created?

```bash
ddev config --project-type=php --database="mysql:8.0"
ddev start

ddev composer init 
ddev composer require vlucas/phpdotenv

ddev npm init -y
ddev npm i vite
# Added vite.config.js
# Added scripts in package.json

# Added docker-compose.yml and Dockerfiles
```

## Why?

Why did I try this approach with a Docker Image + building it in GitHub actions?

> The advantage of using Dockerfile is that you can add exactly the steps you need, and is also portable: if I want to move from Dokku to some other provider, whether PAAS or, say, a Kubernetes pod, you can re-use your Dockerfile. As each step or layer is cached it also makes for a fast build. 

Source: https://danjacob.net/posts/single-docker-file/

> There's a couple ways to deploy your app to dokku. The most common workflow being the heroku style git push. This approach is convenient, but means app builds take place on your server. This is perhaps not a problem for most people, but was quite a big problem for me as I'm deploying apps with complicated builds (using webpack) that take long time to complete and require a lot of machine resources (like a LOT of RAM and a decent amount of processing power). These builds ultimately kill my runtime server and my running apps are unresponsive while a build is taking place. 

Source: https://richardwillis.info/blog/deploy-dokku-app-remote-docker-image

## Acknowledgements

Thanks to

- https://phpdocker.io/
- https://github.com/nystudio107/spin-up-craft
-[How I deploy serverless containers for free - Beyond Fireship](https://www.youtube.com/watch?v=cw34KMPSt4k)
- https://github.com/bkuhl/fpm-nginx
