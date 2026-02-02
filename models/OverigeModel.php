<?php
require_once __DIR__ . '/BaseModel.php';

class OverigeModel extends BaseModel
{
    protected $table = 'overige';
    protected $primaryKey = 'overige_id';

    public function getWithNote($id)
    {
        $stmt = $this->conn->prepare("SELECT customer, note FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
