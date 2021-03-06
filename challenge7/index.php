<?php
    include('mysql_connect.php');
    session_start();
?>

<html>
<head>
    <title>SIT Widgets</title>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>
        function encodeHTML(s) {
             return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
        }

        $( document ).ready(function() {
            $('#form-submit').click(function(ev) {
                ev.preventDefault();
                var input_box = $('#widget-name-input');
                var sanitized_input = encodeHTML(input_box.val());
                input_box.val(sanitized_input);
                $('#create-form').submit();
            });
        });
    </script>
</head>
<body>
    <h1>SIT Widgets</h1>
    <p>View and create your widgets!</p>
    <?php
        // Disable XSS Protection
        header('X-XSS-Protection: 0;');

        if (!empty($_POST['name'])) {
            $_SESSION['user'] = strip_tags($_POST['name']);
        }

        if (!empty($_POST['widget-name'])) {
            $widget = $_POST['widget-name'];
            $query = $link->prepare("INSERT INTO widget VALUES (?, ?)");
            $query->bind_param('ss', $pName, $pOwner);
            $pName = strip_tags($_POST['widget-name']);
            $pOwner = $_SESSION['user'];
            $query->execute();
        }

        if (empty($_SESSION['user'])) {
            echo "<h2>Enter your name!</h2>
            <form method='POST'>
                <input type='text' name='name' />
                <input type='submit' />
            </form>";
        } else {
            echo "<h1>Hi " . $_SESSION['user'] . "!</h1>";
            echo "<h2>New Widget</h2>";
            echo "<form id='create-form' method='POST'>
                <input id='widget-name-input' type='text' name='widget-name' />
                <input id='form-submit' type='submit' />
            </form>";
            $query = $link->prepare('SELECT * FROM widget WHERE owner = ?');
            $query->bind_param('s', $pOwner);
            $pOwner = $_SESSION['user'];
            $query->execute();
            $query->bind_result($name, $owner);
            echo "<!-- TO-DO: Implement link tags! -->";
            echo "<ul>";
            while ($query->fetch()) {
                echo "<li><a href='#" . $name . "'>" . $name . "</a></li>";
            }
            echo "</ul>";
        }
    ?>
</body>
</html>
