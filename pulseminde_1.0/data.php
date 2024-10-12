<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css?v=1.0">
    <style>
        /* Styling the button */
        .refresh-button {
            background-color: hsl(211, 100%, 50%);
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 19px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            
        }
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            
        }

        li {
            float: left;
            font-size: 30px;
        }

        li a {
             display: block;
             color: black;
             text-align: center;
             
             padding: 14px 16px;
             text-decoration: none;
        }

        

        thead{
            background-color: RGB(0,0,0,0.25) ;
        }

        tbody{
            background-color: RGB(0,0,0,0.15) ;
        }
        table{
            margin: auto;
            padding: 20px;
        }
        h1{
            text-align: center;
            font-size: 48px;
            font-weight: bold;
            color: hsl(211, 100%, 50%);
            
        }
        button:hover {
            
            color:red;
        }
        
    </style>
</head>
<body>
<ul>
  <li><a class="active" href="index.html"><img src="Home.png" alt="Snow" style="width:20%"  align="left"></a></li>
  <li style="float:right"><button  class="refresh-button" onclick="refreshPage()"><span class=reload>&#x21bb;</span>Refresh</button>
      <script>
      function refreshPage() {
        window.location.reload(); // Reloads the current page
      }
     </script>
  </li>
</ul>
    
    <h1>Patient Health Data: Temperature, Heart Rate, Stress Index</h1>

    <table style="border: 1px solide white ;  text-align: center; font-size: 20px; width:100%; " >
        <thead>
            <tr >
                <th>Test ID</th>
                <th>Temperature</th>
                <th>Pulse rate</th>
                <th>Stress index</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>

        <tbody>
            <?php 
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "pulsemind";

            $connection = new mysqli($servername, $username, $password, $database);

            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }
            
            // SQL query to order by 'id' in descending order
            $sql = "SELECT * FROM esp32 ORDER BY testid DESC";
            $result = $connection->query($sql);

            if(!$result) {
                die("Invalid query:" . $connection->error);
            }

            while($row = $result->fetch_assoc()){
                echo "<tr>
                    <td>".$row["testid"] ."</td>
                    <td>".$row["temp"] ."</td>
                    <td>".$row["heartrate"] ."</td>
                    <td>".$row["stressindex"] ."</td>
                    <td>".$row["date"] ."</td>
                    <td>".$row["time"] ."</td>
                </tr>";
            }  
            ?>
        </tbody>
    </table>
</body>
</html>
