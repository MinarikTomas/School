<?php
require_once "MyPdo.php";

class Invention
{
    /* @var MyPDO */
    protected $db;

    protected int $id;
    protected string $inventor_id;
    protected DateTime $invention_date;
    protected string $description;

    public function __construct()
    {
        $this->db = MyPDO::instance();
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getInventorId(): string
    {
        return $this->inventor_id;
    }

    /**
     * @param string $inventor_id
     */
    public function setInventorId(string $inventor_id): void
    {
        $this->inventor_id = $inventor_id;
    }

    /**
     * @return DateTime
     */
    public function getInventionDate(): DateTime
    {
        return $this->invention_date;
    }

    /**
     * @param string $invention_date
     */
    public function setInventionDate(string $invention_date): void
    {
        if($invention_date) {
            $this->invention_date = DateTime::createFromFormat('Y-m-d', $invention_date);
        }
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public static function all(){
        return MyPDO::instance()->run("SELECT i1.name, i1.surname, i2.id, year(i2.invention_date) as `year`, i2.description
                                            FROM inventors i1 JOIN inventions i2 ON i1.id = i2.inventor_id")->fetchAll();
    }

    public static function findByCentury($century){
        $data = MyPDO::instance()->run("SELECT i1.name, i1.surname, i2.id, year(i2.invention_date) as `year`, i2.description
                                            FROM inventors i1 JOIN inventions i2 ON i1.id = i2.inventor_id
                                            WHERE FLOOR((year(invention_date)+99)/100) = ?", [$century])->fetchAll();
        if(empty($data)){
            http_response_code(404);
            return false;
        }
        return $data;
    }

    public static function findByYear($year){
        return MyPDO::instance()->run("SELECT id, inventor_id, year(invention_date) as `year`, description FROM inventions WHERE year(invention_date) = ?", [$year])->fetchAll();
    }

    public function save()
    {
        $this->db->run("INSERT into inventions 
            (`inventor_id`, `invention_date`, `description`) values (?, ?, ?)",
            [$this->inventor_id, isset($this->invention_date) ? $this->invention_date->format('Y-m-d') : null, $this->description]);
        $this->id = $this->db->lastInsertId();
    }

    public function toArray(){
        return ['id' => $this->id, 'description' => $this->description, 'year' => isset($this->invention_date) ? $this->invention_date->format("Y") : null, 'inventor_id' => $this->inventor_id];
    }
}