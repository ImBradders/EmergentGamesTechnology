<?php

class Person {
    public $name;
    public $nickname;
    public $age;
    public $role;
    
    public function __construct($name, $nickname, $age, $role) {
        $this->name = $name;
        $this->nickname = $nickname;
        $this->age = $age;
        $this->role = $role;
    }
    
    // Attempt to save a person out to file.
    public function SaveToFile($path) {
        try {
            $file = fopen("people.txt", "a+");
            $toWrite = sprintf("%s, %s, %s, %s", $name, $nickname, $age, $role);
            fwrite($file, $toWrite);
            fclose($file);
        }
        catch (Exception $e) {
            // Scream really loud if it goes wrong.
            echo "Error when trying to save person to file.\n";
            echo $e->getMessage();
            return false;
        }
        
        // If it went right, say so!
        return true;
    }
}

?>