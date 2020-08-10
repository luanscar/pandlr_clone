<?php

namespace App\Models;

use MF\Model\Model;


class Tweet extends Model {
    private $id;
    private $id_usuario;
    private $tweet;
    private $data_tweet;

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        return $this->$attr = $value;
    }

    public function salvarTweet()
    {
        $query = "insert into tweets(id_usuario, tweet)values(:id_usuario, :tweet)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue('id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue('tweet', $this->__get('tweet'));
        $stmt->execute();

        return $this;
    }

    public function getAll() {

        $query = "
        select 
            t.id, 
            t.id_usuario, 
            users.nome, 
            t.tweet, 
            DATE_FORMAT(t.data_tweet, '%d/%m/%Y %H:%i') as data
        from 
            tweets as t
            left join usuarios as users on (t.id_usuario = users.id)
        where 
            t.id_usuario = :id_usuario
            or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores
            where id_usuario = :id_usuario)
        order by
        data desc
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();
    
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    
        }

}