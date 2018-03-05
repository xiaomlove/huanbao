<?php

use Faker\Generator as Faker;

$urls = [];
foreach(range(1, 14) as $value)
{
    $urls[] = $value . ".png";
}
$attachmentKeys = collect($urls);

$factory->define(App\Models\CommentDetail::class, function (Faker $faker) use ($attachmentKeys) {
    $content = [
        ['type' => 'text', 'data' => ['text' => $faker->sentence()]],
    ];
    if (rand(1, 10) > 7)
    {
        $content[] = ['type' => 'image', 'data' => ['attachment_key' => $attachmentKeys->random()]];
    }
    return [
        'content' => json_encode($content, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
    ];
});
