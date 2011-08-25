#!/usr/bin/env bash

while read -r -a file; do
# file[*] arrays are for first name, last name, email address, username, password.
	php symfony guard:create-user ${file[0]} ${file[1]} ${file[2]} ${file[3]} ${file[4]}
done