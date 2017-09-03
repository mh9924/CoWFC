#!/bin/bash
# DWC Network Installer script by kyle95wm/beanjr - re-written for CoWFC

# Variables used by the script in various sections to pre-fill long commandds
ROOT_UID="0"
IP="" # Used for user input
ip=$(curl -s icanhazip.com) # This variable shows the user's external IP
home_ip=$(echo $SSH_CLIENT | awk '{ print $1}')
apache="/etc/apache2/sites-available" # This is the directory where sites are kept in case they need to be disabled in Apache
vh="$PWD/dwc_network_server_emulator/tools/apache-hosts" # This folder is in the root directory of this script and is required for it to copy the files over
vh1="gamestats2.gs.nintendowifi.net.conf" # This is the first virtual host file
vh2="gamestats.gs.nintendowifi.net.conf" # This is the second virtual host file
vh3="nas-naswii-dls1-conntest.nintendowifi.net.conf" # This is the third virtual host file
vh4="sake.gs.nintendowifi.net.conf" # This is the fourth virtual host file
vh9="gamestats2.gs.nintendowifi.net.conf"
vh10="gamestats.gs.nintendowifi.net.conf"
vh11="nas-naswii-dls1-conntest.nintendowifi.net.conf"
vh12="sake.gs.nintendowifi.net.conf"
mod1="proxy" # This is a proxy mod that is dependent on the other 2
mod2="proxy_http" # This is related to mod1
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
EOF
clear
echo "DNSMasq setup completed!"
clear
service dnsmasq restart
clear
}

functiion install_required_packages {
# A function to install the required packages. Please feel free to add 
# instructions here for package installs. I won't add anything here yet since the server is being re-written in C++
}

function check_curl {
### Check if system has curl installed
dpkg -L curl >/dev/null
if [ $? != 0 ] ; then
        apt-get update && apt-get install curl -y
fi
}

### Feel free to add more stuff.
