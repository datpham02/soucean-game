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
        # Use a link-based atomic winner to avoid races between app/reverb startup.
        tmp_key="$key_file.$$"
        generated_key="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
        printf '%s' "$generated_key" > "$tmp_key"
        ln "$tmp_key" "$key_file" 2>/dev/null || true
        rm -f "$tmp_key"
        key_value="$(cat "$key_file")"
    fi
fi

if grep -q '^APP_KEY=' .env; then
    sed -i "s#^APP_KEY=.*#APP_KEY=$key_value#" .env
else
    printf '\nAPP_KEY=%s\n' "$key_value" >> .env
fi

exec "$@"
