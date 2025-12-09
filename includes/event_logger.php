<?php
require_once 'Database/connect.php';

class EventLogger {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Log an event to the database
     * 
     * @param int $userId User ID (optional)
     * @param string $eventType Type of event (e.g., 'login', 'booking', 'admin_action')
     * @param string $description Description of the event
     * @param array $data Additional event data (will be JSON encoded)
     * @return bool True on success, false on failure
     */
    public function logEvent($userId = null, $eventType, $description, $data = []) {
        $userId = $userId ?? ($_SESSION['user_id'] ?? null);
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        // Add additional context to data
        $data = array_merge($data, [
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'page_url' => $_SERVER['REQUEST_URI'] ?? ''
        ]);
        
        $sql = "INSERT INTO event_logs (user_id, event_type, event_description, event_data, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?)";
                
        $stmt = $this->conn->prepare($sql);
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $stmt->bind_param("isssss", 
            $userId,
            $eventType,
            $description,
            $jsonData,
            $ipAddress,
            $userAgent
        );
        
        return $stmt->execute();
    }
    
    /**
     * Get event logs with optional filters
     * 
     * @param array $filters Associative array of filters (user_id, event_type, date_from, date_to, limit, offset)
     * @return array Array of event logs
     */
    public function getEventLogs($filters = []) {
        $where = [];
        $params = [];
        $types = '';
        
        // Build WHERE clause based on filters
        if (!empty($filters['user_id'])) {
            $where[] = "user_id = ?";
            $params[] = $filters['user_id'];
            $types .= 'i';
        }
        
        if (!empty($filters['event_type'])) {
            $where[] = "event_type = ?";
            $params[] = $filters['event_type'];
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
        
        $sql = "SELECT l.*, u.username, u.email 
                FROM event_logs l 
                LEFT JOIN users u ON l.user_id = u.id 
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
            if (!empty($row['event_data'])) {
                $row['event_data'] = json_decode($row['event_data'], true);
            }
            $logs[] = $row;
        }
        
        return $logs;
    }
    
    /**
     * Get statistics about events
     * 
     * @param string $period Period for statistics (day, week, month, year)
     * @return array Statistics data
     */
    public function getEventStats($period = 'month') {
        $interval = '';
        
        switch (strtolower($period)) {
            case 'day':
                $interval = '1 DAY';
                $format = '%Y-%m-%d %H:00:00';
                break;
            case 'week':
                $interval = '7 DAY';
                $format = '%Y-%m-%d';
                break;
            case 'year':
                $interval = '1 YEAR';
                $format = '%Y-%m';
                break;
            case 'month':
            default:
                $interval = '1 MONTH';
                $format = '%Y-%m';
                break;
        }
        
        $sql = "SELECT 
                    DATE_FORMAT(created_at, ?) as period,
                    COUNT(*) as total_events,
                    COUNT(DISTINCT user_id) as unique_users,
                    event_type,
                    COUNT(*) as type_count
                FROM event_logs
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL $interval)
                GROUP BY period, event_type
                ORDER BY period DESC, type_count DESC";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $format);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stats = [];
        while ($row = $result->fetch_assoc()) {
            $stats[] = $row;
        }
        
        return $stats;
    }
}

// Initialize logger
$logger = new EventLogger($con);
?>
