<?php

namespace App\Command\Assets;


use App\Controller\Application;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;

/**
 * Strings present in this command shall not be moved to translation files ad this command DOES generates such
 * and thus it's required to see what's going on in case of crash
 * Class BuildTranslationMessagesYamlFromAssetsCommand
 * @package App\Command\Assets
 */
class BuildTranslationMessagesYamlFromAssetsCommand extends Command
{
    protected static $defaultName = 'npl:assets:build-frontend-translation-file';

    const TRANSLATION_BACKEND_FOLDER     = './translations/backend';
    const TRANSLATION_OUTPUT_FILE_PATH   = "./translations/frontend/messages.json";
    const TRANSLATION_FILE_EXTENSION_YML = "yml";

    /**
     * @var Application $app
     */
    private $app;

    /**
     * @var SymfonyStyle $io
     */
    private $io = null;

    /**
     * @var Parser $yamlParser
     */
    private $yamlParser;

    public function __construct(Application $app, string $name = null) {
        parent::__construct($name);
        $this->app        = $app;
        $this->yamlParser = new YamlParser();
    }

    protected function configure()
    {
        $this
            ->setDescription("This command will get all the translations files from assets and wil build output bundle usable by symfony");
    }

    protected function initialize(InputInterface $input, OutputInterface $output) {
        parent::initialize($input, $output);
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note("Starting building translation messages Yaml file");
        {
            $translationFilesData = $this->getBackendTranslationFilesData();

            if( !empty($translationFilesData) ){
                $this->buildFrontendTranslationFile($translationFilesData);
                $this->validateOutputFrontendTranslationFile();
            }else{
                $message = "Translation data array is empty - does Your asset files even exist and are located in correct directory?";
                $io->warning($message);
                $this->app->getLoggerService()->getLogger()->warning($message);
            }
        }
        $io->newLine();
        $io->success("Finished building translation messages Yaml file");

        return 1;
    }

    /**
     * Will get the content of backend yml files
     *
     * @return array|null
     */
    private function getBackendTranslationFilesData(): ?array
    {
        $this->app->getLoggerService()->getLogger()->info("Started getting translation files data");

        $translationsAssetsDirectoryExist = file_exists(self::TRANSLATION_BACKEND_FOLDER);
        if( !$translationsAssetsDirectoryExist ){
            $this->app->getLoggerService()->getLogger()->critical("Translations assets directory does not exist: ",[
                "directory" => self::TRANSLATION_BACKEND_FOLDER,
            ]);
            return null;
        }

        $finder = new Finder();
        $finder->in(self::TRANSLATION_BACKEND_FOLDER);

        $translationFilesData = [];

        /**
         * Iterate over all files for all languages
         * @var SplFileInfo $file
         */
        foreach( $finder->files() as $file ){
            $groupName       = $file->getRelativePath();
            $fileExtension   = $file->getExtension();
            $translationFile = $file->getRealPath();

            if( self::TRANSLATION_FILE_EXTENSION_YML !== $fileExtension ){
                continue;
            }

            $this->app->getLoggerService()->getLogger()->info("Found file ({$translationFile}) for language ({$groupName})");

            $translationFileData = Yaml::parseFile($translationFile);

            // file might be empty, we must skip these or final parser will add invalid empty {}
            if( is_null($translationFileData) ){
                continue;
            }

            $translationFilesData[] = $translationFileData;
        }

        return $translationFilesData;
    }

    /**
     * Will put the files content in the json file
     *
     * @param array $translationFilesData
     * @return array
     */
    private function buildFrontendTranslationFile(array $translationFilesData): array
    {
        $this->app->getLoggerService()->getLogger()->info("Started building output messages file");

        $outputFilePath          = [];
        $allTranslationFilesData = [];

        // iterate over all groups
        foreach($translationFilesData as $filePath => $assetsDataArrays ){
            $allTranslationFilesData = array_merge($allTranslationFilesData, $assetsDataArrays);
        }

        $jsonData = json_encode($allTranslationFilesData);
        file_put_contents(self::TRANSLATION_OUTPUT_FILE_PATH, $jsonData);

        $this->app->getLoggerService()->getLogger()->info("Finished building output messages file");

        return $outputFilePath;
    }

    /**
     * Will validate the output json translation
     */
    private function validateOutputFrontendTranslationFile(): void
    {
        $frontendTranslationFileContent = file_get_contents(self::TRANSLATION_OUTPUT_FILE_PATH);

        json_decode($frontendTranslationFileContent);
        if( JSON_ERROR_NONE !== json_last_error() ){
            $message = "Output json is not valid, please check it!";
            $this->io->error(json_last_error_msg());
            $this->app->getLoggerService()->getLogger()->critical(json_last_error_msg());
        }

    }
}