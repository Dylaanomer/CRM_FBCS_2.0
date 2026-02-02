<?php
require_once __DIR__ . '/BaseModel.php';

class Customer extends BaseModel
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    public function getWithNote($id)
    {
        $stmt = $this->conn->prepare("SELECT customer, note FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
```