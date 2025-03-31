FROM nginx:1.18

VOLUME ["/var/www/html"]

RUN sed -i "s/# export LS_OPTIONS='--color=auto'/export LS_OPTIONS='--color=auto -ah'/" /root/.bashrc \
    && sed -i "s/# alias ls/alias ls/" /root/.bashrc \
    && sed -i "s/# alias ll/alias ll/" /root/.bashrc \
    ;

RUN sed -i "s/}//" /etc/nginx/mime.types \
    && echo "    application/manifest+json      webmanifest;" >> /etc/nginx/mime.types \
    && echo "}" >> /etc/nginx/mime.types

COPY ./conf/* /etc/nginx/conf.d

EXPOSE 443
