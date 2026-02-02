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
?><?php
// Sample data for demonstration purposes
$sample_overige = array(
    array('overige_id' => 'O001', 'customer' => 'Acme Corp', 'note' => 'Important client'),
    array('overige_id' => 'O002', 'customer' => 'Beta LLC', 'note' => 'Requires follow-up'),
    array('overige_id' => 'O003', 'customer' => 'Gamma Inc', 'note' => 'New customer')
);