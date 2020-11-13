# ExotelDashboard_ICICI

# installation Guide 
# apache2 installation 
sudo apt-get update
sudo apt-get upgrade
sudo apt-get install apache2
sudo service apache2 start 

# installtion php 7.2 version 
sudo apt-get install software-properties-common python-software-properties
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update
sudo apt-get install php7.2 php7.2-cli php7.2-common

#check version need 7.2.*
php -v

# install another php extensions 
sudo apt install openssl php7.2-common php7.2-curl php7.2-json php7.2-mbstring php7.2-mysql php7.2-xml php7.2-zip php7.2-gd


# installing composer 
sudo apt-get update
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '795f976fe0ebd8b75f26a6dd68f78fd3453ce79f32ecb33e7fd087d39bfeb978342fb73ac986cd4f54edd0dc902601dc') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer


# check composer is installed or not using below command 
composer


# installing git 
sudo apt-get install git 

# set name  and email 
git config --global user.name "John Doe"
git config --global user.email johndoe@example.com



# installing mysql 
sudo apt-get update
sudo apt install mysql-server
sudo mysql_secure_installation

# start mysql currently access vai sudo need to accesss as root user 
sudo mysql -u root -p 
# enter password and login 

# run below queries (If u r not able to access mysql by normal uer )
SELECT user,authentication_string,plugin,host FROM mysql.user;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';

FLUSH PRIVILEGES;
quit 

# then check below command 
mysql -u root -p 
# password 

# installing node 
 sudo apt-get update
 sudo apt-get install nodejs
 sudo apt-get install npm 
# check node version installed or not 
node -v
npm -v 

# installing npm package 
npm install 

# git clone in /var/www/html 
 git clone https://github.com/kailasums/ExotelDashboard_ICICI.git

# composer install 
composer install 
# update database user name and password for migration
  php artisan migrate 

# creating admin user after migration  
  php artisan db:seed
# file available public from storage 
 php artisan storage:link
 
# update the flag which level is not allowed to login 
# If facing issue in file upload 
#  php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" 
