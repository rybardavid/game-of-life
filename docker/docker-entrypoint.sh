#!/bin/bash
set -e

# Install/update dependencies if needed
if [ ! -d "vendor" ]; then
    composer install
fi

# First arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php "$@"
fi

# Execute command
exec "$@"
