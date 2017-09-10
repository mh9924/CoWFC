# CoWFC
Front- and back-end website for the DWC network server emulator

# Prerequisites
- mh9924/dwc\_network\_server\_emulator
- Webserver (nginx or httpd)
- PHP ≥ 7.0 - PHP 7.1 recommended
  - php7.1-mysql
  - sqlite php-sqlite3
- MySQL
- HTML5 templates
  - Landed
  - SBAdmin

# Features
- Stats page shows who is online by game/country
- Admin panel to manage bans, whitelists, and more

# Screenshots

## Login Page
![image](https://user-images.githubusercontent.com/10158714/30234202-09416e82-94c9-11e7-94ac-8aa6e8bf550d.png)
## Main Dashboard
![image](https://user-images.githubusercontent.com/10158714/30234212-212eadf2-94c9-11e7-8b01-24c10f67ce7a.png)
## User List - contains all the info an admin would need to identify a player
![image](https://user-images.githubusercontent.com/10158714/30234228-3f4ed5b4-94c9-11e7-814c-26d892d29707.png)

## More screenshots coming soon as we get further with development.

# TODO
- Public ban history
  - Maybe show player name, ban time, unban time, reason for ban and length of ban on page
- Install script for CoWFC to automate new deployments - to some extent - we are already working on this and testing build steps
- Integreate console banning for users with Admin GUI
  - Maybe use the Consoles page to allow banning/unbanning of consoles?
  - Two types of console bans
    - Wii
      - MAC Address
      - Wii Friend Code (found on the Wii Message Board)
      - Serial Number
    - DS
      - MAC Address
      - Maybe BSSID too? - will make it trickier since a BSSID can't easily be changed afaik
- Integrate User Ranking System
  - Limit lower ranked users to what they can and can't do, same with high ranking users
    - Lowest ranked users can only manage own bans, and not others
    - Medium ranking users can manage own bans, as well as manage consoles
    - Highest ranking users can manage everything that a Lowest and Medium ranking user can. This means that a Highest ranking users is a super users and can manage all bans, consoles, users of the admin panel, etc.
- Integrate User Account Management Portal
  - Only Highest ranking users can access this portal
  - Add New Users
  - Modify Users
    - Change Username
    - Reset Password
    - Promote/Demote Rank
      - Highest ranking users can not manage other highest ranking users to prevent abuse
        - This means that if a highest ranking user wants to demote another, it will need to be done through the CLI
          - Shell access is limited
  - Delete Users
    - Highest ranking users can not delete each other
- More ideas I'm sure we haven't thought of yet :p
