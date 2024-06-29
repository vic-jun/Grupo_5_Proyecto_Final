<?php

class RankingModel{

    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function getRanking() {
        $sql = "SELECT nombre_de_usuario, puntaje, id FROM usuario WHERE rol = 'usuario'  ORDER BY puntaje DESC";
        return $this->baseDeDatos->query($sql);
    }

//    public function getRanking(){
//        $query = $this->db->prepare("SELECT * FROM ranking");
//        $query->execute();
//        return $query->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//    public function getRankingByUser($user){
//        $query = $this->db->prepare("SELECT * FROM ranking WHERE user = ?");
//        $query->execute([$user]);
//        return $query->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//    public function addRanking($user, $category, $score){
//        $query = $this->db->prepare("INSERT INTO ranking (user, category, score) VALUES (?, ?, ?)");
//        $query->execute([$user, $category, $score]);
//    }
//
//    public function updateRanking($user, $category, $score){
//        $query = $this->db->prepare("UPDATE ranking SET score = ? WHERE user = ? AND category = ?");
//        $query->execute([$score, $user, $category]);
//    }
//
//    public function deleteRanking($user, $category){
//        $query = $this->db->prepare("DELETE FROM ranking WHERE user = ? AND category = ?");
//        $query->execute([$user, $category]);
//    }
//
//    public function deleteRankingByUser($user){
//        $query = $this->db->prepare("DELETE FROM ranking WHERE user = ?");
//        $query->execute([$user]);
//    }


}
