#!/bin/bash

# ==========================================
# CloudPanel Database Backup Cleaner
# ==========================================

KEEP_DAYS=1
BASE_PATH="/home"

echo "Starting backup cleanup..."

# Find all database backup parent directories
find "$BASE_PATH" \
    -type d \
    -regex ".*/backups/databases/[^/]+$"
    2>/dev/null | while read -r DB_DIR
do

    # Get dated backup folders sorted newest first
    mapfile -t BACKUPS < <(
        find "$DB_DIR" \
            -maxdepth 1 \
            -mindepth 1 \
            -type d \
            -name "20*-*-*" \
            2>/dev/null | sort -r
    )

    # Skip if empty
    [ ${#BACKUPS[@]} -eq 0 ] && continue

    echo ""
    echo "Processing: $DB_DIR"

    for i in "${!BACKUPS[@]}"
    do
        BACKUP="${BACKUPS[$i]}"

        if [ "$i" -lt "$KEEP_DAYS" ]; then
            echo "Keeping: $BACKUP"
        else
            echo "Deleting: $BACKUP"
            rm -rf "$BACKUP"
        fi
    done

done

echo ""
echo "Backup cleanup completed."