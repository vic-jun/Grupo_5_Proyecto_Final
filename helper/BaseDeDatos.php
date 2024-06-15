<?php

class BaseDeDatos{
    private $baseDeDatos;

    public function __construct($servername, $user, $dbname, $password){
        $this->baseDeDatos = new mysqli($servername, $user, $password, $dbname);

        if ($this->baseDeDatos->connect_error){
            echo "Error: " . $this->baseDeDatos->connect_error;
        }
    }

    public function query($sql){
        $result = mysqli_query($this->baseDeDatos, $sql);

        if ($result instanceof mysqli_result) {
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } elseif ($result === true) {
            $rows = true;
        } else {
            $rows = "Error: " . mysqli_error($this->baseDeDatos);
        }
        return $rows;
    }

    public function execute($sql){
        mysqli_query($this->baseDeDatos, $sql);
    }

    public function __destruct(){
        mysqli_close($this->baseDeDatos);
    }
}