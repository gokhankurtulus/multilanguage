<?php
/**
 * @author Gökhan Kurtuluş @gokhankurtulus
 * Date: 28.12.2023 Time: 02:44
 */


namespace MultiLanguage;

final class MultiLanguage
{
    private static string $directoryPath = "";
    private static array $allowedLanguages = [];
    private static string $defaultLanguage = "";
    private static string $currentLanguage = "";

    /**
     * @param string $text text key to translate.
     * @param string|null $lang optional. If you want to translate to specific language set this to the language you want.
     * If not given tries to get current language, if current language is not specified gets default language.
     * It may cause exception if you don't specify this and both current language and default language have not been set before.
     * @param array $replacement optional. array for manipulating strings in language file. Keys must be specified exactly the same in the language file.
     * @return string
     * @throws LanguageException
     */
    public static function translate(
        string  $text,
        ?string $lang = null,
        array   $replacement = []
    ): string
    {
        if (empty($lang)) {
            if (!self::getCurrentLanguage() && !self::getDefaultLanguage()) {
                throw new LanguageException("The current language and the default language have not been set before.");
            }
            $lang = self::getCurrentLanguage() ?: self::getDefaultLanguage();
        }

        if (!self::isAllowedLanguage($lang)) {
            throw new LanguageException("Language '$lang' is not allowed.");
        }

        if (!self::isDirectoryExist(self::getDirectoryPath())) {
            throw new LanguageException("Language directory doesn't exist.");

        }
        if (!self::isFileExist($lang)) {
            throw new LanguageException("Language file '$lang' doesn't exist.");
        }

        $raw = self::getRaw($lang);
        if (!$raw) {
            throw new LanguageException("Failed to read raw data.");
        }

        $data = self::getData($raw);
        if (!$data && $lang != self::getDefaultLanguage()) {
            throw new LanguageException("Failed to get array data.");
        }

        if (isset($replacement)) {
            return strtr($data[$text], $replacement) ?: $text;
        }

        return $data[$text] ?: $text;
    }

    /**
     * @return string
     */
    public static function getDirectoryPath(): string
    {
        return self::$directoryPath;
    }

    /**
     * @param string $directoryPath
     * @param bool $force optional. Set true if you want to force create directory path.
     * @return void
     * @throws LanguageException if force is false and language directory doesn't exist.
     */
    public static function setDirectoryPath(string $directoryPath, bool $force = false): void
    {
        if (!self::isDirectoryExist($directoryPath)) {
            if (!$force)
                throw new LanguageException("Language directory doesn't exist.");
            mkdir($directoryPath);
        }
        self::$directoryPath = $directoryPath;
    }

    /**
     * @return array
     */
    public static function getAllowedLanguages(): array
    {
        return self::$allowedLanguages;
    }

    /**
     * @param array $languages
     * @return void
     */
    public static function setAllowedLanguages(array $languages): void
    {
        self::$allowedLanguages = $languages;
    }

    /**
     * @return string
     */
    public static function getDefaultLanguage(): string
    {
        return self::$defaultLanguage;
    }

    /**
     * @param string $lang
     * @return void
     * @throws LanguageException if language not allowed.
     */
    public static function setDefaultLanguage(string $lang): void
    {
        if (!self::isAllowedLanguage($lang)) {
            throw new LanguageException("Language '$lang' is not allowed.");
        }
        self::$defaultLanguage = $lang;
    }

    /**
     * @return string
     */
    public static function getCurrentLanguage(): string
    {
        return self::$currentLanguage;
    }

    /**
     * @param string $lang
     * @return void
     */
    public static function setCurrentLanguage(string $lang): void
    {
        self::$currentLanguage = $lang;
    }

    /**
     * @param string $lang
     * @return bool
     */
    public static function isAllowedLanguage(string $lang): bool
    {
        return in_array($lang, self::getAllowedLanguages());
    }

    /**
     * @param string $path
     * @return bool
     */
    protected static function isDirectoryExist(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * @param string $language
     * @return bool
     */
    protected static function isFileExist(string $language): bool
    {
        return is_file(self::getDirectoryPath() . DIRECTORY_SEPARATOR . "$language.json");
    }

    /**
     * @param string $language
     * @return false|string
     */
    protected static function getRaw(string $language): false|string
    {
        return @file_get_contents(self::getDirectoryPath() . DIRECTORY_SEPARATOR . "$language.json");
    }

    /**
     * @param string $raw
     * @return false|array
     */
    protected static function getData(string $raw): false|array
    {
        $decodedData = json_decode($raw, true);
        if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        return $decodedData;
    }
}