# Pinned to the exact PHP version used in the workshop so that everyone runs
# "composer ci" in an identical environment, regardless of their local setup.
FROM php:8.5.7-cli

# Infection needs a coverage driver and PHPUnit needs the mbstring extension,
# neither of which is part of the base image.
#
#   - Xdebug (the coverage driver) is installed with PIE, the PHP Foundation's
#     official extension installer that supersedes PECL. PIE downloads the
#     extension's source with git and builds it, so it needs the usual build
#     tools plus libtool. The version is pinned to match the host.
#   - mbstring is a bundled extension and is still installed the classic way;
#     libonig-dev is its build dependency.
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        curl \
        git \
        libtool \
        libonig-dev; \
    docker-php-ext-install mbstring; \
    curl -fsSL https://github.com/php/pie/releases/download/1.4.5/pie.phar -o /usr/local/bin/pie; \
    chmod +x /usr/local/bin/pie; \
    pie install xdebug/xdebug:3.5.3; \
    rm -rf /var/lib/apt/lists/* /root/.pie /tmp/pear

# Restrict Xdebug to coverage collection, which is all Infection needs. Without
# this, Xdebug would default to a mode that provides no code coverage at all.
ENV XDEBUG_MODE=coverage

WORKDIR /app

# The project is mounted into /app at run time. We make sure the autoloader is
# generated (this needs no network, as all tools are committed PHARs) and then
# run the complete quality gate.
CMD ["sh", "-c", "php tools/composer install --no-interaction --no-progress && php tools/composer ci"]
