<?php
require_once '../../Database/connect.php';

class BackupUtil {
    private $conn;
    private $backupPath;
    
    public function __construct($connection) {
        $this->conn = $connection;
        $this->backupPath = __DIR__ . '/../../backups/';
        
        // Create backup directory if it doesn't exist
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }
    
    /**
     * Create a full database backup
     * 
     * @param int $adminId ID of the admin performing the backup
     * @return array Result with status and message
     */
    public function createBackup($adminId) {
        try {
            $tables = [];
            $result = $this->conn->query("SHOW TABLES");
            
            while ($row = $result->fetch_row()) {
                $tables[] = $row[0];
            }
            
            if (empty($tables)) {
                throw new Exception('No tables found in the database');
            }
            
            $return = "-- Database Backup --\n";
            $return .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            
            // Get table structures and data
            foreach ($tables as $table) {
                $return .= "--\n-- Table structure for table `$table`\n--\n\n";
                $return .= "DROP TABLE IF EXISTS `$table`;\n";
                
                $createTable = $this->conn->query("SHOW CREATE TABLE `$table`")->fetch_row();
                $return .= $createTable[1] . ";\n\n";
                
                // Get table data
                $result = $this->conn->query("SELECT * FROM `$table`");
                $numFields = $result->field_count;
                
                if ($result->num_rows > 0) {
                    $return .= "--\n-- Dumping data for table `$table`\n--\n\n";
                    
                    while ($row = $result->fetch_row()) {
                        $return .= "INSERT INTO `$table` VALUES(";
                        for ($i = 0; $i < $numFields; $i++) {
                            $row[$i] = addslashes($row[$i]);
                            $row[$i] = str_replace("\n", "\\n", $row[$i]);
                            if (isset($row[$i])) {
                                $return .= "'" . $row[$i] . "'";
                            } else {
                                $return .= "''";
                            }
                            if ($i < ($numFields - 1)) {
                                $return .= ',';
                            }
                        }
                        $return .= ");\n";
                    }
                    $return .= "\n";
                }
            }
            
            // Save to file
            $backupFile = $this->backupPath . 'backup_' . date('Y-m-d_His') . '.sql';
            file_put_contents($backupFile, $return);
            
            // Log the backup
            $this->logBackup($adminId, 'full', $backupFile);
            
            return [
                'status' => 'success',
                'message' => 'Backup created successfully',
                'file' => $backupFile,
                'size' => filesize($backupFile)
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Backup failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Restore database from backup file
     * 
     * @param string $backupFile Path to the backup file
     * @param int $adminId ID of the admin performing the restore
     * @return array Result with status and message
     */
    public function restoreBackup($backupFile, $adminId) {
        try {
            if (!file_exists($backupFile)) {
                throw new Exception('Backup file not found');
            }
            
            // Read the backup file
            $sql = file_get_contents($backupFile);
            
            // Execute the SQL
            $queries = explode(';', $sql);
            
            // Begin transaction
            $this->conn->begin_transaction();
            
            try {
                foreach ($queries as $query) {
                    $query = trim($query);
                    if (!empty($query)) {
                        $this->conn->query($query);
                    }
                }
                
                $this->conn->commit();
                
                // Log the restore
                $this->logBackup($adminId, 'restore', $backupFile);
                
                return [
                    'status' => 'success',
                    'message' => 'Database restored successfully'
                ];
                
            } catch (Exception $e) {
                $this->conn->rollback();
                throw $e;
            }
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Restore failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get list of available backups
     * 
     * @return array List of backup files with details
     */
    public function listBackups() {
        $backups = [];
        $files = glob($this->backupPath . '*.sql');
        
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'path' => $file,
                'size' => filesize($file),
                'created' => filemtime($file)
            ];
        }
        
        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return $b['created'] - $a['created'];
        });
        
        return $backups;
    }
    
    /**
     * Log backup/restore operation
     * 
     * @param int $adminId Admin ID
     * @param string $type Operation type (backup/restore)
     * @param string $filePath Path to the backup file
     * @return bool True on success
     */
    private function logBackup($adminId, $type, $filePath) {
        $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
        $backupType = ($type === 'restore') ? 'restore' : 'full';
        
        $sql = "INSERT INTO backup_logs (admin_id, backup_type, file_path, file_size) 
                VALUES (?, ?, ?, ?)";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", 
            $adminId,
            $backupType,
            $filePath,
            $fileSize
        );
        
        return $stmt->execute();
    }
    
    /**
     * Get backup logs
     * 
     * @param array $filters Optional filters (admin_id, type, date_from, date_to)
     * @return array List of backup logs
     */
    public function getBackupLogs($filters = []) {
        $where = [];
        $params = [];
        $types = '';
        
        // Build WHERE clause based on filters
        if (!empty($filters['admin_id'])) {
            $where[] = "admin_id = ?";
            $params[] = $filters['admin_id'];
            $types .= 'i';
        }
        
        if (!empty($filters['type'])) {
            $where[] = "backup_type = ?";
            $params[] = $filters['type'];
            $types .= 's';
        }
        
        if (!empty($filters['date_from'])) {
            $where[] = "created_at >= ?";
            $params[] = $filters['date_from'];
            $types .= 's';
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = "created_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
            $types .= 's';
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        $limit = isset($filters['limit']) ? (int)$filters['limit'] : 50;
        $offset = isset($filters['offset']) ? (int)$filters['offset'] : 0;
        
        $sql = "SELECT b.*, u.username 
                FROM backup_logs b 
                LEFT JOIN admin u ON b.admin_id = u.id 
                $whereClause 
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?";
                
        $stmt = $this->conn->prepare($sql);
        
        // Bind parameters if needed
        if (!empty($params)) {
            $stmt->bind_param($types . 'ii', ...array_merge($params, [$limit, $offset]));
        } else {
            $stmt->bind_param('ii', $limit, $offset);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $logs = [];
        
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
        
        return $logs;
    }
}

// Initialize backup utility
$backupUtil = new BackupUtil($con);
?>
