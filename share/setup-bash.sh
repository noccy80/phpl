#!/bin/bash

OPERATION="$1"
DESTINATION="$2"

case "$1" in
  "install")
    echo "Copying powerline.sh to $DESTINATION"
    test -e "${DESTINATION}/powerline.sh" || cp "powerline.bash" "${DESTINATION}/powerline.sh"
    # Install into ~/.bashrc if not present
    grep "powerline.sh" ~/.bashrc &>/dev/null
    if [ $? != 0 ]; then
        echo "source ${DESTINATION}/powerline.sh" >> ~/.bashrc
        echo "Added phpl to .bashrc"
    else
        echo "phpl appears to already be added to your .bashrc"
    fi
    ;;
  *)
    # Do nothing for anything else for now, uninstalling
esac
