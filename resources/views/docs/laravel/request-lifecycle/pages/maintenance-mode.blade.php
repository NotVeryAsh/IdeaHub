<!-- Maintenance Mode -->
<div class="p-6 flex flex-col space-y-10">
    <a href="#">
        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Maintenance Mode</h5>
    </a>

    <p class="font-normal text-gray-300">Sometimes, your application may need to be put into maintenance mode
        - say for example, you were deploying a major update or running a database migration, and you didn't want
        anyone to be using the app while the process takes place.</p>

    <p class="font-normal text-gray-300">Laravel has a simple artisan command to put the application into maintenance mode:</p>

    <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
        <code class="language-php">
            php artisan down
        </code>
    </div>

    <p class="font-normal text-gray-300">By running this command, the following will take place:</p>
    <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
        <li class="font-normal text-gray-300">The <strong>App\Http\Middleware\PreventRequestsDuringMaintenance</strong>
            middleware will be triggered</li>
        <li class="font-normal text-gray-300">The application will return a custom maintenance mode view</li>
        <li class="font-normal text-gray-300">The app will throw a
            <strong>Symfony\Component\HttpKernel\Exception\HttpException</strong> exception with a <strong>503</strong> status code (Server is not ready to handle the request)</li>
    </ul>
</div>
