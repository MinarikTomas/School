<?php
require_once 'MyPdo.php';
require_once 'Invention.php';
class Inventor
{
    /* @var MyPDO */
    protected $db;

    protected int $id;
    protected string $name;
    protected string $surname;
    protected DateTime $birth_date;
    protected string $birth_place;
    protected string $description;
    protected DateTime $death_date;
    protected string $death_place;
    protected $inventions;


    public function __construct()
    {
        $this->db = MyPDO::instance();
        $this->inventions = [];
    }

    /**
     * @return DateTime
     */
    public function getDeathDate(): DateTime
    {
        return $this->death_date;
    }

    /**
     * @param string
     */
    public function setDeathDate(string $death_date): void
    {
        $this->death_date = DateTime::createFromFormat('d.m.Y', $death_date);
    }

    /**
     * @return string
     */
    public function getDeathPlace(): string
    {
        return $this->death_place;
    }

    /**
     * @param string $death_place
     */
    public function setDeathPlace(string $death_place): void
    {
        $this->death_place = $death_place;
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return DateTime
     */
    public function getBirthDate(): DateTime
    {
        return $this->birth_date;
    }

    /**
     * @param string
     */
    public function setBirthDate(string $birth_date): void
    {
        $this->birth_date = DateTime::createFromFormat('d.m.Y', $birth_date);
    }

    /**
     * @return string
     */
    public function getBirthPlace(): string
    {
        return $this->birth_place;
    }

    /**
     * @param string $birth_place
     */
    public function setBirthPlace(string $birth_place): void
    {
        $this->birth_place = $birth_place;
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

    public function addInvention($invention){
        array_push($this->inventions, $invention);
    }

    public static function all(){
        return MyPDO::instance()->run("SELECT * FROM inventors")->fetchAll();
}

    public static function find($id)
    {
        $data =  MyPDO::instance()->run("SELECT i1.id, i1.name, i1.surname, i1.birth_date, i1.birth_place, i1.description,
                                    i1.death_date, i1.death_place, i2.id as invention_id, i2.invention_date as year,
                                    i2.description as invention_desc FROM inventors i1 
                                    JOIN inventions i2 ON i1.id = i2.inventor_id WHERE i1.id = ?", [$id])->fetchAll();
        if(!$data){
            $data = Inventor::findWithoutInventions($id);
            if($data){
                return $data;
            }else{
                http_response_code(404);
                return false;
            }
        }
        $user = new Inventor();
        $user->id = $data[0]['id'];
        $user->name = $data[0]['name'];
        $user->surname = $data[0]['surname'];
        $user->description = $data[0]['description'];
        $user->birth_date = DateTime::createFromFormat('Y-m-d', $data[0]['birth_date']);
        $user->birth_place = $data[0]['birth_place'];
        if($data[0]['death_date']){
            $user->death_date = DateTime::createFromFormat('Y-m-d', $data[0]['death_date']);
        }
        if($data[0]['death_place']){
            $user->death_place = $data[0]['death_place'];
        }
        foreach($data as $item){
            $invention = new Invention();
            $invention->setId($item['invention_id']);
            $invention->setDescription($item['invention_desc']);
            if(isset($item['year'])){
                $invention->setInventionDate($item['year']);
            }
            $invention->setInventorId($user->getId());
            array_push($user->inventions, $invention);
        }
        return $user;
    }

    public static function findWithoutInventions($id): bool|Inventor
    {
        $data = MyPDO::instance()->run("SELECT * FROM inventors WHERE id = ?", [$id])->fetch();
        if (!$data) {
            http_response_code(404);
            return false;
        }

        $user = new Inventor();
        $user->id = $data['id'];
        $user->name = $data['name'];
        $user->surname = $data['surname'];
        $user->description = $data['description'];
        $user->birth_date = DateTime::createFromFormat('Y-m-d', $data['birth_date']);
        $user->birth_place = $data['birth_place'];
        if($data['death_date']){
            $user->death_date = DateTime::createFromFormat('Y-m-d', $data['death_date']);
        }
        if($data['death_place']){
            $user->death_place = $data['death_place'];
        }
        return $user;
    }

    public static function findBySurname($surname): bool|array
    {
        $id = MyPDO::instance()->run("SELECT id FROM inventors WHERE surname = ?", [$surname])->fetchAll();
        if(empty($id)){
            http_response_code(404);
            return false;
        }
        $results = [];
        foreach ($id as $item){
            $inventor = Inventor::find($item['id']);
            array_push($results, $inventor->toArray());
        }
        return $results;
    }

    public static function findByYear($year){
        return MyPDO::instance()->run("SELECT * FROM inventors WHERE year(birth_date) = ? OR year(death_date) = ?", [$year, $year])->fetchAll();
    }

    public function save(){
        $this->db->run("INSERT INTO inventors 
            (`name`, `surname`, `birth_date`, `birth_place`, `description`, `death_date`, `death_place`) values (?, ?, ?, ?, ?, ?, ?)",
            [$this->name, $this->surname, $this->birth_date->format('Y-m-d'), $this->birth_place, $this->description,
                isset($this->death_date) ? $this->death_date->format("Y-m-d") : null, $this->death_place ?? null]);
        $this->id = $this->db->lastInsertId();
    }

    public function destroy(): bool
    {
        MyPDO::instance()->run("delete from inventors where id = ?",
            [$this->id]);
        unset($this->id);
        return true;
    }

    public static function update($id, $data): bool|Inventor
    {
        $inventor = Inventor::find($id);
        if (!$inventor){
            http_response_code(404);
            return false;
        }
        if(!empty($data['name'])){
            $inventor->name = $data['name'];
        }
        if(!empty($data['surname'])){
            $inventor->surname = $data['surname'];
        }
        if(!empty($data['birth_date'])){
            $inventor->setBirthDate($data['birth_date']);
        }
        if(!empty($data['birth_place'])){
            $inventor->birth_place = $data['birth_place'];
        }
        if(!empty($data['description'])){
            $inventor->description = $data['description'];
        }
        if(!empty($data['death_date'])){
            $inventor->setDeathDate($data['death_date']);
        }
        if(!empty($data['death_place'])){
            $inventor->death_place = $data['death_place'];
        }
        try {
            $inventor->updateDB();
        }catch (PDOException $e){
            http_response_code(400);
            return false;
        }
        return $inventor;
    }

    public function updateDB(){
        MyPDO::instance()->run("UPDATE inventors SET name = ?, surname = ?, birth_date = ?, birth_place = ?,
                     description = ?, death_date = ?, death_place = ?
                     WHERE id = ?",
        [$this->name, $this->surname, $this->birth_date->format('Y-m-d'), $this->birth_place, $this->description,
            isset($this->death_date) ? $this->death_date->format("Y-m-d") : null, $this->death_place ?? null, $this->id]);
    }

    public function toArray(){
        $data = ['id' => $this->id, 'name' => $this->name, 'surname' => $this->surname, 'description' => $this->description,
            'birth_date'=> $this->birth_date->format("Y.m.d"), 'birth_place' => $this->birth_place,
            'death_date' => isset($this->death_date) ? $this->death_date->format('Y-m-d') : null,
            'death_place' => $this->death_place ?? null, 'inventions' => []];
        foreach ($this->inventions as $item){
            array_push($data['inventions'], $item->toArray());
        }
        return $data;
    }
}