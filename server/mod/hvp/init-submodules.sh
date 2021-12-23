#!/bin/sh

set -e

git config -f .gitmodules --get-regexp '^submodule\..*\.path$' |
    while read path_key path
    do
        url_key=$(echo $path_key | sed 's/\.path/.url/')
        url=$(git config -f .gitmodules --get "$url_key")
        commit_sha=$(git submodule status -- $path | grep -oE '([[:alnum:]]+)\s' -)
        rm -rf $path
        git submodule deinit $path
        git rm --cached $path
        git clone $url $path
        git --git-dir=$path/.git --work-tree=$path checkout $commit_sha
        rm -rf $path/.git
        git add $path
    done
