#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

APP_PORT="${APP_PORT:-8080}"
PHPMYADMIN_PORT="${PHPMYADMIN_PORT:-8081}"
APP_URL="${APP_URL:-}"
DB_PORT_FORWARD="${DB_PORT_FORWARD:-3307}"
DB_DATABASE="${DB_DATABASE:-admin_feira}"
DB_USERNAME="${DB_USERNAME:-feira}"
DB_PASSWORD="${DB_PASSWORD:-feira}"

if [[ -f .env ]]; then
  set -a
  # shellcheck disable=SC1091
  source .env
  set +a
fi

APP_PORT="${APP_PORT:-8080}"
PHPMYADMIN_PORT="${PHPMYADMIN_PORT:-8081}"
DB_PORT_FORWARD="${DB_PORT_FORWARD:-3307}"
DB_DATABASE="${DB_DATABASE:-admin_feira}"
DB_USERNAME="${DB_USERNAME:-feira}"

if [[ -z "${APP_URL:-}" ]]; then
  APP_URL="http://localhost:${APP_PORT}/"
fi

SITE_URL="${APP_URL%/}"
PMA_URL="http://localhost:${PHPMYADMIN_PORT}"

echo
echo "  Feira"
echo "  ─────"
echo "  Site:        ${SITE_URL}/"
echo "  phpMyAdmin:  ${PMA_URL}/"
echo "  MySQL:       127.0.0.1:${DB_PORT_FORWARD}  (${DB_DATABASE} / ${DB_USERNAME})"
echo
