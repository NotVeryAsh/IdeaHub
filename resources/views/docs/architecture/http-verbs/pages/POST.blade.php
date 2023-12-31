<!-- POST Verb -->
<div class="p-6 flex flex-col space-y-10">
    <a href="#">
        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">POST</h5>
    </a>

    <!-- What is a POST Request -->
    <p class="font-normal text-gray-300">A <strong>POST</strong> Verb is used to store and create a new <strong>resource</strong>, or for authentication.</p>

    <div>

        <!-- Examples of a POST Request -->
        <p class="mb-5 font-normal text-gray-300">Use cases for a POST request can include:</p>

        <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
            <li class="font-normal text-gray-300">A user signing up to a website</li>
            <li class="font-normal text-gray-300">A user logging in to a website</li>
            <li class="font-normal text-gray-300">A form being filled out</li>
            <li class="font-normal text-gray-300">A blog post being posted</li>
            <li class="font-normal text-gray-300">An order being placed</li>
        </ul>
    </div>

    <p class="font-normal text-gray-300">The POST Verb should only be used when creating new resources. PATCH and PUT are used for updating existing records.</p>

    <p class="mb-5 font-normal text-gray-300">The parameters for a post request are sent in the body of the request rather than in the url's query parameters like a GET request.
        The Content-Type header tells the server, or where you are sending the request, which type of data you have sent - using an attribute called a 'MIME Type'.  </p>
    <div>

        <!-- Examples of MIME Types -->
        <p class="mb-5 font-normal text-gray-300">Common examples of the Content-Type header include:</p>

        <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
            <li class="font-normal text-gray-300">application/x-www-form-urlencoded - Used for regular form submissions</li>
            <li class="font-normal text-gray-300">multipart/form-data - Used for form submissions with larges amount of data being sent, such as files</li>
            <li class="font-normal text-gray-300">application/json - Used to send raw json data and objects</li>
            <li class="font-normal text-gray-300">image/png - Used for uploading png images</li>
        </ul>
    </div>

    <div>
        <!-- Example of a Raw POST Request -->
        <p class="mb-5 font-normal text-gray-300">The headers including <strong>Host</strong> and <strong>Content-Length</strong> specify which
            server or domain the request is being made to, and the size of the data you are sending, in bytes.</p>

        <p class="mb-5 font-normal text-gray-300">The top line specifies which method is being used to send the request, such as <strong>POST</strong> as well as where on the host server the request
            is being sent to, such as <strong>/users</strong>, and which protocol is being used to do so, such as <strong>HTTP/1.1</strong>:</p>

        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
            <pre><code>
<span class="text-cyan-400">POST</span> /users HTTP/1.1
<span class="text-lime-200">Host</span>: <span class="text-blue-400">example.com</span>
<span class="text-lime-200">Content-Type</span>: <span class="text-blue-400">application/x-www-form-urlencoded</span>
<span class="text-lime-200">Content-Length</span>: <span class="text-blue-400">28</span>

<span class="text-emerald-600">email=test@example.com</span><span class="text-orange-300">&</span><span class="text-emerald-600">age=33</span>
            </code></pre>
        </div>
    </div>

    <!-- Example POST Request -->
    <div>
        <p class="mb-5 font-normal text-gray-300">Using Laravel's Http facade, you can easily make POST requests like so:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
            <code class="language-php">
                $response = Http::<span class="text-cyan-400">post</span>(<span class="text-emerald-600">'example.com/users'</span>, [
                    <span class="text-emerald-600">'email'</span> => <span class="text-emerald-600">'test@example.com'</span>,
                    <span class="text-emerald-600">'age'</span> => <span class="text-emerald-600">'33'</span>
                ]);
            </code>
        </div>
    </div>

    <div>
        <p class="mb-5 font-normal text-gray-300">This will give you a json response as shown below:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
                    <pre><code class="language-json">
HTTP/1.1 200 OK
<span class="text-lime-200">Content-Type</span>: <span class="text-blue-400">application/json</span>
<span class="text-lime-200">Content-Length</span>: <span class="text-blue-400">84</span>

<span class="text-lime-200">"user"</span>: {
    <span class="text-lime-200">"id"</span>: <span class="text-blue-400">1</span>,
    <span class="text-lime-200">"age"</span>: <span class="text-blue-400">33</span>,
    <span class="text-lime-200">"name"</span>: <span class="text-blue-400">null</span>,
    <span class="text-lime-200">"email"</span>: <span class="text-blue-400">"test@example.com"</span>
}
                    </code></pre>
        </div>
    </div>
</div>
