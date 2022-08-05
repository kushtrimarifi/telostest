<?php
require_once('Database.php');
class Events
{
    public $points = array();
    public $labels = array();
    public function __construct()
    {
        $this->db = new Database();
    }
    public function query_db()
    {
        $result = $this->db->mysqli->query("SELECT * FROM Events order by init_time asc");
        return $result;
    }
    public function getEvents()
    {

        $actualdate = null;
        $lastdate = null;
        $array = [];
        $events = new Events();
        $result = $events->query_db();
        foreach ($result as $row) {
            $start_date = is_numeric($row['init_time']) ? $row['init_time'] : strtotime($row['init_time']);
            $end_date = is_numeric($row['end_time']) ? $row['end_time'] : strtotime($row['end_time']);
            if (!($start_date && $end_date)) continue;

            if (!isset($actualdate)) {
                $actualdate = gmdate('Y-m-d\TH:i:s', $start_date);
                $lastdate = $actualdate;
            }
            while (strtotime($actualdate) < (int)$start_date) {
                $lastdate = $actualdate;
                $actualdate = gmdate('Y-m-d\TH:i:s', ((int)(strtotime($actualdate) + 5)));
                $key = $actualdate;
                $array[$key] = 0;
            }
            if (((int)$start_date >= strtotime($lastdate) && (int)$start_date <= strtotime($actualdate))  || ((int)$end_date >= strtotime($lastdate) && (int)$end_date <= strtotime($actualdate))) {
                $key = $actualdate;
                if (!isset($array[$key])) {
                    $array[$key] = 0;
                }
                $array[$key]++;
            } else {
                $lastdate = $actualdate;
                $actualdate = gmdate('Y-m-d\TH:i:s', ((int)(strtotime($actualdate) + 5)));
                $key = $actualdate;
                $array[$key] = 0;
            }
        }
        foreach ($array as $interval => $count) {
            $this->points[] = $count;
            $this->labels[] = $interval;
        }
    }
}
