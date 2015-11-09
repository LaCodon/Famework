<?php

spl_autoload_register(function ($class) {
    if (substr($class, 0, 9) !== 'Famework\\') {
        /* If the class does not lie under the "Famework" namespace,
         * then we can exit immediately.
         */
        return;
    }
    /* All of the classes have names like "Famework\Foo", so we need
     * to replace the backslashes with frontslashes if we want the
     * name to map directly to a location in the filesystem.
     */
    /* But before, we have to remove the "Famework\" part,
     * because this autoloader is placed in the Famework folder.
     */
    $class = str_replace('Famework\\', '', $class);
    $class = str_replace('\\', '/', $class);
    // Check under the current directory.
    $path = dirname(__FILE__) . '/' . $class . '.php';
    if (is_readable($path)) {
        require $path;
        return;
    }
    /* Now, we have to search the class in the Mod_* folders.
     * For that, we need the folder and the class: 
     * {User}/{Folder}/{Class} --> part 2 and 3
     */
    $parts = explode('/', $class);
    if (count($parts) >= 2) {
        $modpath = dirname(__FILE__) . '/Mods/' . $parts[0] . '_' . $parts[1] . str_replace($parts[0] . '/' . $parts [1], '', $class) . '.php';
        if (is_readable($modpath)) {
            require $modpath;
            return;
        }
    }
});
