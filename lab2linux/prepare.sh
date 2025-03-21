sudo apache2ctl stop
sudo rm -rf /var/www/webprog2
sudo mkdir /var/www/webprog2
sudo cp -r src/* /var/www/webprog2/
sudo apache2ctl restart