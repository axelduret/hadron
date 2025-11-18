FROM dunglas/frankenphp

ARG USER=appuser

RUN \
	useradd ${USER}; \
	setcap -r /usr/local/bin/frankenphp; \
	chown -R ${USER}:${USER} /data/caddy && chown -R ${USER}:${USER} /config/caddy

USER ${USER}