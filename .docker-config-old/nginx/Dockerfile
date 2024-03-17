FROM nginx:alpine

COPY ./html/ /usr/share/nginx/html/

COPY npm_build

# TODO: or use https://github.com/nginxinc/docker-nginx-unprivileged/tree/main for security?
# see https://www.reddit.com/r/nginx/comments/16ih337/running_nginx_as_nonroot_unprivileged_inside/
# FROM nginxinc/nginx-unprivileged