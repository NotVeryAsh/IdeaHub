<!-- Intro to Request Lifecycle -->
<div class="p-6 flex flex-col space-y-10">
    <a href="#">
        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Intro</h5>
    </a>

    <p class="font-normal text-gray-300">All Requests in Laravel start at <strong>public/index.php</strong> since this is the file that Apache and Nginx web servers point to in order to serve the application.</p>

    <div>
        <p class="mb-5 font-normal text-gray-300">In Laravel, the <strong>public/index.php</strong> file does the following actions to return a response to the client:</p>

        <ul class="list-decimal list-inside font-normal text-gray-300 space-y-3">
            <li class="font-normal text-gray-300">Check if the app is in maintenance mode</li>
            <li class="font-normal text-gray-300">Loads all of composer's dependencies with the generated autoload file</li>
            <li class="font-normal text-gray-300">Get an instance of the app</li>
            <li class="font-normal text-gray-300">Handle the incoming request</li>
            <li class="font-normal text-gray-300">Return the response to the client</li>
        </ul>
    </div>

    <p class="font-normal text-gray-300">Below, we will break down all the segments and actions that go into serving the application and handling incoming requests</p>
</div>
