## docker-setup
docker build -t kasicareimg .

docker rm kcContainer

docker run --name kcContainer  -p 8080:80  -v $(pwd):/var/www/html kasicareimg apache2ctl -D FOREGROUND

sudo -u $($USER) chown timothy:www-data -R $(pwd)
