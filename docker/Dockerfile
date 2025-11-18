FROM dunglas/frankenphp

ARG USER=appuser

USER root

RUN install-php-extensions \
	# libsodium \
	# pcntl \
	# pcov \
	# posix \
	# uuid \
	# xdebug \
	gmp \
	soap \
	sodium

RUN \
	useradd ${USER}; \
	setcap -r /usr/local/bin/frankenphp; \
	chown -R ${USER}:${USER} /data/caddy && chown -R ${USER}:${USER} /config/caddy

USER ${USER}