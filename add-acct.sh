#!/bin/bash

read -rp "Please enter a username: " username
read -rp "Please enter a password, or leave blank if you only have a hash: " password
#read -rp "Please enter a rank number [1-3]: " rank
if [ ! -z $password ] ; then
	hash=$(/var/www/CoWFC/SQL/bcrypt-hash "$password")
else
	read -p "Please enter bcrypt hash: " hash
fi

echo "Inserting user $username into db....."
#echo "insert into users (Username, Password, Rank) values ('$username','$hash','$rank');" | mysql -u root -ppasswordhere cowfc
echo "insert into users (Username, Password, Rank) values ('$username','$hash','3');" | mysql -u root -ppasswordhere cowfc
