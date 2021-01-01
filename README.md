# API

## Git Clone

~~~
git clone https://github.com/elkulo/API.git API
cd API/slim
composer install
~~~

env を .env にリネーム

## Git First Install

~~~
git init
git add README.md 
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/elkulo/API.git
git push -u origin main
git remote set-head origin main
git add --all
git commit -m "Slim"
~~~