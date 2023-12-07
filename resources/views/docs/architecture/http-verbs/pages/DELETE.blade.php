<!-- DELETE Verb -->
<div class="p-6 flex flex-col space-y-10">
    <a href="#">
        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">DELETE</h5>
    </a>

    <!-- What is a DELETE Request -->
    <p class="font-normal text-gray-300">A <strong>DELETE</strong> Verb is used to delete an existing <strong>resource</strong>.</p>

    <div>

        <!-- Examples of a DELETE Request -->
        <p class="mb-5 font-normal text-gray-300">Use cases for a DELETE request can include:</p>

        <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
            <li class="font-normal text-gray-300">Delete a user's account</li>
            <li class="font-normal text-gray-300">Deleting a blog post</li>
            <li class="font-normal text-gray-300">Removing a comment from a post</li>
            <li class="font-normal text-gray-300">Deleting a friend request</li>
        </ul>
    </div>

    <p class="font-normal text-gray-300">When using DELETE, passing parameters is not required since you can pass an id for the resource into the url.</p>

    <p class="mb-5 font-normal text-gray-300">You may also get data for a resource from various sources such as a Cookie or in the Authorization header.</p>

    <!-- Example of a Raw DELETE Request -->
    <p class="mb-5 font-normal text-gray-300">In this example, we are going to pass the user's id into the url so the server is able to identify which user we want to delete</p>

    <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
        <pre><code>
<span class="text-cyan-400">DELETE</span> /users/1 HTTP/1.1
<span class="text-lime-200">Host</span>: <span class="text-blue-400">example.com</span>
    </code></pre>
    </div>


    <!-- Example DELETE Request -->
    <div>
        <p class="mb-5 font-normal text-gray-300">Using Laravel's Http facade, you can easily make DELETE requests like so:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
            <code class="language-php">
                $response = Http::<span class="text-cyan-400">delete</span>(<span class="text-emerald-600">'example.com/1'</span>);
            </code>
        </div>
    </div>

    <div>
        <p class="mb-5 font-normal text-gray-300">The response could look something like this:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
        <pre><code>
HTTP/1.1 200 OK
<span class="text-lime-200">Content-Type</span>: <span class="text-blue-400">application/json</span>
<span class="text-lime-200">Content-Length</span>: <span class="text-blue-400">88</span>

{<span class="text-lime-200">"message"</span>:<span class="text-blue-400">"OK"</span>}
    </code></pre>
        </div>
    </div>
</div>
