FROM nginx:stable-alpine

ARG UID
ARG GID
ARG NETWORK_NAME

ENV UID=${UID}
ENV GID=${GID}

RUN apk update && apk add openssl
RUN openssl req -x509 -newkey rsa:4096 -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt -sha256 -days 3650 -nodes -subj "/C=XX/ST=StateName/L=CityName/O=CompanyName/OU=CompanySectionName/CN=CommonNameOrHostname"

RUN addgroup -g ${GID} --system ${NETWORK_NAME}
RUN adduser -G ${NETWORK_NAME} --system -D -s /bin/sh -u ${UID} ${NETWORK_NAME}
RUN sed -i "s/user  nginx/user ${NETWORK_NAME}/g" /etc/nginx/nginx.conf

RUN mkdir -p /var/www/html