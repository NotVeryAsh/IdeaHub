<!-- GET Verb -->
<div class="p-6 flex flex-col space-y-10">
    <a href="#">
        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">GET</h5>
    </a>

    <!-- What is a GET Request -->
    <p class="font-normal text-gray-300">A <strong>GET</strong> Verb is used to retrieve data from a <strong>resource</strong> - to 'get' the data.</p>

    <div>

        <!-- Examples of a GET Request -->
        <p class="mb-5 font-normal text-gray-300">These <strong>resources</strong> can include:</p>

        <ul class="list-disc list-inside font-normal text-gray-300 space-y-3">
            <li class="font-normal text-gray-300">Images</li>
            <li class="font-normal text-gray-300">Documents</li>
            <li class="font-normal text-gray-300">URLs</li>
            <li class="font-normal text-gray-300">API Endpoints</li>
        </ul>
    </div>

    <div>
        <!-- Query Parameters -->
        <p class="mb-5 font-normal text-gray-300">The query parameters for a GET request (The data you are trying to pass into the request) are appended to the end of the request url with a question mark, and an ampersand that separates each parameter:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
            <code class="language-php">
                <span class="text-emerald-600">https://example.com</span><span class="text-orange-300">?</span><span class="text-emerald-600">name=John</span><span class="text-orange-300">&</span><span class="text-emerald-600">age=30</span>
            </code>
        </div>
    </div>

    <!-- Example GET Request -->
    <div>
        <p class="mb-5 font-normal text-gray-300">You can get the current time in Los Angeles by using Laravel's Http facade to make a GET request to worldtimeapi:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
            <code class="language-php">
                $response = Http::<span class="text-cyan-400">get</span>(<span class="text-emerald-600">'http://worldtimeapi.org/api/timezone/America/Los_Angeles'</span>);
            </code>
        </div>
    </div>

    <!-- Example GET Response -->
    <div>
        <p class="mb-5 font-normal text-gray-300">This will give you a json response as shown below:</p>
        <div class="bg-gray-800 rounded border-2 border-gray-600 p-2 text-gray-200">
                    <pre><code class="language-json">
{
    <span class="text-lime-200">"abbreviation"</span>:<span class="text-blue-400">"PDT"</span>,
    <span class="text-lime-200">"client_ip"</span>:<span class="text-blue-400">"127.0.0.1"</span>,
    <span class="text-lime-200">"datetime"</span>:<span class="text-blue-400">"2023-08-03T03:20:12.085447-07:00"</span>,
    <span class="text-lime-200">"day_of_week"</span>:<span class="text-blue-400">4</span>,
    <span class="text-lime-200">"day_of_year"</span>:<span class="text-blue-400">215</span>,
    <span class="text-lime-200">"dst"</span>:<span class="text-blue-400">true</span>,
    <span class="text-lime-200">"dst_from"</span>:<span class="text-blue-400">"2023-03-12T10:00:00+00:00"</span>,
    <span class="text-lime-200">"dst_offset"</span>:<span class="text-blue-400">3600</span>,
    <span class="text-lime-200">"dst_until"</span>:<span class="text-blue-400">"2023-11-05T09:00:00+00:00"</span>,
    <span class="text-lime-200">"raw_offset"</span>:<span class="text-blue-400">-28800</span>,
    <span class="text-lime-200">"timezone"</span>:<span class="text-blue-400">"America/Los_Angeles"</span>,
    <span class="text-lime-200">"unixtime"</span>:<span class="text-blue-400">1691058012</span>,
    <span class="text-lime-200">"utc_datetime"</span>:<span class="text-blue-400">"2023-08-03T10:20:12.085447+00:00"</span>,
    <span class="text-lime-200">"utc_offset"</span>:<span class="text-blue-400">"-07:00"</span>,
    <span class="text-lime-200">"week_number"</span>:<span class="text-blue-400">31</span>
}
                    </code></pre>
        </div>
    </div>
</div>
