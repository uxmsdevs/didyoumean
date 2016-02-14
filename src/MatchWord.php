<?php namespace Uxms\DidYouMean;

/**
 * Corrected word suggestions based on a dictionary by levenshtein function
 */
class MatchWord
{
    /* Holds language string */
    private $language;

    /* Holds word to compare */
    private $word;

    /* Holds an array which has first character to file mapping */
    private $activeMapper = [];

    /* Holds an array which has mapped dictionary words */
    private $activeDictionary = [];

    function __construct($language = 'en', $word = null)
    {
        $this->language = strtolower($language);
        $this->word = strtolower($word);
    }

    /**
     * Set language of given word
     *
     * @param string $language Language code
     *
     * @return MatchWord
     */
    public function setLanguage($language = null)
    {
        $this->language = is_null($language) ? 'en' : strtolower($language);

        return $this;
    }

    /**
     * Set word to check if exact match
     *
     * @param string $word Language code
     *
     * @return MatchWord
     */
    public function setWord($word = null)
    {
        $this->word = is_null($word) ? null : strtolower($word);

        return $this;
    }

    /**
     * Includes proper mapping and dictionary files.
     *
     * @return MatchWord
     */
    public function checkMatch()
    {
        /*
         * Assign word to variable
         */
        try {
            if ($this->word) {
                $wordFileName = $this->word[0];
            } else {
                throw new \Exception('Word not defined', 1);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }

        /*
         * Get proper mapper file
         */
        $mapperFileDir = dirname(__FILE__).'/dictionaries/'.$this->language.'Mapper.php';
        try {
            if (is_file($mapperFileDir)) {
                $this->activeMapper = include $mapperFileDir;
            } else {
                throw new \Exception('Proper mapper file could not found', 1);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }

        /*
         * Get proper dictionary file
         */
        $dictFileDir = dirname(__FILE__).'/dictionaries/'.$this->language.'/'.$this->activeMapper[$wordFileName];
        try {
            if (is_file($dictFileDir)) {
                $this->activeMapper = include $mapperFileDir;
            } else {
                throw new \Exception('Proper dictionary file could not found', 1);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }

        $this->activeDictionary = file($dictFileDir, FILE_IGNORE_NEW_LINES);

        return $this->compareWordWithDictionary();
    }

    /**
     * Check if given word is exact match against dictionary with levenshtein
     *
     * @return string
     */
    private function compareWordWithDictionary()
    {
        // no shortest distance found, yet
        $shortest = -1;

        // loop through words to find the closest
        foreach ($this->activeDictionary as $dictWord) {

            // calculate the distance between the input word,
            // and the current word
            $lev = levenshtein($this->word, $dictWord);

            // check for an exact match
            if ($lev == 0) {

                // closest word is this one (exact match)
                $closest = $dictWord;
                $shortest = 0;

                // break out of the loop; we've found an exact match
                break;
            }

            // if this distance is less than the next found shortest
            // distance, OR if a next shortest word has not yet been found
            if ($lev <= $shortest || $shortest < 0) {
                // set the closest match, and shortest distance
                $closest  = $dictWord;
                $shortest = $lev;
            }
        }

        if ($shortest == 0) {
            return json_encode([
                'status' => 1,
                'description' => 'Exact match',
                'closest' => $closest
            ]);
        } else {
            return json_encode([
                'status' => 0,
                'description' => 'Did you mean',
                'closest' => $closest
            ]);
        }

    }

}
