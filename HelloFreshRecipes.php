<?php

const FETCH_TAKE = 2656;
const SKIP_INTERVAL = 250;

$currentSkip = 0;
$result;

while ($currentSkip < FETCH_TAKE)
{
    GetRecipesFrom($currentSkip);
    
    for ($i = 0; $i < count($result->items); $i++)
    {
        $fileName = $i + $currentSkip;
        file_put_contents("{$fileName}.json", json_encode($result->items[$i]));
    
        print($fileName . "\n");
    }

    $currentSkip += SKIP_INTERVAL;
    unset($result);
}

function GetRecipesFrom($skip)
{
    global $result;
    print("current skip : {$skip}\n");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.hellofresh.com/gw/recipes/recipes/search?country=FR&locale=fr-FR&skip={$skip}&take=" . FETCH_TAKE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        'authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJibG9ja2VkIjpmYWxzZSwiY291bnRyeSI6InVzIiwiZW1haWwiOiJ0aG9tYXMuZ2lvdmFubm9uaUBob3RtYWlsLmZyIiwiZXhwIjoxNjYxMjkxMTkyLCJpYXQiOjE2NjEyODkzOTIsImlkIjoiMjQ5NDkzY2UtMDhiOC00NmEwLTg2MTUtNTVkMWNmMjZkNjk5IiwiaXNzIjoiYTM1MDI5MzYtMWE2MS00NWQ5LWE4OGQtMThjYTA1NGM0NGRjIiwianRpIjoiZjEwYWU0OWMtYWI4OC00MjgzLTlmOWUtMjdkZTA3YmNlZTBhIiwibWV0YWRhdGEiOnsicGFzc3dvcmRsZXNzIjpmYWxzZX0sInJvbGVzIjpbXSwic2NvcGUiOiIiLCJzdWIiOiIyNDk0OTNjZS0wOGI4LTQ2YTAtODYxNS01NWQxY2YyNmQ2OTkiLCJ1c2VybmFtZSI6InRob21hcy5naW92YW5ub25pQGhvdG1haWwuZnIifQ.wo-Voa7OKdhBC3sYbpybZTBuDlcZRbCr5K1XzhYFgMc',
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec ($ch));

    curl_close ($ch);
}