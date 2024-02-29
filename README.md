# test-technique
test-technique Symfony

#clone project into www directory
cd /var/www/
git clone https://github.com/montassarbouali/test-technique.git

#access to directory
cd test-technique

#run docker-compose
sudo docker-compose up

#access to test-technique_www image
sudo docker exec -it CONTAINER_ID bash

#access to project 
root@CONTAINER_ID:/var/www# cd project

#change permissions to log and cache folders
root@CONTAINER_ID:/var/www/project# chmod -R 0777 var/log/
root@CONTAINER_ID:/var/www/project# chmod -R 0777 var/cache/

#create database and run doctrine-migrate
root@CONTAINER_ID:/var/www/project# php bin/console doctrine:database:create
Created database `test_technique` for connection named default

root@CONTAINER_ID:/var/www/project# php bin/console doctrine:migrations:migrate --no-interaction

#open project on browser
http://localhost:8741/

#open phpmyadmin on browser without password and user "root"
http://localhost:8080/

