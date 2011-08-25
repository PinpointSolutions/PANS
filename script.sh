#!/usr/bin/env bash

arr=("s.l.mabey@gmail.com 2702533 pword" "scott.mabey@live.com 2702420 pword")


echo "Number of items in original array: ${#arr[*]}"
for ix in ${!arr[*]}
do
    printf "   %s\n" "${arr[$ix]}"
#	symfony guard:create-user ${arr[$ix]}
done



# symfony guard:create-user (email address) (username) (password)