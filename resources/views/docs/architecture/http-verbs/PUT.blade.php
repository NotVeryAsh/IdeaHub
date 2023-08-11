<!-- PUT Verb -->
<div class="p-6 flex flex-col space-y-10">
    <a href="#">
        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">PUT</h5>
    </a>

    <!-- What is a PUT Request -->
    <p class="font-normal text-gray-300">A <strong>PUT</strong> Verb is used to update a <strong>resource</strong> or create a new <strong>resource</strong> if one isn't found.</p>

    <div>

        <!-- Examples of a PUT Request -->
        <p class="mb-5 font-normal text-gray-300">Use cases for a PUT request can include:</p>

        <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
            <li class="font-normal text-gray-300">Update a user's email address</li>
            <li class="font-normal text-gray-300">Update a few lines of an address</li>
            <li class="font-normal text-gray-300">Editing an order</li>
            <li class="font-normal text-gray-300">Changing the due date of a project</li>
        </ul>
    </div>

    <p class="font-normal text-gray-300">When using PUT, the entire resource is put into the request, and so it will either replace an existing resource or create a new resource</p>

    <p class="mb-5 font-normal text-gray-300">The parameters for a PUT request are sent in the body of the request, much like a POST and PATCH request. You can also use the Content-Type header to specify which type of data you have sent to the server.</p>

    <!-- Example of a Raw PUT Request -->
    <p class="mb-5 font-normal text-gray-300">We are going to use the same example as the PATCH request and send the data to <strong>https://example.com/test</strong> in the form of a json object which is specified by the Content-Type.
        However, this time, we will be sending over all of the fields that belong to the user, since a PUT request requires us to include the entire resource </p>

    <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
        <pre><code>
<span class="text-cyan-400">PUT</span> /test HTTP/1.1
<span class="text-lime-200">Host</span>: <span class="text-blue-400">example.com</span>
<span class="text-lime-200">Content-Type</span>: <span class="text-blue-400">application/json</span>
<span class="text-lime-200">Content-Length</span>: <span class="text-blue-400">88</span>

{
    <span class="text-lime-200">"id"</span>: <span class="text-blue-400">1</span>,
    <span class="text-lime-200">"age"</span>: <span class="text-blue-400">47</span>,
    <span class="text-lime-200">"name"</span>: <span class="text-blue-400">"Jake Smith"</span>,
    <span class="text-lime-200">"email"</span>: <span class="text-blue-400">"jake@example.com"</span>
}
    </code></pre>
    </div>


    <!-- Example PUT Request -->
    <div>
        <p class="mb-5 font-normal text-gray-300">Using Laravel's Http facade, you can easily make PUT requests like so:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
            <code class="language-php">
                $response = Http::<span class="text-cyan-400">put</span>(<span class="text-emerald-600">'example.com/test'</span>, [
                    <span class="text-emerald-600">'id'</span> => <span class="text-emerald-600">1</span>
                    <span class="text-emerald-600">'age'</span> => <span class="text-emerald-600">47</span>,
                    <span class="text-emerald-600">'name'</span> => <span class="text-emerald-600">'Jake Smith'</span>,
                    <span class="text-emerald-600">'email'</span> => <span class="text-emerald-600">'jake@example.com'</span>
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
<span class="text-lime-200">Content-Length</span>: <span class="text-blue-400">92</span>

<span class="text-lime-200">"user"</span>: {
    <span class="text-lime-200">"id"</span>: <span class="text-blue-400">1</span>,
    <span class="text-lime-200">"age"</span>: <span class="text-blue-400">47</span>,
    <span class="text-lime-200">"name"</span>: <span class="text-blue-400">"Jake Smith"</span>,
    <span class="text-lime-200">"email"</span>: <span class="text-blue-400">"jake@example.com"</span>
}
                    </code></pre>
        </div>
    </div>
</div>
