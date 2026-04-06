#!/usr/bin/env bash
# Deploy pass24-site: sync child theme and mu-plugin to WordPress installation.
# Called by GitHub Actions after git pull.
set -euo pipefail

REPO_DIR="/opt/pass24-site"
WP_DIR="/var/www/html/wordpress"

cd "$REPO_DIR"

echo "=== Syncing child theme ==="
rsync -av --delete \
  "$REPO_DIR/themes/pass24-child/" \
  "$WP_DIR/wp-content/themes/pass24-child/"

echo "=== Syncing mu-plugin ==="
cp "$REPO_DIR/mu-plugins/pass24-ai-factory.php" \
   "$WP_DIR/wp-content/mu-plugins/"

echo "=== Syncing root web files (verification, etc.) ==="
for f in "$REPO_DIR"/mailru-verification*.html "$REPO_DIR"/yandex_*.html; do
  [ -f "$f" ] && cp "$f" "$WP_DIR/" && echo "  copied $(basename "$f")"
done

echo "=== Ensuring wp-config.php defines ==="
WP_CONFIG="$WP_DIR/wp-config.php"

if [ -n "$BITRIX_WEBHOOK_URL" ] && ! grep -q 'PASS24_BITRIX_WEBHOOK' "$WP_CONFIG"; then
  # Insert define before "That's all, stop editing!" marker
  sed -i "/That's all, stop editing/i define( 'PASS24_BITRIX_WEBHOOK', '$BITRIX_WEBHOOK_URL' );" "$WP_CONFIG"
  echo "  added PASS24_BITRIX_WEBHOOK"
elif grep -q 'PASS24_BITRIX_WEBHOOK' "$WP_CONFIG"; then
  echo "  PASS24_BITRIX_WEBHOOK already defined"
else
  echo "  WARNING: BITRIX_WEBHOOK_URL env var not set, skipping"
fi

echo "=== Fixing ownership ==="
chown -R www-data:www-data "$WP_DIR/wp-content/themes/pass24-child/"
chown www-data:www-data "$WP_DIR/wp-content/mu-plugins/pass24-ai-factory.php"

echo "=== Deploy complete: $(date) ==="
