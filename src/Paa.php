<?php

use Cocur\Slugify\Slugify;
use League\Csv\Reader;
use League\Csv\ResultSet;
use League\Csv\Statement;
use League\Csv\Writer;
use Plasticbrain\FlashMessages\FlashMessages;

class Paa
{

    CONST DEPTH = [0, 1, 2, 'max'];
    CONST LANG = ['fr', 'en'];
    CONST PATH_QUESTIONS = "assets/questions/";

    /**
     * @var Slugify
     */
    private $slugify;
    /**
     * @var FlashMessages
     */
    private $msg;
    /**
     * @var string
     */
    private $lang = 'fr';

    /**
     * Paa constructor.
     * @param Slugify $slugify
     * @param FlashMessages $msg
     */
    public function __construct(Slugify $slugify, FlashMessages $msg)
    {
        $this->slugify = $slugify;
        $this->msg = $msg;
    }

    /**
     * @param Slugify $slugify
     * @param FlashMessages $msg
     * @return array
     * @throws \League\Csv\Exception
     */
    public function inputKeywordsPost(): array
    {
        $issetInputKeyword = ParamsRequest::methodIsset($_POST, 'Inputkeyword');
        $issetDepth = ParamsRequest::methodIsset($_POST, 'depth');
        $emptyInputKeyword = ParamsRequest::methodEmpty($_POST, 'Inputkeyword', true);

        if ($issetInputKeyword && $emptyInputKeyword && $issetDepth) {
            $array_depth = self::DEPTH;
            if (in_array(ParamsRequest::post('depth'), $array_depth)) {
                $keyword = HelperCharacters::escape(ParamsRequest::post('Inputkeyword'));
                $depth = ParamsRequest::post('depth');

                $slug = $this->slugify->slugify($keyword);
                $dirname = self::PATH_QUESTIONS . $slug . '/';

                // Scraping google Paa and create CSV
                $this->langPaa();
                $this->fileRenderCSV($keyword, $depth, $dirname);

                // Read csv and getting results
                $records = $this->dataWithLimitCSV($depth, $dirname, 500);
                foreach ($records as $offset => $record) {
                    $paa_record[] = $record;
                }

                $lang = $this->lang;
                return [$keyword, $paa_record, $depth, $lang];
            }
        }

        return ['', [], '', 'fr'];
    }

    /**
     *
     */
    public function exportCSV(): void
    {
        $slug = HelperCharacters::escape(ParamsRequest::get('keyword'));
        $array_depth = self::DEPTH;

        $depth = ParamsRequest::get('depth');
        $this->lang = ParamsRequest::get('lang');
        if (in_array($depth, $array_depth)) {
            $dirname = self::PATH_QUESTIONS . $slug . '/';
            $this->openAndCreateFileCSV($depth, $dirname, $slug);
        }
    }

    /**
     * @return bool
     */
    public static function inArrayDepth(): bool
    {
        return in_array(ParamsRequest::post('depth'), self::DEPTH) === true;
    }

    /**
     * @param FlashMessages $msg
     * @param string $message
     * @param bool $code
     */
    public function renderError(string $message, bool $code = false): void
    {
        $this->msg->error($message);
        if ($code) {
            $this->msg->add('<ol>
                        <li><kbd>chown -R www-data assets</kbd></li>
                        <li><kbd>chgrp -R www-data assets</kbd></li>
                        <li><kbd>chmod -R 777 assets</kbd></li>
                    </ol>');   
        }
        header('location: index.php');
        exit;
    }

    /**
     * @param string $depth
     * @param string $dirname
     * @param string $slug
     */
    private function openAndCreateFileCSV(string $depth, string $dirname, string $slug): void
    {
        if (file_exists($dirname . 'paa-' . $this->lang . '-' . $depth . '.csv')) {
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="' . $slug . '.csv"');
            $reader = Reader::createFromPath($dirname . 'paa-' . $this->lang . '-' . $depth . '.csv', 'r');
            $reader->output();
            die();
        }
    }

    /**
     * @param string $keyword
     * @param string $depth
     * @param string $dirname
     */
    private function fileRenderCSV(string $keyword, string $depth, string $dirname)
    {
        if (!file_exists($dirname . 'paa-' . $this->lang . '-' . $depth . '.csv')) {
            $output = shell_exec("/usr/bin/node assets/js/get_paas.js --clicks=$depth --kw=\"$keyword\" --lang={$this->lang}");
            $delimiter = explode("==", $output);
            if (!isset($delimiter[2])) {
                $this->renderError('Oops, Essayez avec un autre terme :(');
            }
            if (!file_exists($dirname)) {
                $this->createDir(self::PATH_QUESTIONS);
                $this->createDir($dirname);
            }
            $this->createAndDataCSV($delimiter, $depth, $dirname);
        }
    }

    /**
     * @param array $delimiter
     * @param string $depth
     * @param string $dirname
     */
    private function createAndDataCSV(array $delimiter, string $depth, string $dirname): void
    {
        $wrap_line = explode("\n", trim($delimiter[2]));
        foreach ($wrap_line as $wrap) {
            $csv_construct[] = [$wrap];
        }
        $writer = Writer::createFromPath($dirname . 'paa-' . $this->lang . '-' . $depth . '.csv', 'w+');
        $writer->insertAll($csv_construct);
    }

    /**
     * @param string $depth
     * @param string $dirname
     * @param int $limit
     * @return ResultSet
     * @throws \League\Csv\Exception
     */
    private function dataWithLimitCSV(string $depth, string $dirname, int $limit): ResultSet
    {
        $csv = Reader::createFromPath($dirname . 'paa-' . $this->lang . '-' . $depth . '.csv', 'r');
        $stmt = (new Statement())->limit($limit);
        return $stmt->process($csv);
    }

    /**
     * @return string
     */
    private function langPaa(): string
    {
        $langPaa = ParamsRequest::post('lang');
        $langPaaIsset = ParamsRequest::methodIsset($_POST, 'lang');
        $langPaaNotEmpty = ParamsRequest::methodEmpty($_POST, 'lang', true);
        if ($langPaaIsset && $langPaaNotEmpty) {
            if (in_array($langPaa, self::LANG)) {
                $this->lang = $langPaa;
                return $this->lang;
            }
            return $this->lang;
        }
        return $this->lang;
    }

    /**
     * @return bool
     */
    private function createDir(string $dirname): bool
    {
        if (!file_exists($dirname)) {
            if (!mkdir($dirname, 0777, true)) {
                $this->renderError('Oops, Vous devez donner les droits nécessaires au dossier <strong>assets</strong> en <strong>root</strong> (sudo ou su) comme suit: ', true);
                return false;
            }
            return true;
        }
        return true;
    }
}