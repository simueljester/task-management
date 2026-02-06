## Installation Setup
To setup this exercise follow this:
 - Extract the file ***tasks.zip***
 - Go to root folder
 - `composer install`
 - `npm install`
 - `npm run dev`
 - `php artisan key:generate` 
 - `php artisan migrate` to run the migration. ENV in included and the database name is **task_management**
 - `php artisan db:seed` - this will generate default user email *test@example.com* with a password of ***@test123***.
 - `php artisan serve` to run the application
 
## Used Tech Stacks
 - Laravel 12
 - Laravel breeze for Authentication
 - Tailwind 4.0.0
 -  FetchAPI
 
## Sources
 - Used this for vanilla javascript for drag reorder function https://codepen.io/tahazsh/pen/KKGJggG

## Other Notes
 - If it does not work, please refer to this github https://github.com/simueljester/task-management. This can be cloned and do the same installation setup.
