<!-- PATCH Verb -->
<div class="p-6 flex flex-col space-y-10">
    <a href="#">
        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">PATCH</h5>
    </a>

    <!-- What is a PATCH Request -->
    <p class="font-normal text-gray-300">A <strong>PATCH</strong> Verb is used to partially update a <strong>resource</strong>.</p>

    <div>

        <!-- Examples of a PATCH Request -->
        <p class="mb-5 font-normal text-gray-300">Use cases for a PATCH request can include:</p>

        <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
            <li class="font-normal text-gray-300">Update a user's email address</li>
            <li class="font-normal text-gray-300">Update a few lines of an address</li>
            <li class="font-normal text-gray-300">Editing an order</li>
            <li class="font-normal text-gray-300">Changing the due date of a project</li>
        </ul>
    </div>

    <p class="font-normal text-gray-300">The PATCH Verb in its simplest form is used to overwrite specific fields in a resource, whereas a PUT is used to replace an existing resource or create a new resource.</p>

    <p class="mb-5 font-normal text-gray-300">The parameters for a PATCH request are sent in the body of the request, much like a POST request. You can also use the Content-Type header to specify which type of data you have sent to the server. </p>

    <!-- Example of a Raw PATCH Request -->
    <p class="mb-5 font-normal text-gray-300">In the example below, we are sending our data to <strong>https://example.com/users/1</strong> in the form of a json object which is specified by the Content-Type. We are passing the user id which is 1.</p>

    <p class="mb-5 font-normal text-gray-300">The Accept header is much like the Content-Type header. It specifies which type of data the Client is expecting to receive from the server - with an attribute called a 'MIME TYPE'.</p>
    <div>

            <!-- Examples of MIME Types -->
        <p class="mb-5 font-normal text-gray-300">Common examples of the Accept header include:</p>

        <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
            <li class="font-normal text-gray-300">text/csv - Used for downloading the returned comma separated data as a csv</li>
            <li class="font-normal text-gray-300">application/pdf - Used for downloading the return file as a pdf</li>
            <li class="font-normal text-gray-300">application/json - Used for returning json objects from the server</li>
            <li class="font-normal text-gray-300">text/plain - Used for downloading the returned file as a blank text file</li>
        </ul>
    </div>

    <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
        <pre><code>
<span class="text-cyan-400">PATCH</span> /users/1 HTTP/1.1
<span class="text-lime-200">Host</span>: <span class="text-blue-400">example.com</span>
<span class="text-lime-200">Content-Type</span>: <span class="text-blue-400">application/json</span>
<span class="text-lime-200">Content-Length</span>: <span class="text-blue-400">42</span>

{
    <span class="text-lime-200">"age"</span>: <span class="text-blue-400">24</span>,
    <span class="text-lime-200">"name"</span>: <span class="text-blue-400">"John Smith"</span>
}
    </code></pre>
    </div>


    <!-- Example PATCH Request -->
    <div>
        <p class="mb-5 font-normal text-gray-300">Using Laravel's Http facade, you can easily make PATCH requests like so:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
            <code class="language-php">
                $response = Http::<span class="text-cyan-400">patch</span>(<span class="text-emerald-600">'example.com/test'</span>, [
                    <span class="text-emerald-600">'age'</span> => <span class="text-emerald-600">24</span>,
                    <span class="text-emerald-600">'name'</span> => <span class="text-emerald-600">'John Smith'</span>
                ]);
            </code>
        </div>
    </div>

    <div>
        <p class="mb-5 font-normal text-gray-300">This will give you a json response as shown below:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
                    <pre><code class="language-json">
<span class="text-lime-200">"user"</span>: {
    <span class="text-lime-200">"id"</span>: <span class="text-blue-400">1</span>,
    <span class="text-lime-200">"age"</span>: <span class="text-blue-400">24</span>,
    <span class="text-lime-200">"name"</span>: <span class="text-blue-400">"John Smith"</span>,
    <span class="text-lime-200">"email"</span>: <span class="text-blue-400">"test@example.com"</span>
}
                    </code></pre>
        </div>
    </div>
</div>
