#!/bin/bash

read -p "Please enter a username: " username
echo "Deleting user $username from db....."
echo "delete from users where Username='$username';" | mysql -u root -ppasswordhere cowfc
