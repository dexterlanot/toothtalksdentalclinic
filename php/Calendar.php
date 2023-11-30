<?php
include("db_config.php");

session_start();

// Check if the dentist is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

class Calendar
{

    private $active_year, $active_month, $active_day;
    private $events = [];

    public function __construct($date = null)
    {
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }
    public function getPrevMonth()
    {
        return date('Y-m-d', strtotime('-1 month', strtotime($this->active_year . '-' . $this->active_month . '-01')));
    }

    public function getNextMonth()
    {
        return date('Y-m-d', strtotime('+1 month', strtotime($this->active_year . '-' . $this->active_month . '-01')));
    }

    public function fetchAppointmentsFromDatabase()
    {
        global $db;

        // Fetch appointments from the database and return as JSON
        $appointments = [];

        $sql_fetch_appointments = "SELECT * FROM appointment WHERE DentistID = {$_SESSION['user_id']} AND (Status = 'Pending' OR Status = 'Approved')";
        $result = $db->query($sql_fetch_appointments);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = array(
                    'title' => $row['TreatmentType'],
                    'start' => $row['Date'] . 'T' . $row['Time'],
                    'end' => $row['Date'] . 'T' . $row['Time'],
                    'color' => $row['Status'] == 'Pending' ? 'event-pending' : 'event-approved',
                );
            }
        }

        return $appointments;
    }


    public function __toString()
    {
        $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';
        foreach ($days as $day) {
            $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
        }
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '
                <div class="day_num ignore">
                    ' . ($num_days_last_month - $i + 1) . '
                </div>
            ';
        }
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = '';
            if ($i == $this->active_day) {
                $selected = ' selected';
            }
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';
            foreach ($this->events as $event) {
                for ($d = 0; $d <= ($event[2] - 1); $d++) {
                    if (date('y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
                        $html .= '<div class="event' . $event[3] . '">';
                        $html .= $event[0] . '<br>';
                        $html .= 'Time: ' . date('h:i A', strtotime($event[1])) . '<br>';
                        $html .= '</div>';
                    }
                }
            }
            $html .= '</div>';
        }
        for ($i = 1; $i <= (42 - $num_days - max($first_day_of_week, 0)); $i++) {
            $html .= '
                <div class="day_num ignore">
                    ' . $i . '
                </div>
            ';
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}

// Instantiate the Calendar class
$calendar = new Calendar();

// Fetch appointments from the database
$appointments = $calendar->fetchAppointmentsFromDatabase();

// Add fetched appointments to the calendar
foreach ($appointments as $appointment) {
    $calendar->add_event($appointment['title'], $appointment['start'], 1, $appointment['color']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="icon" type="image/x-icon" href="../assets/client-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body>
    <?php include("dentist_sidebar.php") ?>
    <section class="calendar-section">
        <!-- Echo the Calendar object to display the calendar with events -->
        <?php echo $calendar; ?>
    </section>
</body>

</html>