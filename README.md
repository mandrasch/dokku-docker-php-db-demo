# dokku-docker-php-db-demo

Simple PHP example of Docker Image Deployment with Dokku, it's my first try. Tried to keep it simple and lightweight.

⚠️ Beware: This is a demo project, please use with caution and double-check security. ⚠️ 

Fork of https://github.com/bkuhl/fpm-nginx.

The Docker Image is built via GitHub Action and pushed to GitHubs package registry. The Dokku instance pulls the image from there (after being informed by a GitHub Action trigger).

<hr>
It is currently not possible to deploy a docker compose build with different services, but you can build your webserver as image and deploy it to Dokku (https://github.com/dokku/dokku/issues/5102). See https://dokkupose.netlify.app/ as well.
<hr>


| Container     | Size          |
| ------------- | ------------- |
| webserver     |               |

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
- [ ] Dokku - learn about release tasks 
- [ ] For more efficient zero downtime deployments, add healthchecks to your app.json. See https://dokku.com/docs/deployment/zero-downtime-deploys/ for examples

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

Login to Dokku server:

```bash
dokku apps:create dokku-docker-php-db-demo

# https://dokku.com/docs/deployment/methods/image#initializing-an-app-repository-from-a-docker-image
dokku git:from-image dokku-docker-php-db-demo ghcr.io/mandrasch/dokku-docker-php-db-demo:latest

# After successful deployments, latest won't repull?
# (If the image tag doesn't change, that won't trigger a rebuild (as documented),
# see: https://github.com/dokku/dokku/issues/6302 - # or use :<sha> for pull?
docker pull ghcr.io/mandrasch/dokku-docker-php-db-demo
dokku ps:rebuild dokku-docker-php-db-demo
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
- [How I deploy serverless containers for free - Beyond Fireship](https://www.youtube.com/watch?v=cw34KMPSt4k)
- https://github.com/bkuhl/fpm-nginx
- https://www.tonysm.com/multiprocess-containers-with-s6-overlay/
