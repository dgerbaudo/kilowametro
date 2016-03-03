<?php

include("Connection.php");

class Stats
{

    public function renderPeriodOptions()
    {
        $conn = Connection::getConnection();

        $query = "SELECT DATE_FORMAT(period, '%Y-%m') as id,
                         DATE_FORMAT(period, '%m/%Y') as period
                  FROM kwm_data
                  WHERE period > '2010-01-01'
                    AND period < now()
                  GROUP BY period
                  ORDER BY 1 DESC";

        if ($result = $conn->query($query)) {

            while ($row = $result->fetch_object()) {
                $value = $row->id;
                $period = $row->period;

                echo "<option value='$value'>$period</option>";
            }

            $result->free();
        }

        $conn->close();

    }

    public function renderStats()
    {
        $conn = Connection::getConnection();

        $query = "SELECT DATE_FORMAT(period, '%Y-%m') as id,
                         DATE_FORMAT(period, '%m/%Y') as period
                  FROM kwm_data
                  WHERE period > '2010-01-01'
                    AND period < now()
                  GROUP BY period
                  ORDER BY 1 DESC ";

        if ($result = $conn->query($query)) {

            while ($row = $result->fetch_object()) {
                $value = $row->id;
                $period = $row->period;

                echo "<option value='$value'>$period</option>";
            }

            $result->free();
        }

        $conn->close();

    }

    public function getData($period)
    {
        $conn = Connection::getConnection();
        $period = $conn->real_escape_string($period) . "-01";
        $query = "SELECT p.province_name AS province,
                         COUNT(*) AS numrec,
                         SUM(d.kwh) AS sum_kwh,
                         SUM(d.amount) AS sum_amount,
                         AVG(d.kwh) AS avg_kwh,
                         AVG(d.amount) AS avg_amount,
                         AVG(d.amount/kwh) AS avg_amount_per_kwh,
                         MIN(d.amount/kwh) AS min_amount_per_kwh,
                         MAX(d.amount/kwh) AS max_amount_per_kwh
                  FROM `kwm_data` d
                  JOIN province p ON p.id = d.province_id
                  WHERE d.period = '$period'
                  GROUP BY province
                  ORDER BY avg_amount_per_kwh DESC";
        if ($result = $conn->query($query)) {

            while ($row = $result->fetch_object()) {
                $data[] = $row;
            }
            $result->free();
        }
        $conn->close();
        return $data;
    }

}
