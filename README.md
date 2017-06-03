# Installation & usage
- Copy .env.example & rename .env
- Create a database & give database information .env file
- run php artisan contest:fetch:codeforces to fetch codeforces.com website coding events
- run php artisan contest:fetch:toph to fetch toph.co website coding events
- You can add this as cronjob e.g * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1 this will fetch daily events from both codeforces.com & toph.co for more see https://laravel.com/docs/5.4/scheduling