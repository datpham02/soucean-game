#!/usr/bin/env sh
set -eu

cd /var/www/html/backend

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -n "${APP_KEY:-}" ]; then
    key_value="$APP_KEY"
elif grep -q '^APP_KEY=base64:' .env; then
    key_value="$(grep '^APP_KEY=' .env | cut -d= -f2-)"
else
    key_file="${APP_KEY_FILE:-/var/www/shared/app.key}"
    key_dir="$(dirname "$key_file")"
    mkdir -p "$key_dir"

    if [ -f "$key_file" ] && [ -s "$key_file" ]; then
        key_value="$(cat "$key_file")"
    else
        key_value="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
        printf '%s' "$key_value" > "$key_file"
    fi
fi

if grep -q '^APP_KEY=' .env; then
    sed -i "s#^APP_KEY=.*#APP_KEY=$key_value#" .env
else
    printf '\nAPP_KEY=%s\n' "$key_value" >> .env
fi

exec "$@"
