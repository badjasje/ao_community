<?
/**
 * Simple autoloader, so we don't need Composer just for this.
 */
class Autoloader {

    public static $loader;
    private $classDirectories;

    public static function init() {
        if (self::$loader == NULL) self::$loader = new self();
        return self::$loader;
    }

    // Make a list of locations of classfiles
    public function __construct() {
        $directories = array_diff(scandir(CLASS_PATH), array('..', '.'));
        $this->classDirectories = array();
        foreach($directories as $dir) {
           $files = array_diff(scandir(CLASS_PATH . DIRECTORY_SEPARATOR . $dir), array('..', '.'));
           foreach($files as $file) $this->classDirectories[$file] = $dir;
        }
        spl_autoload_register(array($this,'classes'));
    }

    // Try to load class, if it exists
    private function classes($class) {
        $file = strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $class)).'.class.php';
        if(isset($this->classDirectories[$file])) {
            $path = implode(DIRECTORY_SEPARATOR, array(CLASS_PATH, $this->classDirectories[$file], $file));
            if (file_exists($path)) {
                require($path);
                return true;
            }
        }
        return false;
    }

    // includes?
    // libraries?
}