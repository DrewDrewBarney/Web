<?php

class Context {

    private static array $roots = [];
    private static string $siteTitle = 'No Site Title Set';
    private static array $sitePlan = [['caption' => 'No site plan set', 'url' => 'home']];
    private static string $iconPath = '';
    private static array $CSSpaths = [];
    private static string $commonImagePath = '';
    private static string $projectImagePath = '';

    // CLASS AUTOLOADING

    public static function loadClasses(array $roots): void {

        self::$roots = $roots;
        spl_autoload_register(function ($class) {
            self::loadClassFile($class);
        });
    }

    private static function loadClassFile(string $class): void {

        foreach (self::$roots as $root) {
            $directories = new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new RecursiveIteratorIterator($directories);

            foreach ($iterator as $file) {
                //echo $file->getRealPath() . '<br>';
                //echo $file->getBaseName() . '<br>';
                if ($file->isFile() && $file->getBasename() === $class . '.php') {
                    $path = $file->getRealPath();
                    //echo $path . '<br>';
                    include_once $path;
                }
            }
        }
    }

    // CONTEXT RELATED METHODS.
    // includes is called from the entry point index file. 
    // this is where to set the path to CSS, FavIcon and the Site Menu
    
    public static function setSitePlan(array $sitePlan): void {
        self::$sitePlan = $sitePlan;
    } 

    public static function setIconPath(string $path): void {
        self::$iconPath = $path;
    }

    public static function iconPath(): string {
        return self::relativeRootURL() . self::$iconPath;
    }

    public static function setSiteTitle(string $title): void {
        self::$siteTitle = $title;
    }

   
    public static function setCommonImagePath(string $path): void {
        self::$commonImagePath = Context::relativeRootURL() . $path;
    }
    
    public static function commonImagePath():string{
        return self::$commonImagePath;
    }
    
    public static function setProjectImagePath(string $path): void {
        self::$projectImagePath = Context::relativeRootURL() . $path;
    }
    
    public static function projectImagePath():string{
        return self::$projectImagePath;
    }

    public static function relativeRootURL(): string {
        $entryURL = rtrim(dirname($_SERVER['SCRIPT_NAME']));
        $parts = explode('/', $entryURL);
        $result = '';
        foreach (range(3, sizeof($parts)) as $part) {
            $result .= '../';
        }
        return $result;
    }

    public static function setCSSpaths(array $CSSpaths): void {
        foreach ($CSSpaths as $path){
             self::$CSSpaths[] = Context::relativeRootURL() . $path;
        }
    }

    public static function relativeCSSPaths(): array {
        return Tools::getFiles(self::$CSSpaths, 'css', true);
    }

   

    public static function siteTitle(): string {
        return self::$siteTitle;
    }

    public static function sitePlan(): array {
        return self::$sitePlan;
    }
}
