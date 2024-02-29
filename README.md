api-AR24
AR24 API Symfony

#clone project into www directory

cd /var/www/

git clone https://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@github.com/montassarbouali/api-AR24.git

#access to directory

cd api-AR24

#run docker-compose

sudo docker-compose up -d

#access to test-technique_www image

sudo docker exec -it CONTAINER_ID bash

#access to project

root@CONTAINER_ID:/var/www# cd project

#change permissions to log and cache folders if has problem after test API

root@CONTAINER_ID:/var/www/p
