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
function apache_mods {
a2enmod $mod1 $mod2
service apache2 restart
a2enmod $mod3
if [ $? != "0" ] ; then
a2dismod mpm_event
a2enmod $mod3
service apache2 restart
fi
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
curl -4 -s icanhazip.com
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
if [ ! -f "/var/www/.php71-added" ] ; then
    echo "Adding the PHP 7.1 repository. Please follow any prompts."
    add-apt-repository ppa:ondrej/php
    sleep 2s
    echo "Creating file to tell the script you already added the repo"
    touch "/var/www/.php71-added"
    echo "I will now reboot your server to free up resources for the next phase"
    reboot
    exit
else
    echo "The PHP 7.1 repo is already added. If you believe this to ben an error, please type 'rm -rf /var/www/.php71-added' to remove the file which prevents the repository from being added again."
fi
# Fix dpkg problems that happened somehow
dpkg --configure -a
echo "Updating & installing PHP 7.1 onto your system..."
apt-get update
apt-get install php7.1 -y
# Install the other required packages
apt-get install apache2 python2.7 python-twisted dnsmasq git curl -y
}
function config_mysql {
echo "We will now configure MYSQL server."
debconf-set-selections <<< 'mysql-server mysql-server/root_password password passwordhere'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password passwordhere'
apt-get -y install mysql-server
# We will now set the new mysql password in the AdminPage.php file.
# Do not change "passwordhere", as this will be the base for replacing it later
# The below sed command has NOT been tested so we don't know if this will work or not.
#sed -i -e 's/passwordhere/passwordhere/g' /var/www/html/_site/AdminPage.php
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
echo "create database cowfc" | mysql -u root -ppasswordhere
echo "Now importing dumped cowfc database..."
mysql -u root -ppasswordhere cowfc < /var/www/CoWFC/SQL/cowfc.sql
echo "Now inserting user $firstuser into the database with password $firstpasswd, hashed as $firstpasswdhashed."
echo "insert into users (Username, Password, Rank) values ('$firstuser','$firstpasswdhashed','$firstuserrank');" | mysql -u root -ppasswordhere cowfc
}
function re {
echo "In order to log into your Admin interface, you will need to set up reCaptcha keys. This script will walk you through it"
echo "Please make an account over at https://www.google.com/recaptcha/"
# Next we will ask the user for their secret key and site keys
read -p "Please enter the SECRET KEY you got from setting up reCaptcha: " secretkey
read -p "Please enter the SITE KEY you got from setting up reCaptcha: " sitekey
echo "Thank you! I will now add your SECRET KEY and SITE KEY to /var/www/html/_admin/Auth/Login.php"
# Replace SECRET_KEY_HERE with the secret key from our $secretkey variable
sed -i -e "s/SECRET_KEY_HERE/$secretkey/g" /var/www/html/_admin/Auth/Login.php
# Replace SITE_KEY_HERE with the site key from our $sitekey variable
sed -i -e "s/SITE_KEY_HERE/$sitekey/g" /var/www/html/_admin/Auth/Login.php
}
function add-cron {
echo "Checking if there is a cron available for $USER"
crontab -l -u $USER |grep "@reboot sh /start-altwfc.sh >/cron-logs/cronlog 2>&1"
if [ $? != "0" ] ; then
echo "No cron job is currently installed"
echo "Working the magic. Hang tight!"
cat > /start-altwfc.sh <<EOF
#!/bin/sh
cd /
chmod 777 /var/www/dwc_network_server_emulator -R
cd var/www/dwc_network_server_emulator
python master_server.py
cd /
EOF
chmod 777 /start-altwfc.sh
mkdir -p /cron-logs
echo "Creating the cron job now!"
echo "@reboot sh /start-altwfc.sh >/cron-logs/cronlog 2>&1" >/tmp/alt-cron
crontab -u $USER /tmp/alt-cron
echo "Done!"
fi
}
function install_website {
# First we will delete evertyhing inside of /var/www/html
rm -rf /var/www/html/*
# Then we will copy the website files from our CoWFC Git
cp /var/www/CoWFC/Web/* /var/www/html -R
# Let's restart Apache now
service apache2 restart
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
    # The first thing we will do is to update our package repos but let's also make sure that the user is running the script in the proper directory /var/www
    if [ $PWD == "/var/www" ] ; then
        apt-get update
        # Let's install required packages first.
        install_required_packages
        # Then we will check to see if the Gits for CoWFC and dwc_network_server_emulator exist
        if [ ! -d "/var/www/CoWFC" ] ; then
            echo "Git for CoWFC does not exist in /var/www/"
            git clone https://github.com/mh9924/CoWFC.git
        fi
        if [ ! -d "/var/www/dwc_network_server_emulator" ] ; then
            echo "Git for dwc_network_server_emulator does not exist in /var/www"
            git clone https://github.com/mh9924/dwc_network_server_emulator.git
            echo "Setting proper file permissions"
            chmod 777 /var/www/dwc_network_server_emulator/ -R
        fi
# Configure DNSMASQ
dns_config
# Let's set up Apache now
create_apache_vh_nintendo
create_apache_vh_wiimmfi
apache_mods # Enable reverse proxy mod and PHP 7.1
install_website # Install the web contents for CoWFC
config_mysql # We will set up the mysql password as "passwordhere" and create our first user
re # Set up reCaptcha
add-cron #Makes it so master server can start automatically on boot
echo "Thank you for installing CoWFC. One thing to note is that this script does not come with the HTML5 templates, so things may look messy. You may install whatever HTML5 templates you want and modify the webpages to your heart's content."
echo "If you wish to access the admin GUI, please go to http://YOURSERVERADDRESS/?page=admin&section=Dashboard"
reboot
exit 0
# DO NOT PUT COMMANDS UNDER THIS FI
fi
else
    echo "Sorry, you do not appear to be running a supported Opperating System."
    echo "Please make sure you are running the latest version of Ubuntu and try again!"
    exit 1
fi
