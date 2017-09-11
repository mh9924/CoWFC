#!/bin/bash
# DWC Network Installer script by kyle95wm/beanjr - re-written for CoWFC

# Variables used by the script in various sections to pre-fill long commandds
ROOT_UID="0"
IP="" # Used for user input
ip=$(curl -s icanhazip.com) # This variable shows the user's external IP
home_ip=$(echo $SSH_CLIENT | awk '{ print $1}')
mod1="proxy" # This is a proxy mod that is dependent on the other 2
mod2="proxy_http" # This is related to mod1
mod3="php7.1"
fqdn="localhost" # This variable fixes the fqdn error in Apache

# Functions

function create_apache_vh_nintendo {
# This function will create virtual hosts for Nintendo's domains in Apache
echo "Creating Nintendo virtual hosts...."
touch /etc/apache2/sites-available/gamestats2.gs.nintendowifi.net.conf
touch /etc/apache2/sites-available/gamestats.gs.nintendowifi.net.conf
touch /etc/apache2/sites-available/nas-naswii-dls1-conntest.nintendowifi.net.conf
touch /etc/apache2/sites-available/sake.gs.nintendowifi.net.conf
cat >/etc/apache2/sites-available/gamestats2.gs.nintendowifi.net.conf <<EOF
<VirtualHost *:80>
ServerAdmin webmaster@localhost
ServerName gamestats2.gs.nintendowifi.net
ServerAlias "gamestats2.gs.nintendowifi.net, gamestats2.gs.nintendowifi.net"
ProxyPreserveHost On
ProxyPass / http://127.0.0.1:9002/
ProxyPassReverse / http://127.0.0.1:9002/
</VirtualHost>
EOF

cat >/etc/apache2/sites-available/gamestats.gs.nintendowifi.net.conf <<EOF
<VirtualHost *:80>
ServerAdmin webmaster@localhost
ServerName gamestats.gs.nintendowifi.net
ServerAlias "gamestats.gs.nintendowifi.net, gamestats.gs.nintendowifi.net"
ProxyPreserveHost On
ProxyPass / http://127.0.0.1:9002/
ProxyPassReverse / http://127.0.0.1:9002/
</VirtualHost>
EOF

cat >/etc/apache2/sites-available/nas-naswii-dls1-conntest.nintendowifi.net.conf <<EOF
<VirtualHost *:80>
ServerAdmin webmaster@localhost
ServerName naswii.nintendowifi.net
ServerAlias "naswii.nintendowifi.net, naswii.nintendowifi.net"
ServerAlias "nas.nintendowifi.net"
ServerAlias "nas.nintendowifi.net, nas.nintendowifi.net"
ServerAlias "dls1.nintendowifi.net"
ServerAlias "dls1.nintendowifi.net, dls1.nintendowifi.net"
ServerAlias "conntest.nintendowifi.net"
ServerAlias "conntest.nintendowifi.net, conntest.nintendowifi.net"
ProxyPreserveHost On
ProxyPass / http://127.0.0.1:9000/
ProxyPassReverse / http://127.0.0.1:9000/
</VirtualHost>
EOF

cat >/etc/apache2/sites-available/sake.gs.nintendowifi.net.conf <<EOF
<VirtualHost *:80>
ServerAdmin webmaster@localhost
ServerName sake.gs.nintendowifi.net
ServerAlias sake.gs.nintendowifi.net *.sake.gs.nintendowifi.net
ServerAlias secure.sake.gs.nintendowifi.net
ServerAlias secure.sake.gs.nintendowifi.net *.secure.sake.gs.nintendowifi.net
ProxyPass / http://127.0.0.1:8000/
CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

echo "Done!"
echo "enabling...."
a2ensite *.nintendowifi.net.conf
service apache2 restart
}

function create_apache_vh_wiimmfi {
# This function will create virtual hosts for Wiimmfi's domains in Apache
echo "Creating Wiimmfi virtual hosts...."
touch /etc/apache2/sites-available/gamestats2.gs.wiimmfi.de.conf
touch /etc/apache2/sites-available/gamestats.gs.wiimmfi.de.conf
touch /etc/apache2/sites-available/nas-naswii-dls1-conntest.wiimmfi.de.conf
touch /etc/apache2/sites-available/sake.gs.wiimmfi.de.conf
cat >/etc/apache2/sites-available/gamestats2.gs.wiimmfi.de.conf <<EOF
<VirtualHost *:80>
ServerAdmin webmaster@localhost
ServerName gamestats2.gs.wiimmfi.de
ServerAlias "gamestats2.gs.wiimmfi.de, gamestats2.gs.wiimmfi.de"
ProxyPreserveHost On
ProxyPass / http://127.0.0.1:9002/
ProxyPassReverse / http://127.0.0.1:9002/
</VirtualHost>
EOF

cat >/etc/apache2/sites-available/gamestats.gs.wiimmfi.de.conf <<EOF
<VirtualHost *:80>
ServerAdmin webmaster@localhost
ServerName gamestats.gs.wiimmfi.de
ServerAlias "gamestats.gs.wiimmfi.de, gamestats.gs.wiimmfi.de"
ProxyPreserveHost On
ProxyPass / http://127.0.0.1:9002/
ProxyPassReverse / http://127.0.0.1:9002/
</VirtualHost>
EOF

cat >/etc/apache2/sites-available/nas-naswii-dls1-conntest.wiimmfi.de.conf <<EOF
<VirtualHost *:80>
ServerAdmin webmaster@localhost
ServerName naswii.wiimmfi.de
ServerAlias "naswii.wiimmfi.de, naswii.wiimmfi.de"
ServerAlias "nas.wiimmfi.de"
ServerAlias "nas.wiimmfi.de, nas.wiimmfi.de"
ServerAlias "dls1.wiimmfi.de"
ServerAlias "dls1.wiimmfi.de, dls1.wiimmfi.de"
ServerAlias "conntest.wiimmfi.de"
ServerAlias "conntest.wiimmfi.de, conntest.wiimmfi.de"
ProxyPreserveHost On
ProxyPass / http://127.0.0.1:9000/
ProxyPassReverse / http://127.0.0.1:9000/
</VirtualHost>
EOF

cat >/etc/apache2/sites-available/sake.gs.wiimmfi.de.conf <<EOF
<VirtualHost *:80>
ServerAdmin webmaster@localhost
ServerName sake.gs.wiimmfi.de
ServerAlias sake.gs.wiimmfi.de *.sake.gs.wiimmfi.de
ServerAlias secure.sake.gs.wiimmfi.de
ServerAlias secure.sake.gs.wiimmfi.de *.secure.sake.gs.wiimmfi.de
ProxyPass / http://127.0.0.1:8000/
CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

echo "Done!"
echo "enabling...."
a2ensite *.wiimmfi.de.conf
service apache2 restart
}

function dns_config {
# This function will configure dnsmasq
echo "----------Lets configure DNSMASQ now----------"
sleep 3s
echo "Adding Google DNS (8.8.8.8) to config"
# We add Google's DNS server to our server so that anyone with our DNS server can still resolve hostnames to IP
# addresses outside our DNS server. Useful for Dolphin testing
cat >>/etc/dnsmasq.conf <<EOF
server=8.8.8.8
EOF
sleep 2s
echo "What is your EXTERNAL IP?"
echo "NOTE: If you plan on using this on a LAN, put the IP of your Linux system instead"
echo "It's also best practice to make this address static in your /etc/network/interfaces file"
echo "your LAN IP is"
hostname  -I | cut -f1 -d' '
echo "Your external IP is:"
curl -s icanhazip.com
echo "Please type in either your LAN or external IP"
read -e IP
cat >>/etc/dnsmasq.conf <<EOF # Adds your IP you provide to the end of the DNSMASQ config file
address=/nintendowifi.net/$IP
address=/wiimmfi.de/$IP
EOF
clear
echo "DNSMasq setup completed!"
clear
service dnsmasq restart
clear
}

function install_required_packages {
# Add PHP 7.1 repo
echo "Adding the PHP 7.1 repository. Please follow any prompts."
add-apt-repository ppa:ondrej/php
sleep 2s
echo "Updating & installing PHP 7.1 onto your system..."
apt-get update
apt-get install php7.1 -y
# Install the other required packages
apt-get install apache2 python2.7 python-twisted dnsmasq git -y
}
function config_mysql {
echo "We will now configure MYSQL server."
read -p "Please enter the MYSQL password you would like to use for user 'root': " MYSQLPASSWD
echo "Great! I will now install and configure MYSQL Server with the password you gave me."
debconf-set-selections <<< 'mysql-server mysql-server/root_password password $MYSQLPASSWD'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password $MYSQLPASSWD'
apt-get -y install mysql-server
# We will now set the new mysql password in the AdminPage.php file.
# Do not change "passwordhere", as this will be the base for replacing it later
# The below sed command has NOT been tested so we don't know if this will work or not.
sed -i -e 's/passwordhere/$MYSQLPASSWD/g' /var/www/html/_site/AdminPage.php
# Next we will install two more packages to make mysql and sqlite work with PHP
apt-get install php7.1-mysql -y
apt-get install sqlite php-sqlite3 -y
# Now we will set up our first admin user
echo "Now we're going to set up our first Admin Portal user."
read -p "Please enter the username you wish to use: " firstuser
read -p "Please enter the password you wish to use for $firstuser: " firstpasswd
echo "Now hasing the password for $firstuser....."
echo "Please copy the hash below to your clipboard and paste it into the upcoming prompt"
/var/www/CoWFC/SQL/bcrypt-hash "$firstpasswd"
read -p "Please paste the bcrypt hash (above) for the password you set for $firstuser: " firstpasswdhashed
echo "We will now set the rank for $firstuser"
echo "At the moment, this does nothing. However in later releases, we plan to restrict who can do what."
echo "1: First Rank"
echo "2: Second Rank"
echo "3: Third Rank"
read -p "Please enter a rank number [1-3]: " firstuserrank
echo "That's all the informatio I'll need for now."
echo "Setting up the cowfc users database"
echo "create database cowfc" | mysql -u root -p$MYSQLPASSWD
echo "Now importing dumped cowfc database..."
mysql -u root -p$MYSQLPASSWD cowfc < /var/www/CoWFC/SQL/cowfc.sql
echo "Now inserting user $firstuser into the database with password $firstpasswd, hashed as $firstpasswdhashed."
echo "insert into users values ($firstuser,$firstpasswdhashed,$firstuserrank)"
}

function check_curl {
### Check if system has curl installed
dpkg -L curl >/dev/null
if [ $? != 0 ] ; then
        apt-get update && apt-get install curl -y
fi
}

# MAIN
# First we will check if we are on Ubuntu
if [  -n "$(uname -a | grep Ubuntu)" ]; then
    CANRUN="TRUE"
else
    CANRUN="FALSE"
fi

# Determine if our script can run
if [ $CANRUN == "TRUE" ] ; then
# Our script can run since we are on Ubuntu
# Put commands or functions on these lines to continue with script execution.
# The first thing we will do is to update our package repos
apt-get update
else
    echo "Sorry, you do not appear to be running a supported Opperating System."
    echo "Please make sure you are running the latest version of Ubuntu and try again!"
    exit 1
fi
