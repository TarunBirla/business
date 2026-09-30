<?php

namespace App\Services;

use App\Models\AbusingWord;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ProfanityFilter
{
    /**
     * Comprehensive default list of 500+ abusive, profane, and inappropriate words (English & Hinglish/Hindi).
     */
    protected static array $defaultWords = [
        // --- English Profanities & Slurs ---
        'abuse', 'abusive', 'anal', 'anus', 'ar5e', 'arrse', 'arse', 'arsehole', 'ass', 'ass-fucker',
        'asses', 'assfucker', 'assfukka', 'asshole', 'assholes', 'asswhole', 'ballbag', 'ballsack',
        'bastard', 'bastards', 'bdsm', 'beastial', 'beastiality', 'bellend', 'bestiality', 'biatch',
        'bitch', 'bitches', 'bitchin', 'bitching', 'bitchy', 'blowjob', 'blowjobs', 'boob', 'boobs',
        'boobie', 'boobies', 'booobs', 'boooobs', 'booooobs', 'booooooobs', 'breasts', 'bugger',
        'bullshit', 'bullshitter', 'bum', 'butt', 'butthole', 'butt-plug', 'clit', 'clitoris',
        'cock', 'cocks', 'cockhead', 'cocksucker', 'cockhead', 'coon', 'crap', 'crappy', 'cum',
        'cumming', 'cumshot', 'cunt', 'cunts', 'd1ck', 'damn', 'dick', 'dicks', 'dickhead',
        'dickheads', 'dickpic', 'dickweed', 'dildo', 'dildos', 'dipshit', 'doggystyle', 'douche',
        'douchebag', 'douchebags', 'douchenozzle', 'ejaculate', 'ejaculated', 'ejaculating',
        'ejaculation', 'erection', 'erections', 'fag', 'fagging', 'faggot', 'faggots', 'fags',
        'fanny', 'fatass', 'felching', 'fellatio', 'flange', 'fuck', 'fucked', 'fucker', 'fuckers',
        'fuckhead', 'fuckheads', 'fuckin', 'fucking', 'fuckings', 'fucknut', 'fuckoff', 'fucks',
        'fucktard', 'fuckup', 'fuk', 'fuker', 'fukker', 'fukkin', 'fukking', 'gangbang', 'gangbanged',
        'goddamn', 'goddamned', 'gook', 'handjob', 'handjobs', 'hardcoresex', 'homo', 'horny',
        'incest', 'jackass', 'jackasses', 'jerk', 'jerkoff', 'jizz', 'knob', 'knobhead', 'labia',
        'lust', 'lusting', 'masochist', 'masturbate', 'masturbating', 'masturbation', 'mofo',
        'motherfuck', 'motherfucker', 'motherfuckers', 'motherfucking', 'motherfuckka', 'nigger',
        'niggers', 'nigga', 'niggas', 'numbnuts', 'orgasm', 'orgasms', 'penis', 'penises', 'piss',
        'pissed', 'pisser', 'pisses', 'pissing', 'pissflaps', 'playboy', 'polack', 'porn', 'porno',
        'pornography', 'prick', 'pricks', 'pussy', 'pussies', 'pussys', 'queer', 'rape', 'raped',
        'raper', 'raping', 'rapist', 'rectum', 'retard', 'retarded', 'retards', 'rimming', 'sadist',
        'scrotum', 'semen', 'sex', 'sexual', 'sexually', 'shemale', 'shit', 'shits', 'shitass',
        'shitbag', 'shitdick', 'shite', 'shitfaced', 'shithead', 'shitheads', 'shithole', 'shiting',
        'shititter', 'shitt', 'shitting', 'shitty', 'skank', 'skanks', 'slut', 'sluts', 'slutty',
        'smegma', 'sonofabitch', 'spastic', 'spunk', 'tit', 'tits', 'titties', 'titty', 'tosser',
        'twat', 'twats', 'vagina', 'vulva', 'wank', 'wanker', 'wankers', 'wanking', 'whore',
        'whores', 'whoring', 'xxx', 'zoophile',

        // --- Hinglish & Hindi Profanities ---
        'abuse', 'abusive', 'behenchod', 'behenchode', 'bhenchod', 'bhenchode', 'bhenchodd',
        'bhenchodi', 'bhonsdike', 'bhosda', 'bhosdi', 'bhosdike', 'bhosdikey', 'bhosdiki',
        'bhosdiwala', 'bhosdiwale', 'bhosdiwali', 'bhosad', 'bhadwa', 'bhadwaa', 'bhadwe',
        'bhadwi', 'bsdk', 'bsdd', 'bc', 'mc', 'mkc', 'bkc', 'tmkc', 'tmkcb', 'tmbc',
        'chud', 'chuda', 'chudai', 'chudail', 'chudam', 'chudwa', 'chudwaya', 'chutiya',
        'chutiyap', 'chutiyapa', 'chutiye', 'chutiyon', 'chut', 'chuta', 'chuti', 'gaand',
        'gand', 'gaandu', 'gandu', 'gaandfat', 'gaandmasti', 'gaandmarwa', 'gaandmara',
        'gandmarwa', 'gandmara', 'harami', 'haramkhor', 'haramzadi', 'haramzada', 'jhant',
        'jhaant', 'jhat', 'jhantoo', 'jhaantu', 'kamina', 'kamine', 'kaminey', 'kamini',
        'kutta', 'kutte', 'kutti', 'kuttinya', 'lauda', 'laude', 'laudey', 'loda', 'lode',
        'lodey', 'lodha', 'lund', 'lundh', 'lundee', 'lundia', 'madarchod', 'madarchode',
        'maderchod', 'maderchode', 'madarjat', 'madarjhant', 'raand', 'randi', 'randiya',
        'randiyons', 'randiwaala', 'randiwaale', 'randwa', 'saala', 'saale', 'saali', 'sala',
        'sale', 'sali', 'suar', 'suwar', 'suar-ki-aulad', 'terimaaki', 'terimaakichut',
        'teribhenki', 'teribhenkichut', 'tharki', 'hawasi', 'bakchod', 'bakchodi', 'bhadve',
        'chutmarani', 'chutmarika', 'chutmarike', 'gandfat', 'gandfaad', 'gandmasti', 'kamineyo',
        'kutta-kamine', 'laudewala', 'lundless', 'muth', 'muthi', 'mutthal', 'muthal', 'randibazi',
        'randipana', 'suar-aulad', 'teri-maa-ki', 'teri-bhen-ki',

        // --- Additional Obscenities & Extended Variations ---
        'anal-beads', 'analdildo', 'analsex', 'antifa', 'arse-hole', 'ass-fucker', 'ass-pirate',
        'assclown', 'assface', 'assfucker', 'asshat', 'asshead', 'asshole', 'asshopper',
        'assjacker', 'asslicker', 'assman', 'assmonkey', 'assmunch', 'asspacker', 'asspirate',
        'asswipe', 'autoerotic', 'babeland', 'bareback', 'barelylegal', 'bastardchild',
        'batshit', 'bitch-ass', 'bitchass', 'bitcher', 'bitchin', 'bitchslap', 'bloody',
        'blow-job', 'bondage', 'booty-call', 'brotherfucker', 'butt-pirate', 'camgirl',
        'camslut', 'camwhore', 'carpetmuncher', 'chesticles', 'chinc', 'chink', 'circlejerk',
        'cleavage', 'clusterfuck', 'cock-sucker', 'cockbite', 'cockburger', 'cockface',
        'cockhead', 'cockjockey', 'cockknoker', 'cockmaster', 'cockmongler', 'cockmuncher',
        'cocksmacker', 'cocksucker', 'cum-shot', 'cumdump', 'cumdumpster', 'cumguzzler',
        'cumslut', 'cunt-face', 'cuntface', 'cunthole', 'cuntlicker', 'cuntrag', 'cuntslut',
        'cybersex', 'cyberfucker', 'dago', 'dammit', 'dick-head', 'dickbag', 'dickbeaters',
        'dickdipper', 'dickhead', 'dickhole', 'dickjuice', 'dickless', 'dicklicker',
        'dickman', 'dickmilk', 'dickripper', 'dicksuck', 'dicksucker', 'dickwad', 'dickzipper',
        'dildo', 'dingleberry', 'dirsa', 'dog-fucker', 'doggie-style', 'doggiestyle',
        'doggy-style', 'donkeypunch', 'double-dong', 'douche-bag', 'douchebag', 'dp-action',
        'dumbass', 'dumbshit', 'dumass', 'dyke', 'eat-pussy', 'ejaculate', 'ekrem',
        'fag-bag', 'fag-ass', 'fagbag', 'faggit', 'faggot', 'fagit', 'faggs', 'fagot',
        'fags', 'fagts', 'fanyy', 'fatass', 'felch', 'fellatio', 'feltch', 'fingerfuck',
        'fingerfucked', 'fingerfucker', 'fingerfuckers', 'fingerfucking', 'fingerfucks',
        'fistfuck', 'fistfucked', 'fistfucker', 'fistfuckers', 'fistfucking', 'fistfuckings',
        'fistfucks', 'flange', 'flygap', 'fornicate', 'fuck-ass', 'fuck-bitch', 'fuck-head',
        'fuck-off', 'fuck-tard', 'fucka', 'fuckable', 'fuckass', 'fuckbag', 'fuckboy',
        'fuckbrain', 'fuckbutt', 'fuckbutter', 'fucker', 'fuckers', 'fuckface', 'fuckhead',
        'fuckin', 'fucking', 'fucktard', 'fuckup', 'fuckwit', 'fuk', 'fuker', 'fukker',
        'fukkin', 'fukking', 'fuks', 'fukwhit', 'fukwit', 'fux', 'fux0r', 'gangbang',
        'gangbanged', 'gangbanger', 'gaysian', 'gender-bender', 'gook', 'gspot', 'hand-job',
        'hardcore-sex', 'hardon', 'heeb', 'hell', 'herpes', 'hoar', 'hoare', 'hoe', 'homo',
        'homoerotic', 'honkey', 'hooker', 'horniest', 'horny', 'hotsex', 'hump', 'humped',
        'humping', 'jack-off', 'jackass', 'jackoff', 'jap', 'jerk-off', 'jerkoff', 'jism',
        'jiz', 'jizm', 'jizz', 'jizzed', 'kaffir', 'kike', 'kismet', 'kock', 'kondum',
        'koolaid', 'krap', 'kraut', 'kum', 'kummer', 'kumming', 'kums', 'kummer', 'kunt',
        'kyke', 'l3i+ch', 'l3itch', 'labia', 'lesbian', 'lesbo', 'lezzie', 'lust',
        'lusting', 'm-fucking', 'mams', 'masochist', 'masturbate', 'masturbating',
        'masturbation', 'meth', 'mick', 'micro-penis', 'milf', 'mindfuck', 'mo-fo',
        'mothafuck', 'mothafucka', 'mothafuckas', 'mothafuckaz', 'mothafucked', 'mothafucker',
        'mothafuckers', 'mothafuckin', 'mothafucking', 'mothafuckings', 'mothafucks',
        'mother-fuck', 'mother-fucker', 'mother-fucking', 'motherfuck', 'motherfucked',
        'motherfucker', 'motherfuckers', 'motherfuckin', 'motherfucking', 'motherfuckings',
        'motherfuckka', 'motherfucks', 'muff', 'muffdiver', 'muffpuff', 'nazi', 'negro',
        'nig-nog', 'nigga', 'niggah', 'niggas', 'niggaz', 'nigger', 'niggers', 'niggle',
        'niggler', 'nimphomania', 'nipple', 'nipples', 'nizzler', 'noblhead', 'nobjocky',
        'nofuck', 'nut-butter', 'nutbutter', 'numbnuts', 'nut sack', 'nutsack', 'omg',
        'orgasm', 'orgasms', 'p-o-r-n', 'pawn', 'pecker', 'penis', 'penises', 'penisfucker',
        'phuck', 'phuk', 'phuked', 'phuking', 'phukked', 'phukking', 'phuks', 'phq',
        'pimp', 'piss', 'piss-off', 'pissed', 'pisser', 'pisses', 'pissflaps', 'pissin',
        'pissing', 'pissoff', 'porn', 'porno', 'pornography', 'pornos', 'prick', 'pricks',
        'pron', 'puss', 'pusse', 'pussi', 'pussies', 'pussy', 'pussys', 'pussyeater',
        'pussyfucker', 'pussylicker', 'pussypounder', 'queer', 'quim', 'raghead', 'rape',
        'raped', 'raper', 'raping', 'rapist', 'rectum', 'retard', 'retarded', 'retards',
        'rimjaw', 'rimming', 'rosy-palm', 'rtard', 'r-tard', 'rubbish', 'sadist',
        'scank', 'schlong', 'scrotum', 'semen', 'sex', 'sexcam', 'sexo', 'sexy', 'shemale',
        'shipal', 'shit', 'shit-ass', 'shit-bag', 'shit-cunt', 'shit-dick', 'shit-head',
        'shit-heel', 'shit-hole', 'shit-house', 'shit-stain', 'shit-talk', 'shitass',
        'shitbag', 'shitblimp', 'shitbrains', 'shitdick', 'shite', 'shiteater', 'shitfaced',
        'shithead', 'shitheads', 'shithole', 'shithouse', 'shiting', 'shitlist', 'shitstain',
        'shitt', 'shitter', 'shitting', 'shitty', 'shiz', 'shiznit', 'skank', 'skeet',
        'slanteye', 'slut', 'sluts', 'slutty', 'smegma', 'smut', 'snatch', 'son-of-a-bitch',
        'sonofabitch', 'spastic', 'spic', 'spig', 'spik', 'splooge', 'spunk', 'stfu',
        't1t', 't1tt1e5', 't1tties', 'tard', 'testicle', 'tit', 'titfuck', 'tits',
        'titt', 'tittie5', 'titties', 'titty', 'tittyfuck', 'tittywank', 'titwank',
        'tosser', 'towelhead', 'trashy', 'tubgirl', 'turd', 'twat', 'twathead', 'twats',
        'twatty', 'twunt', 'twunter', 'unclefucker', 'unfuck', 'v14gra', 'v1gra',
        'vagina', 'viagra', 'vullva', 'vulva', 'w00se', 'wank', 'wanker', 'wankers',
        'wanking', 'wetback', 'whore', 'whoreface', 'whorehouse', 'whores', 'whoring',
        'wop', 'wtf', 'x-rated', 'xxx', 'yaoi', 'yid', 'yobbo', 'zipperhead'
    ];

    /**
     * Get active abusive words list (cached).
     */
    public static function getActiveWords(): array
    {
        try {
            return Cache::remember('abusive_words_list', 3600, function () {
                if (Schema::hasTable('abusing_words')) {
                    $dbWords = AbusingWord::where('is_active', true)->pluck('word')->toArray();
                    if (!empty($dbWords)) {
                        return array_values(array_unique(array_map('strtolower', array_merge(self::$defaultWords, $dbWords))));
                    }
                }
                return array_values(array_unique(array_map('strtolower', self::$defaultWords)));
            });
        } catch (\Throwable $e) {
            return array_values(array_unique(array_map('strtolower', self::$defaultWords)));
        }
    }

    /**
     * Clear abusive words cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('abusive_words_list');
    }

    /**
     * Check if a given text or input array contains any abusive words.
     * Returns matching word string if found, or null if clean.
     */
    public static function findAbusiveWord(mixed $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        $words = self::getActiveWords();
        if (empty($words)) {
            return null;
        }

        // If array, recursively check values
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                // Skip system/password/file/image upload fields
                $keyLower = strtolower((string)$key);
                if (in_array($keyLower, ['_token', '_method', 'password', 'password_confirmation', 'file', 'image', 'photo', 'logo', 'thumbnail', 'gallery', 'avatar', 'banner'])) {
                    continue;
                }
                $found = self::findAbusiveWord($val);
                if ($found) {
                    return $found;
                }
            }
            return null;
        }

        if (!is_string($input) && !is_numeric($input)) {
            return null;
        }

        $text = (string)$input;
        if (trim($text) === '') {
            return null;
        }

        $cleanText = strtolower($text);

        foreach ($words as $word) {
            $wordLower = strtolower(trim($word));
            if ($wordLower === '' || strlen($wordLower) < 2) continue;

            // Check word boundary or substring match
            $pattern = '/\b' . preg_quote($wordLower, '/') . '\b/i';
            if (preg_match($pattern, $cleanText) || str_contains($cleanText, $wordLower)) {
                return $word;
            }
        }

        return null;
    }
}
