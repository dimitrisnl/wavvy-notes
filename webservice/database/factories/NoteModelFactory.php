<?php

$factory->define(App\Note::class, function (Faker\Generator $faker) {
    return [
    	'name' => $faker->word,
        'body' => $faker->text($maxNbChars = 300) 
    ];
});
