<?php

class Word
{
    protected MyPdo $db;
    protected int $id;
    protected string $title;



    public function __construct(MyPDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function find($id){
        $data = $this->db->run("SELECT * FROM words WHERE id = ?", [$id])->fetch();
        $this->id = $data['id'];
        $this->title = $data['title'];
    }

    public function save(){
        $this->db->run("INSERT INTO `words`(`title`) VALUES (?)", [$this->title]);
        $this->id = $this->db->lastInsertId();
    }
}