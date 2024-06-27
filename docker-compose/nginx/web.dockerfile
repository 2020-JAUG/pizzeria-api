FROM nginx:1.21

COPY docker-compose/nginx/vhost.conf /etc/nginx/conf.d/default.conf

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

RUN rm /bin/sh && ln -s /bin/bash /bin/sh
