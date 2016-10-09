hillevi-web
===========

#What you will need
- A server, I use Ubuntu 16.04
- LAMP stack installed

#Installation (on a clean fresh server)

##Setup your server 
I assume you can handle with this. There are many guides around there.

Remember to run ``apt-get update`` before doing any installations, otherwise the installation may fail to the lack of packages.

##Install MariaDB
Simply run ``apt-get -y install mariadb-server mariadb-client``. 

After this command has done it's job, we need to configure the MariaDB. Just run ``mysql_secure_installation`` and the installation will lead you through the important steps in the way to the secure usage of the MariaDB. After this you can test your installation by typing in ``mysql -u root -p`` and giving in the root password you just set. If the installation was done successfully you will be provided by mysql command line.

##Install Apache
Simply run ``apt-get -y install apache2`` and wait for it to finish. After that you may test the installation by accessing to your ip address or domain with your web browser. You should see Apache default page.

##Install PHP7
Install PHP 7 by running ``apt-get -y install php7.0 libapache2-mod-php7.0`` and after that ``systemctl restart apache2``.

##You're done!
There are also some other PHP 7 packages which may improve the effectiveness of your PHP code. You may install them (pick the ones you want to install) like this:

``apt-get -y install php7.0-mysql php7.0-curl php7.0-gd php7.0-intl php-pear php-imagick php7.0-imap php7.0-mcrypt php-memcache  php7.0-pspell php7.0-recode php7.0-sqlite3 php7.0-tidy php7.0-xmlrpc php7.0-xsl php7.0-mbstring php-gettext``

##Install Hillevi requirements
You have to install also few modules to make Hillevi run correctly.

1. Install composer ``apt install composer``
2. Run ``composer install`` in the application root directory to install all the dependencies
3. Run ``sudo bash init.sh`` to configure the application
4. Run ``./hillevi.sh`` to run the application

TODO: improve the installation guide

#Setting everything up
##Database
Build up your database with fixtures by just typing ``bash bin/create_database.sh``. This will create your database and load fixture data into your database. By default there is a single user with username admin and password admin.
##Setup server
In your application root just run ``php bin/console server:start`` to set up the symfony server (dev) and ``php bin/console socket:start`` to open the web socket server for enabling notifications.

