<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
  <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      writePaydatesForYear(test_input($_POST["year"]));
    }

    function writePaydatesForYear($year) {
      if(!$year) { $year = date('Y'); }

      $dates = array();
      $file = fopen('myPayDates.csv', 'w');

      fwrite($file, 'Month,PayDate,BonusDate' . PHP_EOL);
      for ($i = 0; $i < 12; $i++){
        $date = date(strtotime($year . '-' . ($i + 1) . '-1'));

        $month = new Month($date);
        fwrite($file, $month->toString() . PHP_EOL);
      }
      fclose($file);
    }

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    class Month {
      var $name;
      var $payDate;
      var $bonusDate;

      function __construct($date) {
        $this->name = date('F', $date);
        $this->payDate = $this->payDate($date);
        $this->bonusDate = $this->bonusDate($date);
      }

      function payDate($date) {
        return strtotime('last weekday next month' . date("F Y", $date));
      }

      function bonusDate($date) {
        $fifteenth = strtotime('+14 day', $date);

        if(date('N', $fifteenth) >= 6) {
          return strtotime('next Wednesday', $fifteenth);
        }

        return $fifteenth;
      }

      function toString() {
        return $this->name . ',' . date('d', $this->payDate) . ',' . date('d', $this->bonusDate);
      }
    }
    
  ?> 

  <h2>Pay Date calculation</h2>
      
  <form method='POST' action='<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>'>
      <div>
        <label>Year:</label>
        <input type='number' name='year'>
      </div>
    </table>

    <input type='submit' name='submit' value='Submit'> 
  </form>


 </body>
</html>
