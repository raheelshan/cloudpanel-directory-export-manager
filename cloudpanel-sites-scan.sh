#!/bin/bash

# ==========================================
# CloudPanel Sites Directories Scan
# ==========================================

shopt -s dotglob

OUTPUT="/home/site-user/htdocs/excludes.yourdomain.com/storage/sites.json"

echo "[" > $OUTPUT

FIRST=true

for userhome in /home/*; do

    [ -d "$userhome" ] || continue

    USERNAME=$(basename "$userhome")

    IGNORE_USERS=("clp" "mysql" "opc" "ubuntu")

    if [[ " ${IGNORE_USERS[@]} " =~ " ${USERNAME} " ]]; then
        continue
    fi

    # Normal folders
    for path in "$userhome/htdocs" "$userhome/logs" "$userhome/tmp" "$userhome/backups"; do

        if [ -d "$path" ]; then

            SIZE=$(du -sh "$path" 2>/dev/null | cut -f1)

            if [ "$FIRST" = true ]; then
                FIRST=false
            else
                echo "," >> $OUTPUT
            fi

            echo "{
                \"domain\": \"$USERNAME\",
                \"path\": \"$path\",
                \"size\": \"$SIZE\"
            }" >> $OUTPUT

        fi

    done
	
    # Hidden folders like .cache .vscode-server
    for hidden in "$userhome"/.*; do

        [ -d "$hidden" ] || continue

        BASENAME=$(basename "$hidden")

        # Skip . and ..
        [ "$BASENAME" = "." ] && continue
        [ "$BASENAME" = ".." ] && continue

        SIZE=$(du -sh "$hidden" 2>/dev/null | cut -f1)

        if [ "$FIRST" = true ]; then
            FIRST=false
        else
            echo "," >> $OUTPUT
        fi

        echo "{
            \"domain\": \"$USERNAME\",
            \"path\": \"$hidden\",
            \"size\": \"$SIZE\"
        }" >> $OUTPUT

    done	
echo "]" >> $OUTPUT	