#!/bin/bash

OPERATION="$1"
DESTINATION="$2"

_FISH_FUNCS="${HOME}/.config/fish/functions"
_FISH_PROMPT="${_FISH_FUNCS}/fish_prompt.fish"
_TEMPLATE="./powerline.fish"

case "$1" in
  "install")
    if [ -f "${_FISH_PROMPT}" ]; then
      echo "Backing up old fish_prompt.fish to fish_prompt.fish.old"
      mv "${_FISH_PROMPT}" "${_FISH_PROMPT}.old"
    fi
    echo "Installing into ${_FISH_PROMPT}"
    cp "${_TEMPLATE}" "${_FISH_PROMPT}"
    ;;
  "uninstall")
    echo "Removing ${_FISH_PROMPT}"
    if [ -f "${_FISH_PROMPT}" ]; then
      mv "${_FISH_PROMPT}" "~/fish_prompt.old"
    fi
    ;;
esac

