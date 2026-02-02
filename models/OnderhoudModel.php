<?php
require_once __DIR__ . '/BaseModel.php';

class OnderhoudModel extends BaseModel
{
    protected $table = 'onderhoud';
    protected $primaryKey = 'onderhoud_id';

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
$sample_customers = array(
    array('customer_id' => 'C001', 'customer' => 'Acme Corp', 'note' => 'Important client'),
    array('customer_id' => 'C002', 'customer' => 'Beta LLC', 'note' => 'Requires follow-up'),
    array('customer_id' => 'C003', 'customer' => 'Gamma Inc', 'note' => 'New customer')
);