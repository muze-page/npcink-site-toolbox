#!/bin/bash
set -euo pipefail

ERRORS=0

for file in $(find . -name '*.php' -not -path './vendor/*' -not -path './node_modules/*'); do
    RESULT=$(php -l "$file" 2>&1)
    if ! echo "$RESULT" | grep -q "No syntax errors"; then
        echo "$RESULT"
        ERRORS=$((ERRORS + 1))
    fi
done

if [ $ERRORS -eq 0 ]; then
    echo "All PHP files pass syntax check."
    exit 0
else
    echo "$ERRORS file(s) have syntax errors."
    exit 1
fi