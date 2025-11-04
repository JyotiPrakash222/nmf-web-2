<?php
// database/seeders/BadWordsSeeder.php

namespace Database\Seeders;

use App\Models\BadWord;
use Illuminate\Database\Seeder;

class BadWordsSeeder extends Seeder
{
    public function run()
    {
        $badWords = [
            // ENGLISH PROFANITY & OFFENSIVE TERMS
            // Basic profanity (low severity)
            ['word' => 'damn', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'hell', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'crap', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'stupid', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'idiot', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'moron', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'dumb', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'dumbo', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'fool', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'loser', 'language' => 'en', 'severity' => 'low'],

            // Moderate profanity
            ['word' => 'ass', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'bitch', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'piss', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'bastard', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'whore', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'slut', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'cunt', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'fuck', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'shit', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'pussy', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'dick', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'cock', 'language' => 'en', 'severity' => 'medium'],

            // Variations and compound words
            ['word' => 'asshole', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'bullshit', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'fucking', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'motherfucker', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'fucked', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'fucker', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'shitty', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'pissed', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'bitches', 'language' => 'en', 'severity' => 'medium'],

            // HATE SPEECH & DISCRIMINATORY TERMS
            // Racial slurs (high severity - some examples, be very careful with these)
            ['word' => 'nigger', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'nigga', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'chink', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'spic', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'wetback', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'kike', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'fag', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'faggot', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'retard', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'retarded', 'language' => 'en', 'severity' => 'high'],

            // Religious/ethnic hate terms
            ['word' => 'terrorist', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'nazi', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'hitler', 'language' => 'en', 'severity' => 'high'],

            // VIOLENT/THREATENING LANGUAGE
            ['word' => 'kill', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'die', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'murder', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'suicide', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'bomb', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'shoot', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'stab', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'rape', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'torture', 'language' => 'en', 'severity' => 'high'],

            // SPAM/PROMOTIONAL WORDS
            ['word' => 'free', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'winner', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'congratulations', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'urgent', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'limited time', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'act now', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'click here', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'buy now', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'cash', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'prize', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'guarantee', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'no cost', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'risk free', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'make money', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'earn money', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'instant', 'language' => 'en', 'severity' => 'low'],

            // SEXUAL CONTENT
            ['word' => 'sex', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'porn', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'nude', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'naked', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'orgasm', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'masturbate', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'horny', 'language' => 'en', 'severity' => 'medium'],

            // HINDI PROFANITY & OFFENSIVE TERMS
            // Basic Hindi profanity
            ['word' => 'बेवकूफ', 'language' => 'hi', 'severity' => 'low'], // bevakoof - stupid
            ['word' => 'मूर्ख', 'language' => 'hi', 'severity' => 'low'], // moorakh - fool
            ['word' => 'पागल', 'language' => 'hi', 'severity' => 'low'], // pagal - mad/crazy
            ['word' => 'गधा', 'language' => 'hi', 'severity' => 'low'], // gadha - donkey/ass
            ['word' => 'कुत्ता', 'language' => 'hi', 'severity' => 'medium'], // kutta - dog
            ['word' => 'कुतिया', 'language' => 'hi', 'severity' => 'medium'], // kutiya - bitch
            ['word' => 'हरामी', 'language' => 'hi', 'severity' => 'medium'], // harami - bastard
            ['word' => 'कमीना', 'language' => 'hi', 'severity' => 'medium'], // kameena - scoundrel

            // Severe Hindi profanity
            ['word' => 'भोसड़ी', 'language' => 'hi', 'severity' => 'high'], // bhosadi
            ['word' => 'चूतिया', 'language' => 'hi', 'severity' => 'high'], // chutiya
            ['word' => 'मादरचोद', 'language' => 'hi', 'severity' => 'high'], // madarchod
            ['word' => 'भेनचोद', 'language' => 'hi', 'severity' => 'high'], // bhenchod
            ['word' => 'रंडी', 'language' => 'hi', 'severity' => 'high'], // randi - whore
            ['word' => 'लंड', 'language' => 'hi', 'severity' => 'high'], // lund - penis
            ['word' => 'चूत', 'language' => 'hi', 'severity' => 'high'], // chut - vagina
            ['word' => 'गांड', 'language' => 'hi', 'severity' => 'high'], // gaand - ass
            ['word' => 'गांडू', 'language' => 'hi', 'severity' => 'high'], // gandu - asshole

            // Romanized Hindi profanity
            ['word' => 'bevakoof', 'language' => 'hi', 'severity' => 'low'],
            ['word' => 'pagal', 'language' => 'hi', 'severity' => 'low'],
            ['word' => 'gadha', 'language' => 'hi', 'severity' => 'low'],
            ['word' => 'kutta', 'language' => 'hi', 'severity' => 'medium'],
            ['word' => 'kutiya', 'language' => 'hi', 'severity' => 'medium'],
            ['word' => 'harami', 'language' => 'hi', 'severity' => 'medium'],
            ['word' => 'kameena', 'language' => 'hi', 'severity' => 'medium'],
            ['word' => 'chutiya', 'language' => 'hi', 'severity' => 'high'],
            ['word' => 'madarchod', 'language' => 'hi', 'severity' => 'high'],
            ['word' => 'bhenchod', 'language' => 'hi', 'severity' => 'high'],
            ['word' => 'randi', 'language' => 'hi', 'severity' => 'high'],
            ['word' => 'lund', 'language' => 'hi', 'severity' => 'high'],
            ['word' => 'gaand', 'language' => 'hi', 'severity' => 'high'],
            ['word' => 'gandu', 'language' => 'hi', 'severity' => 'high'],

            // Hindi hate speech
            ['word' => 'नफरत', 'language' => 'hi', 'severity' => 'high'], // nafrat - hate
            ['word' => 'मार', 'language' => 'hi', 'severity' => 'high'], // maar - kill/hit
            ['word' => 'मारूंगा', 'language' => 'hi', 'severity' => 'high'], // maarunga - will kill

            // COMMON VARIATIONS & LEETSPEAK
            ['word' => 'f*ck', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'f**k', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'fuk', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'fack', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'sh*t', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'shyt', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'b*tch', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'btch', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'a$$hole', 'language' => 'en', 'severity' => 'medium'],
            ['word' => '@sshole', 'language' => 'en', 'severity' => 'medium'],

            // Numbers substitution (leetspeak)
            ['word' => '5hit', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'a55', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'a55hole', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'b1tch', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'fck', 'language' => 'en', 'severity' => 'high'],

            // DRUG/SUBSTANCE REFERENCES
            ['word' => 'weed', 'language' => 'en', 'severity' => 'medium'],
            ['word' => 'cocaine', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'heroin', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'meth', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'crack', 'language' => 'en', 'severity' => 'high'],
            ['word' => 'ecstasy', 'language' => 'en', 'severity' => 'high'],

            // ADDITIONAL SPAM PATTERNS
            ['word' => 'make $$$', 'language' => 'en', 'severity' => 'low'],
            ['word' => '100% free', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'no obligation', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'cancel at any time', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'while supplies last', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'order now', 'language' => 'en', 'severity' => 'low'],
            ['word' => 'what are you waiting for', 'language' => 'en', 'severity' => 'low'],

            // ADDITIONAL LANGUAGES (Basic examples)
            // Spanish
            ['word' => 'mierda', 'language' => 'es', 'severity' => 'medium'], // shit
            ['word' => 'joder', 'language' => 'es', 'severity' => 'medium'], // fuck
            ['word' => 'puta', 'language' => 'es', 'severity' => 'high'], // whore
            ['word' => 'cabrón', 'language' => 'es', 'severity' => 'medium'], // asshole
            ['word' => 'pendejo', 'language' => 'es', 'severity' => 'medium'], // idiot

            // French
            ['word' => 'merde', 'language' => 'fr', 'severity' => 'medium'], // shit
            ['word' => 'putain', 'language' => 'fr', 'severity' => 'medium'], // damn/whore
            ['word' => 'salope', 'language' => 'fr', 'severity' => 'high'], // bitch
            ['word' => 'connard', 'language' => 'fr', 'severity' => 'medium'], // asshole

            // Arabic (romanized)
            ['word' => 'kus', 'language' => 'ar', 'severity' => 'high'],
            ['word' => 'sharmouta', 'language' => 'ar', 'severity' => 'high'],
        ];

        foreach ($badWords as $word) {
            BadWord::updateOrCreate(
                [
                    'word' => $word['word'],
                    'language' => $word['language']
                ],
                [
                    'severity' => $word['severity'],
                    'is_active' => true
                ]
            );
        }
    }
}
