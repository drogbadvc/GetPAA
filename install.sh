#!/bin/bash
VERSION="0.1"
# Determine if use root
if [ $EUID -ne 0 ]; then
  echo "Script must be run as root: # sudo $0" 1>&2
  exit 1
fi
# Determine OS platform
UNAME=$(uname | tr "[:upper:]" "[:lower:]")
# If Linux, try to determine specific distribution
if [ "$UNAME" == "linux" ]; then
    # If available, use LSB to identify distribution
    if [ -f /etc/lsb-release -o -d /etc/lsb-release.d ]; then
        export DISTRO=$(lsb_release -i | cut -d: -f2 | sed s/'^\t'//)
    # Otherwise, use release info file
    else
        export DISTRO=$(ls -d /etc/[A-Za-z]*[_-][rv]e[lr]* | grep -v "lsb" | cut -d'/' -f3 | cut -d'-' -f1 | cut -d'_' -f1)
    fi
    else
    echo 'Operating System used must be Linux'
    exit 1
fi
# For everything else (or if above failed), just use generic identifier
[ "$DISTRO" == "" ] && export DISTRO=$UNAME
unset UNAME
if [  -n "$(uname -a | grep Ubuntu)" ]; then
  NODE=$(node -v)
  if ! [ -x "$(command -v node)" ]; then
    echo 'Node is not installed. Put y to proceed with installation' >&2
    read -p 'Do You want to continue (y/N): ' ans;
    case $ans in
      [yY] | [yY][eE][sS])
    echo 'Downloads the package lists from the repositories and updates ...' >&2
    echo -n '[In Progress] Nodejs and dependencies installation ...'
    apt-get -qq update && apt-get --yes -qq --allow-downgrades install nodejs;
    ;;
  *)
    ;;
  esac
    fi
  echo "Node $NODE is installed"
  NPM=$(npm -v)
  if ! [ -x "$(command -v npm)" ]; then
    echo 'NPM is not installed. Put y to proceed with installation' >&2
    read -p 'Do You want to continue (y/N): ' ans;
    case $ans in
      [yY] | [yY][eE][sS])
      echo 'Downloads the package lists from the repositories and updates ...' >&2
      echo -n '[In Progress] NPM and dependencies installation ...'
    apt-get -qq update && apt-get --yes -qq --allow-downgrades install npm;
    ;;
  *)
    ;;
  esac
    fi
    echo "NPM $NPM is installed"
    if ! [ -f "/usr/bin/composer" ]; then
      COMPOSER=$(composer about)
      echo 'Composer is not installed. Put y to proceed with installation' >&2
      read -p 'Do You want to continue (y/N): ' ans;
       case $ans in
      [yY] | [yY][eE][sS])
      apt-get -qq update && apt-get --yes -qq --allow-downgrades install composer;
      ;;
  *)
    ;;
  esac
      fi
    echo "Composer is installed"
    echo 'Install all modules listed as dependencies'
    read -p 'Do You want to continue (y/N): ' ans;
       case $ans in
      [yY] | [yY][eE][sS])
      PACKAGE=package.json
      if ! [ -f $PACKAGE ]; then
        echo "$PACKAGE does not exist";
        exit 1;
        fi
       echo -n "[In Progress] Installation all modules listed as dependencies $PACKAGE"
       npm install
       echo 'Installation $PACKAGE completed'
      COMPJSON=composer.json
      if ! [ -f $COMPJSON ]; then
        echo "$COMPJSON does not exist";
        exit 1;
        fi
        echo -n "[In Progress] Installation all modules listed as dependencies $COMPJSON"
        composer install
        echo "Installation $COMPJSON completed"
        php -S localhost:8000
;;
  *)
    ;;
  esac
  else
    echo 'Install.sh is compatible with ubuntu only now'
    exit 1
fi