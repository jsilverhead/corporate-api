FROM nginx:1.23.2-alpine

# Когда собирается образ Nginx, файл "/etc/nginx/templates/default.conf.template"
# перезаписывает файл "/etc/nginx/conf.d/default.conf", который является дефолтной конфигурацией Nginx.
COPY ./nginx/nginx-server.conf /etc/nginx/templates/default.conf.template
