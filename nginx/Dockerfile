FROM nginx:mainline

COPY conf/nginx.conf /etc/nginx/nginx.conf

RUN set -ex \
    && rm -rf /etc/nginx/cond.d/default.conf
