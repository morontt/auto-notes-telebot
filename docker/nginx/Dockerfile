FROM nginx:1.18

VOLUME ["/var/www/html"]

RUN sed -i "s/# export LS_OPTIONS='--color=auto'/export LS_OPTIONS='--color=auto -ah'/" /root/.bashrc \
    && sed -i "s/# alias ls/alias ls/" /root/.bashrc \
    && sed -i "s/# alias ll/alias ll/" /root/.bashrc \
    ;

COPY ./conf/* /etc/nginx/conf.d

EXPOSE 443
