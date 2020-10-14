#comment

Copy-item '.env.example' '.env'

composer install
npm install
npm install --save vue
php artisan key:generate
