<!-- Kernels -->
<div class="p-6 flex flex-col space-y-10">
    <a href="#">
        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kernels</h5>
    </a>

    <p class="font-normal text-gray-300">When a request is handled, either the <strong>HTTP Kernel</strong>
        is used to handle the request, or the <strong>Console Kernel</strong> is used to handle the request,
        depending on if the incoming request was made by the client via HTTP or via the command line.
    </p>

    <a href="#">
        <h6 class="text-1xl font-bold tracking-tight text-gray-900 dark:text-white">HTTP Kernel</h6>
    </a>

    <p class="font-normal text-gray-300">The HTTP Kernel class is located at <strong>app/Http/Kernel.php</strong></p>

    <p class="font-normal text-gray-300">The HTTP Kernel class extends the <strong>Illuminate\Foundation\Http\Kernel</strong>
        contract (interface), which means that a list of various classes are run on every request. These classes are
        defined as 'bootstrappers' and include functionality such as loading the environment variables and configuration,
        configuring how errors are handled, and registering the various classes that are used by the application, such as facades.
        By extending this contract, the HTTP Kernel class also has a handle method, which will handle any incoming requests.
    </p>

    <p class="font-normal text-gray-300">The HTTP Kernel class allows you to define the middleware used in the application
        in quite a flexible way. You simply add the fully qualified name of the Middleware class to one of the middleware arrays in the Http Kernel class.:
    </p>
    <p class="font-normal text-gray-300">The middleware arrays consist of the following:
    </p>
    <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
        <li class="font-normal text-gray-300"><strong>$middleware</strong> - This array is used to define the global
            middleware that is run on every incoming request. By default, this array uses middleware classes to handle
            functionality such as handling CORS, preventing any incoming requests during maintenance mode and validating
            the size of post requests.
        </li>
        <li class="font-normal text-gray-300"><strong>$middlewareGroups</strong> - This array is used to define which
            middleware is run for a specific group of routes. By default, this includes the 'web' and 'api' groups. The
            'web' group by default uses middleware such as EncryptCookies and StartSession to encrypt the user's
            cookies and start a session for the user, whereas the 'api' group by default has middleware to throttle the
            amount of requests being made to the application.
        </li>
        <li class="font-normal text-gray-300"><strong>$middlewareAliases</strong> - This array is used to define smaller
            'keywords' or aliases that point to middleware classes, which can be used in the routes file using the
            'middleware' function. By default, this includes aliases such as 'auth' which points to the
            'Illuminate\Auth\Middleware\Authenticate' middleware class. This particular middleware will redirect the
            user to a login page if they are not authenticated, and can be used in the routes file by calling
            <strong>->middleware('auth')</strong> on a route.
        </li>
    </ul>

    <a href="#">
        <h6 class="text-1xl font-bold tracking-tight text-gray-900 dark:text-white">HTTP Kernel</h6>
    </a>

    <p class="font-normal text-gray-300">The Console Kernel class is located at <strong>app/Console/Kernel.php</strong></p>

    <p class="font-normal text-gray-300">The Console Kernel extends the Illuminate\Foundation\Console interface, which
        much like the  Illuminate\Foundation\Http Kernel contract, has an array of bootstrappers that are run on every
        request, and a handle method that handles the incoming request.
    </p>

    <p class="font-normal text-gray-300">The Console Kernel has two methods for registering commands and defining scheduled
        commands. The commands function will firstly register all the commands for the app, and then load any custom
        commands that you define in <strong>routes/console.php</strong>. The schedule function allows you to specify
        commands that should be run on a schedule, such as every minute, every hour, or every day at a specific time.
        For example <strong>$schedule->command('auth:clear-resets')->everyFifteenMinutes();</strong> will run the auth:clear-resets
        every fifteen minutes.
    </p>
</div>
