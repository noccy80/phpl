#!/bin/bash

INSTALL_DIR=~/.powerline

if [ -z "$(which php)" ]; then
    echo "Error: PHP not found. Please install php-cli and try again."
    exit 1
fi

if [ ! -d $INSTALL_DIR ]; then
    echo "Creating directory: $INSTALL_DIR"
    mkdir -p $INSTALL_DIR
fi

echo -n "Installing php-powerline:"
cp -R lib $INSTALL_DIR/
cp -R bin $INSTALL_DIR/
cp powerline.sh $INSTALL_DIR/
echo " Done"

echo -n "Installing modules:"
MODULES_SRC=./modules
MODULES_DST=$INSTALL_DIR/modules
if [ ! -d $MODULES_DST ]; then
    mkdir $MODULES_DST
fi
for MODULE in $MODULES_SRC/*.php; do
    # echo " ~> $(basename $MODULE .php)"
    cp $MODULE $MODULES_DST
done
echo " Done"

echo -n "Installing themes:"
THEMES_SRC=./themes
THEMES_DST=$INSTALL_DIR/themes
if [ ! -d $THEMES_DST ]; then
    mkdir $THEMES_DST
fi
for THEME in $THEMES_SRC/*.theme; do
    # echo " ~> $(basename $THEME .theme)"
    cp $THEME $THEMES_DST
done
echo " Done"

if [ ! -d ~/bin ]; then
    echo "Skipping automatic symlink creation, please add $INSTALL_DIR/bin to your path."
else
    echo "Creating symlinks:"
    for TOOL in $INSTALL_DIR/bin/*; do
        echo " ~> $(basename $TOOL)"
        test -e ~/bin/$(basename $TOOL) || ln -s $TOOL ~/bin/$(basename $TOOL)
    done
fi

echo "Checking .bashrc..."
grep "powerline.sh" ~/.bashrc &>/dev/null
if [ $? == 0 ]; then
    echo "Already installed into .bashrc"
else
    echo "source $INSTALL_DIR/powerline.sh" >> ~/.bashrc
    echo "Updated .bashrc"
fi

echo "Updating prompt..."
$INSTALL_DIR/bin/phpl-reload --all
